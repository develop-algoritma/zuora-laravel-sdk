<?php

use Spira\ZuoraSdk\API;
use Spira\ZuoraSdk\Zuora;
use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Contact;
use Spira\ZuoraSdk\DataObjects\Product;
use Spira\ZuoraSdk\DataObjects\PaymentMethod;
use Spira\ZuoraSdk\DataObjects\ProductRatePlan;
use Spira\ZuoraSdk\DataObjects\ProductRatePlanCharge;
use Spira\ZuoraSdk\DataObjects\ProductRatePlanChargeTier;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    protected $zuora;

    public function tearDown()
    {
        Mockery::close();
    }

    protected function checkDataObject($object, $class, array $fields = null)
    {
        $this->assertTrue(is_object($object), 'Assert '.gettype($object).' is an object');
        $this->assertTrue($object instanceof $class, 'Assert '.get_class($object).' is instance of '.$class);

        if (is_null($fields)) {
            $fields = $class::getRequiredColumns();
        }

        try {
            foreach ($fields as $field) {
                $this->assertArrayHasKey($field, $object);
            }
        } catch (\Exception $e) {
            echo sprintf(
                "\nObject data:\n%s\nMissing columns:\n%s\n",
                print_r($object->toArray(), true),
                print_r(array_diff($fields, array_keys($object->toArray())), true)
            );

            throw $e;
        }
    }

    protected function checkProductObject($product, $columns = null)
    {
        $this->checkDataObject($product, Product::class, $columns);
    }

    protected function checkProductRatePlanObject($ratePlan, $columns = null)
    {
        $this->checkDataObject($ratePlan, ProductRatePlan::class, $columns);
    }

    protected function checkProductRatePlanChargeObject($ratePlanCharge, $columns = null)
    {
        $this->checkDataObject($ratePlanCharge, ProductRatePlanCharge::class, $columns);
    }

    protected function checkProductRatePlanChargeTierObject($ratePlanCharge, $columns = null)
    {
        $this->checkDataObject($ratePlanCharge, ProductRatePlanChargeTier::class, $columns);
    }

    protected function checkAccountObject($account, $columns = null)
    {
        $this->checkDataObject($account, Account::class, $columns);
    }

    protected function checkContactObject($contact, $columns = null)
    {
        $this->checkDataObject($contact, Contact::class, $columns);
    }

    protected function checkPaymentTypeObject($paymentType, $columns = null)
    {
        $this->checkDataObject($paymentType, PaymentMethod::class, $columns);
    }

    /** @return API|\Mockery\MockInterface */
    protected function makeApi($credentialsRequired = false, $logger = null)
    {
        $config = require __DIR__.'/../../storage/config.php';

        if ($credentialsRequired && (empty($config['username']) || empty($config['password']))) {
            $this->markTestSkipped('If you want to run integration tests provide sandbox credentials in config.php file');
        }

        return Mockery::mock(API::class, [$config, $logger])->makePartial();
    }

    /** @return Zuora */
    protected function makeZuora($credentialsRequired = true, $logger = null)
    {
        return new Zuora($this->makeApi($credentialsRequired, $logger));
    }

    /** @return Zuora */
    protected function getZuora()
    {
        if (is_null($this->zuora)) {
            $this->zuora = $this->makeZuora();
        }

        return $this->zuora;
    }

    /**
     * @param int $objectsCount - number of expected objects count
     *
     * @return \Mockery\Matcher\Closure
     */
    protected function makeObjectsExpectation($objectsCount = 1)
    {
        return Mockery::on(
            function ($var) use ($objectsCount) {
                return is_array($var)
                && array_key_exists('zObjects', $var)
                && count($var['zObjects']) == $objectsCount
                && current($var['zObjects']) instanceof SoapVar;
            }
        );
    }

    /**
     * @param int $headersCount - number of expected headers count
     *
     * @return \Mockery\Matcher\Closure
     */
    protected function makeLoginHeadersExpectation($headersCount = 1)
    {
        return Mockery::on(
            function ($var) use ($headersCount) {
                return is_array($var) && count($var) == $headersCount && current($var) instanceof SoapHeader;
            }
        );
    }
}
