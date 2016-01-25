<?php

namespace Spira\ZuoraSdk;

use Spira\ZuoraSdk\DataObjects\Product;

/**
 * Business logic to interact with Zuora
 */
class Zuora
{
    /** @var API */
    protected $api;

    function __construct(API $api)
    {
        $this->api = $api;
    }

    /**
     * @param $product Product|Product[]
     */
    public function addProduct($product)
    {
        return $this->api->create($product);
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->api->query('SELECT Id, Name FROM Product');
    }

    public function getProductRatePlans()
    {
        // TODO
    }

    public function subscribeUserToRatePlan()
    {
        // TODO
    }

    public function getUserSubscriptions()
    {
        // TODO
    }
}