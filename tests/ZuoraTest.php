<?php

use Spira\ZuoraSdk\DataObjects\Product;
use Spira\ZuoraSdk\QueryBuilder;
use Spira\ZuoraSdk\Zuora;

class ZuoraTest extends TestCase
{
    public function testGetAll()
    {
        $api = $this->makeApi();
        $api->shouldReceive('query')
            ->with('SELECT Id, Name FROM Product WHERE id = 123', 10)
            ->once();

        $zuora = new Zuora($api);
        $zuora->getAll('Product', ['Id', 'Name'], 10, $this->getWhereLambda());
    }

    public function testGetOne()
    {
        $api = $this->makeApi();
        $api->shouldReceive('query')
            ->with('SELECT Id, Name FROM Product WHERE id = 123', 1)
            ->once();

        $zuora = new Zuora($api);
        $zuora->getOne('Product', ['Id', 'Name'], $this->getWhereLambda());
    }

    public function testGetGetById()
    {
        $api = $this->makeApi();
        $api->shouldReceive('query')
            ->with('SELECT Id, Name FROM Product WHERE id = 321', 1)
            ->once();

        $zuora = new Zuora($api);
        $zuora->getOneById('Product', ['Id', 'Name'], 321);
    }

    /**
     * You should have at least one product in your demo account for passing this test.
     * @group integration
     */
    public function testGetAllReturnsArrayOfProductsForOneLimited()
    {
        $api = $this->makeApi(true);
        $zuora = new Zuora($api);

        $products = $zuora->getAll('Product', ['Id', 'Name'], 1);

        $this->assertTrue(is_array($products));
        $this->assertCount(1, $products);
        $this->checkProductObject(current($products));
    }

    /**
     * You should have at least one product in your demo account for passing this test.
     * @group integration
     */
    public function testGetAllReturnsArrayOfProducts()
    {
        $api = $this->makeApi(true);
        $zuora = new Zuora($api);

        $products = $zuora->getAll('Product', ['Id', 'Name']);
        $this->assertTrue(is_array($products));
        $this->assertGreaterThanOrEqual(1, count($products));
        $this->checkProductObject(current($products));
    }

    /**
     * You should have at least one product in your demo account for passing this test.
     * @group integration
     */
    public function testGetOneReturnsProduct()
    {
        $api = $this->makeApi(true);
        $zuora = new Zuora($api);

        $product = $zuora->getOne('Product', ['Id', 'Name']);

        $this->checkProductObject($product);
    }

    protected function checkProductObject($product)
    {
        $this->assertEquals(Product::class, get_class($product));
        $this->assertNotEmpty($product->Id);
        $this->assertNotEmpty($product->Name);
    }

    protected function getWhereLambda()
    {
        return function (QueryBuilder $builder) {
            $builder->where('id', '=', 123);
        };
    }
}
