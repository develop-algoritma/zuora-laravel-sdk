<?php

use Spira\ZuoraSdk\DataObjects\Product;
use Spira\ZuoraSdk\DataObjects\ProductRatePlan;
use Spira\ZuoraSdk\DataObjects\ProductRatePlanCharge;
use Spira\ZuoraSdk\DataObjects\ProductRatePlanChargeTier;

/**
 * @group integration
 */
class ProductsTest extends TestCase
{
    public function testGetAllProducts()
    {
        $zuora = $this->getZuora();
        $products = $zuora->getAllProducts(null, 1);

        $this->assertTrue(is_array($products));
        $this->assertCount(1, $products, 'There has to be at least 1 product');
        $this->checkProductObject($products[0]);

        return $products[0];
    }

    /**
     * @depends testGetAllProducts
     */
    public function testGetOneProduct(Product $product)
    {
        $zuora = $this->getZuora();
        $result = $zuora->getOneProduct($product->Id);

        $this->checkProductObject($result);
        $this->assertEquals($product->toArray(), $result->toArray());
    }

    public function testGetAllRatePlans()
    {
        $zuora = $this->getZuora();
        $result = $zuora->getAllProductRatePlans(null, 1);

        $this->assertTrue(is_array($result));
        $this->assertCount(1, $result, 'There has to be 1 rate plan retrieved');

        $ratePlan = current($result);
        $this->checkProductRatePlanObject($ratePlan);

        return $ratePlan;
    }

    /**
     * @depends testGetAllRatePlans
     */
    public function testGetRatePlansForProduct(ProductRatePlan $ratePlan)
    {
        $zuora = $this->getZuora();
        $result = $zuora->getRatePlansForProduct($ratePlan['ProductId']);

        $this->assertTrue(is_array($result));
        $this->assertGreaterThanOrEqual(1, count($result), 'There has to be at least 1 rate plan for your product');
        $this->checkProductRatePlanObject($result[0]);

        $this->assertTrue(in_array($ratePlan['Id'], array_pluck($result, 'Id')));
    }

    /**
     * @depends testGetAllRatePlans
     */
    public function testGetOneProductRatePlan(ProductRatePlan $ratePlan)
    {
        $zuora = $this->getZuora();
        $result = $zuora->getOneProductRatePlan($ratePlan['Id']);

        $this->checkProductRatePlanObject($result);
        $this->assertEquals($ratePlan->toArray(), $result->toArray());
    }

    /**
     * @depends testGetAllRatePlans
     */
    public function testGetProductRatePlanCurrencies(ProductRatePlan $ratePlan)
    {
        $zuora = $this->getZuora();

        $currencies = $zuora->getOneProductRatePlanActiveCurrencies($ratePlan);
        $this->assertTrue(is_array($currencies));
        $this->assertGreaterThanOrEqual(1, $currencies, 'There has to be at least 1 currency for rate plan');
    }

    public function testGetAllProductRatePlanCharges()
    {
        $zuora = $this->getZuora();
        $result = $zuora->getAllProductRatePlanCharges(null, 1);

        $this->assertTrue(is_array($result));
        $this->assertCount(1, $result);

        $ratePlanCharge = current($result);
        $this->checkProductRatePlanChargeObject($ratePlanCharge);

        return $ratePlanCharge;
    }

    /**
     * @depends testGetAllProductRatePlanCharges
     */
    public function testGetChargesForRatePlan(ProductRatePlanCharge $ratePlanCharge)
    {
        $zuora = $this->getZuora();

        $result = $zuora->getChargesForProductRatePlan($ratePlanCharge['ProductRatePlanId']);
        $this->assertTrue(is_array($result));
        $this->assertGreaterThanOrEqual(1, $result, 'There has to be at least 1 rate plan charge for your rate plan');

        $this->checkProductRatePlanChargeObject($result[0]);

        $this->assertTrue(in_array($ratePlanCharge['Id'], array_pluck($result, 'Id')));
    }

    /**
     * @depends testGetAllProductRatePlanCharges
     */
    public function testGetOneProductRatePlanCharge(ProductRatePlanCharge $ratePlanCharge)
    {
        $zuora = $this->getZuora();
        $result = $zuora->getOneProductRatePlanCharge($ratePlanCharge);

        $this->checkProductRatePlanChargeObject($result);
        $this->assertEquals($ratePlanCharge->toArray(), $result->toArray());
    }

    public function testGetAllProductRatePlanChargeTiers()
    {
        $zuora = $this->getZuora();
        $result = $zuora->getAllProductRatePlanChargeTiers(null, 1);

        $this->assertTrue(is_array($result));
        $this->assertCount(1, $result);

        $tier = current($result);
        $this->checkProductRatePlanChargeTierObject($tier);

        return $tier;
    }

    /**
     * @depends testGetAllProductRatePlanChargeTiers
     */
    public function testGetTiersForRatePlanCharge(ProductRatePlanChargeTier $tier)
    {
        $zuora = $this->getZuora();

        $result = $zuora->getTiersForProductRatePlanCharge($tier['ProductRatePlanChargeId']);
        $this->assertTrue(is_array($result));
        $this->assertGreaterThanOrEqual(1, count($result), 'There has to be at least 1 rate plan charge tier');
        $this->checkProductRatePlanChargeTierObject($result[0]);

        $this->assertTrue(in_array($tier['Id'], array_pluck($result, 'Id')));
    }

    /**
     * @depends testGetAllProductRatePlanChargeTiers
     */
    public function testGetOneProductRatePlanChargeTier(ProductRatePlanChargeTier $tier)
    {
        $zuora = $this->getZuora();
        $result = $zuora->getOneProductRatePlanChargeTier($tier);

        $this->checkProductRatePlanChargeTierObject($result);
        $this->assertEquals($tier->toArray(), $result->toArray());
    }
}
