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

    /**
     * @depends testGetAllProducts
     */
    public function testGetAllProductRatePlans(Product $product)
    {
        $zuora = $this->getZuora();
        $ratePlans = $zuora->getAllProductRatePlans($product);

        $this->assertTrue(is_array($ratePlans));
        $this->assertGreaterThanOrEqual(1, count($ratePlans), 'There has to be at least 1 rate plan for your product');
        $this->checkProductRatePlanObject($ratePlans[0]);

        return $ratePlans[0];
    }

    /**
     * @depends testGetAllProductRatePlans
     */
    public function testGetOneProductRatePlan(ProductRatePlan $ratePlan)
    {
        $zuora = $this->getZuora();
        $result = $zuora->getOneProductRatePlan($ratePlan['Id']);

        $this->checkProductRatePlanObject($result);
        $this->assertEquals($ratePlan->toArray(), $result->toArray());
    }

    /**
     * @depends testGetAllProductRatePlans
     */
    public function testGetProductRatePlanCurrencies(ProductRatePlan $ratePlan)
    {
        $zuora = $this->getZuora();

        $currencies = $zuora->getOneProductRatePlanActiveCurrencies($ratePlan);
        $this->assertTrue(is_array($currencies));
        $this->assertGreaterThanOrEqual(1, $currencies, 'There has to be at least 1 currency for rate plan');
    }

    /**
     * @depends testGetAllProductRatePlans
     */
    public function testGetAllProductRatePlanCharges(ProductRatePlan $ratePlan)
    {
        $zuora = $this->getZuora();

        $ratePlanCharges = $zuora->getAllProductRatePlanCharges($ratePlan);
        $this->assertTrue(is_array($ratePlanCharges));
        $this->assertGreaterThanOrEqual(1, $ratePlanCharges, 'There has to be at least 1 rate plan charge for your rate plan');

        $this->checkProductRatePlanChargeObject($ratePlanCharges[0]);

        return $ratePlanCharges[0];
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

    /**
     * @depends testGetAllProductRatePlanCharges
     */
    public function testGetAllProductRatePlanChargeTiers(ProductRatePlanCharge $ratePlanCharge)
    {
        $zuora = $this->getZuora();

        $tiers = $zuora->getAllProductRatePlanChargeTiers($ratePlanCharge);
        $this->assertTrue(is_array($tiers));
        $this->assertGreaterThanOrEqual(1, count($tiers), 'There has to be at least 1 rate plan charge tier for rate plan charge');
        $this->checkProductRatePlanChargeTierObject($tiers[0]);

        return $tiers[0];
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
