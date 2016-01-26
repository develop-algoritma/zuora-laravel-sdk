<?php

use Psr\Log\LoggerInterface;
use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Product;

class ApiTest extends TestCase
{
    public function testQuery()
    {
        $api = $this->makeApi();
        $api->shouldReceive('call')
            ->with('query', Mockery::type('array'), $this->makeLoginHeadersExpectation())
            ->once();

        $api->query('SELECT Id, Name FROM Product', 1);
    }

    public function testCreateOne()
    {
        $api = $this->makeApi();
        $api->shouldReceive('call')
            ->with('create', $this->makeObjectsExpectation())
            ->once();

        $api->create(new Product(['Name' => 'Ololo']));
    }

    public function testCreateMultiple()
    {
        $api = $this->makeApi();
        $api->shouldReceive('call')
            ->with('create', $this->makeObjectsExpectation(2))
            ->once();

        $api->create([new Product(['Name' => 'First']), new Product(['Name' => 'Second'])]);
    }

    public function testUpdate()
    {
        $api = $this->makeApi();
        $api->shouldReceive('call')
            ->with('update', $this->makeObjectsExpectation())
            ->once();

        $api->update(new Product(['Name' => 'Ololo']));
    }

    public function testDelete()
    {
        $api = $this->makeApi();
        $api->shouldReceive('call')
            ->with('delete', Mockery::type('array'))
            ->once();

        $api->delete('Product', 1);
    }

    /**
     * @group integration
     * @expectedException Spira\ZuoraSdk\Exception\ApiException
     * @expectedExceptionCode Spira\ZuoraSdk\Exception\ApiException::MISSING_REQUIRED_VALUE
     * @expectedExceptionMessage Missing required value: EffectiveStartDate
     */
    public function testApiErrorResponseThrowsAnException()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('debug')->once(); // Authorized
        $logger->shouldReceive('notice')->once(); // Method call() is called
        $logger->shouldReceive('error')->once(); // API returned an error

        $this->makeApi(true, $logger)->create(new Product(['Name' => 'Ololo']));
    }

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
}
