<?php

use Spira\ZuoraSdk\API;
use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Product;

class ApiTest extends TestCase
{
    /**
     * @expectedException \Spira\ZuoraSdk\Exception\LogicException
     * @expectedExceptionMessage No query locator stored from previous query
     */
    public function testErrorNoQueryLocatorForQueryMore()
    {
        $this->makeApi()->queryMore();
    }

    /**
     * @expectedException Spira\ZuoraSdk\Exception\LogicException
     * @expectedExceptionMessage Supplied arguments must be array or DataObject
     */
    public function testErrorBadArguments()
    {
        $this->makeApi()->create(123);
    }

    /**
     * @expectedException @expectedException Spira\ZuoraSdk\Exception\LogicException
     * @expectedExceptionMessage All DataObjects must be of the same type
     */
    public function testErrorCreateWithDifferentDataObjects()
    {
        $this->makeApi()->create([new Product(), new Account()]);
    }

    /** @return API */
    protected function makeApi(array $config = [])
    {
        $default = require __DIR__ . '/../storage/config.php';

        return new API(array_merge($default, $config));
    }
}
