<?php

/**
 * @group integration
 */
class ProductsTest extends TestCase
{
    public function testProductGetters()
    {
        $zuora = $this->makeZuora();

        // Test get all
        $products = $zuora->getAllProducts();
        $this->assertTrue(is_array($products));
        $this->assertGreaterThanOrEqual(1, count($products), 'There has to be at least 1 product');
        $this->checkProductObject($products[0]);

        // Test get one
        $product = $zuora->getOneProduct($products[0]->Id);
        $this->checkProductObject($product);
        $this->assertEquals($products[0]->toArray(), $product->toArray());

        return true;
    }

    /**
     * @depends testProductGetters
     */
    public function testProductRatePlansGetters()
    {
        $zuora = $this->makeZuora();
        $product = $this->getFirstProduct($zuora);

        // Test get all
        $ratePlans = $zuora->getAllProductRatePlans($product);
        $this->assertTrue(is_array($ratePlans));
        $this->assertGreaterThanOrEqual(1, count($ratePlans), 'There has to be at least 1 rate plan for your product');

        $this->checkProductRatePlanObject($ratePlans[0]);

        // Test get one
        $ratePlan = $zuora->getOneProductRatePlan($ratePlans[0]['Id']);
        $this->checkProductRatePlanObject($ratePlan);

        // Test get currencies
        $currencies = $zuora->getOneProductRatePlanActiveCurrencies($ratePlan);
        $this->assertTrue(is_array($currencies));
        $this->assertGreaterThanOrEqual(1, $currencies, 'There has to be at least 1 currency for rate plan');
    }

    /**
     * @depends testProductRatePlansGetters
     */
    public function testProductRatePlanChargeGetters()
    {
        $zuora = $this->makeZuora();
        $product = $this->getFirstProduct($zuora);
        $ratePlan = current($zuora->getAllProductRatePlans($product));

        // Test get all
        $ratePlanCharges = $zuora->getAllProductRatePlanCharges($ratePlan);
        $this->assertTrue(is_array($ratePlanCharges));
        $this->assertGreaterThanOrEqual(1, $ratePlanCharges, 'There has to be at least 1 rate plan charge for your rate plan');

        $this->checkProductRatePlanChargeObject($ratePlanCharges[0]);

        // Test get one
        $ratePlanCharge = $zuora->getOneProductRatePlanCharge($ratePlanCharges[0]);
        $this->checkProductRatePlanChargeObject($ratePlanCharge);
        $this->assertEquals($ratePlanCharges[0]->toArray(), $ratePlanCharge->toArray());
    }

    /**
     * @depends testProductRatePlanChargeGetters
     */
    public function testProductRatePlanChargeTierGetters()
    {
        $zuora = $this->makeZuora();
        $product = $this->getFirstProduct($zuora);
        $ratePlan = current($zuora->getAllProductRatePlans($product, null, 1));
        $ratePlanCharge = current($zuora->getAllProductRatePlanCharges($ratePlan, null, 1));

        // Test get all
        $tiers = $zuora->getAllProductRatePlanChargeTiers($ratePlanCharge);
        $this->assertTrue(is_array($tiers));
        $this->assertGreaterThanOrEqual(1, count($tiers), 'There has to be at least 1 rate plan charge tier for rate plan charge');
        $this->checkProductRatePlanChargeTierObject($tiers[0]);

        // Test get one
        $tier = $zuora->getOneProductRatePlanChargeTier($tiers[0]);
        $this->checkProductRatePlanChargeTierObject($tier);
        $this->assertEquals($tiers[0]->toArray(), $tier->toArray());
    }
}
