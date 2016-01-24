<?php

namespace Spira\ZuoraSdk;

use Illuminate\Support\Arr;
use Spira\ZuoraSdk\DataObjects\Error;
use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Product;
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

    function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $objects DataObject|DataObject[]
     */
    public function create($objects)
    {
        $objects = $this->prepareSoapVars($objects);

        $this->shouldBeLoggedIn();

        return $this->call('create', ['zObjects' => $objects]);
    }

    /**
     * @param $objects DataObject|DataObject[]
     */
    public function update($objects)
    {
        $objects = $this->prepareSoapVars($objects);

        $this->shouldBeLoggedIn();

        return $this->call('update', ['zObjects' => $objects]);
    }

    /**
     * @param $type - type of object being deleted
     * @param int|array $ids - object IDs
     */
    public function delete($type, $ids)
    {
        $this->shouldBeLoggedIn();

        return $this->call('delete', ['delete' => ['type' => $type, 'ids' => $ids]]);
    }

    /**
     * Run a query
     */
    public function query($query, $limit = null)
    {
        $this->shouldBeLoggedIn();

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

        $this->shouldBeLoggedIn();

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
            $result = $this->getClient()->login(Arr::only($this->config, ['username', 'password']));

            $this->session = new \SoapHeader(
                'http://api.zuora.com/',
                'SessionHeader',
                ['session' => $result->result->Session]
            );
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
    protected function call($method, array $arguments, array $headers = [])
    {
        $headersCombined = array_merge($this->headers, $headers);
        if ($this->session) {
            $headersCombined[] = $this->session;
        }

        return $this->getClient()->__soapCall($method, $arguments, null, $headersCombined);
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