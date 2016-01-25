<?php

namespace Spira\ZuoraSdk;

use Monolog\Logger;
use Illuminate\Support\Arr;
use Psr\Log\LoggerInterface;
use Monolog\Handler\NullHandler;
use Spira\ZuoraSdk\DataObjects\Error;
use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Product;
use Spira\ZuoraSdk\Exception\ApiException;
use Spira\ZuoraSdk\Exception\LogicException;

class API
{
    /** Limit of API for number of objects sent per-once */
    const MAX_API_OBJECTS = 50;

    protected $config;
    protected $queryLocator;

    /** @var \SoapHeader[] */
    protected $headers = [];

    /**
     * @var \SoapClient
     */
    protected $client;

    /** @var \SoapHeader */
    protected $session;

    /** @var LoggerInterface */
    protected $logger;

    function __construct(array $config = null, LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->logger = $logger ?: new Logger('zuora', [new NullHandler()]);
    }

    /**
     * @param $objects DataObject|DataObject[]
     */
    public function create($objects)
    {
        return $this->call('create', ['zObjects' => $this->prepareSoapVars($objects)]);
    }

    /**
     * @param $objects DataObject|DataObject[]
     */
    public function update($objects)
    {
        return $this->call('update', ['zObjects' => $this->prepareSoapVars($objects)]);
    }

    /**
     * @param $type - type of object being deleted
     * @param int|array $ids - object IDs
     */
    public function delete($type, $ids)
    {
        return $this->call('delete', ['delete' => ['type' => $type, 'ids' => $ids]]);
    }

    /**
     * Run a query
     */
    public function query($query, $limit = null)
    {
        $headers = $limit ? [$this->makeLimitHeader($limit)] : [];
        $result  = $this->call('query', ['query' => ['queryString' => $query]], $headers);

        // Store queryLocator for next queryMore() calls
        $this->queryLocator = !empty($result->result->queryLocator) ? $result->result->queryLocator : null;

        return $result;
    }

    /**
     * Get a next page from previous query
     */
    public function queryMore($limit = null)
    {
        if (!$this->hasMore()) {
            throw new LogicException('No query locator stored from previous query');
        }

        $data    = ['queryMore' => ['queryLocator' => $this->queryLocator]];
        $headers = $limit ? [$this->makeLimitHeader($limit)] : [];
        $result  = $this->call('queryMore', $data, $headers);

        // Store queryLocator for next queryMore() calls
        $this->queryLocator = !empty($result->result->queryLocator) ? $result->result->queryLocator : null;

        return $result;
    }

    /**
     * Check does query has next page
     */
    public function hasMore()
    {
        return !empty($this->queryLocator);
    }

    /**
     * @return \SoapClient
     */
    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new \SoapClient(
                $this->config['wsdl'],
                [
                    'soap_version' => SOAP_1_1,
                    'trace'        => true,
                    'exceptions'   => true,
                    'classmap'     => $this->getClassMap(),
                    'cache_wsdl'   => WSDL_CACHE_NONE,
                ]
            );
            $this->client->__setLocation($this->config['endpoint']);
        }

        return $this->client;
    }

    /**
     * @param \SoapClient $client
     * @return $this
     */
    public function setClient(\SoapClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Header for limit a query
     *
     * @return \SoapHeader
     */
    protected function makeLimitHeader($limit)
    {
        return new \SoapHeader('http://api.zuora.com/', 'QueryOptions', ['batchSize' => $limit]);
    }

    /**
     * Authorizes and stores session token
     */
    protected function shouldBeLoggedIn()
    {
        if (empty($this->session)) {
            try {
                $result = $this->getClient()->login(Arr::only($this->config, ['username', 'password']));
            } catch (\Exception $e) {
                $this->logger->error('Login error: ' . $e->getMessage(), Arr::except($this->config, 'password'));

                throw $e;
            }

            $this->session = new \SoapHeader(
                'http://api.zuora.com/',
                'SessionHeader',
                ['session' => $result->result->Session]
            );
            $this->logger->debug('Logged as ' . $this->config['username']);
        }
    }

    /**
     * Convert DataObjects to SoapVar
     *
     * @param $data
     * @return \SoapVar[]
     * @throws LogicException
     */
    protected function prepareSoapVars($data)
    {
        if ($data instanceof DataObject) {
            $data = [$data];
        }

        if (!is_array($data)) {
            throw new LogicException('Supplied arguments must be array or DataObject');
        }

        if (count($data) > static::MAX_API_OBJECTS) {
            throw new LogicException(sprintf('API does not support more than %d objects per request', static::MAX_API_OBJECTS));
        }

        $class = get_class(current($data));
        foreach ($data as $obj) {
            if (!($obj instanceof $class)) {
                throw new LogicException('All DataObjects must be of the same type');
            }
        }

        return array_map(
            function (DataObject $object) {
                return $object->toSoap();
            },
            $data
        );
    }


    /**
     * Call method on Zuora API
     *
     * @see \SoapClient::__soapCall
     */
    public function call($method, array $arguments, array $headers = [])
    {
        $this->logger->notice(sprintf('call(%s, %s)', $method, json_encode($arguments)));
        $this->shouldBeLoggedIn();

        $headersCombined = array_merge($this->headers, $headers);
        if ($this->session) {
            $headersCombined[] = $this->session;
        }

        $result = $this->getClient()->__soapCall($method, $arguments, null, $headersCombined);

        if (empty($result->result->Success)) {
            $err = ApiException::createFromApiObject(!empty($result->result->Errors) ? $result->result->Errors : null);
            $this->logger->error(sprintf('[%s] %s', $err->getCodeName(), $err->getMessage()));

            throw $err;
        }

        return $result;
    }

    /**
     * Classmap for mapping SOAP objects
     */
    protected function getClassMap()
    {
        return [
            'Product' => Product::class,
            'Account' => Account::class,
            'Error'   => Error::class,
        ];
    }
}