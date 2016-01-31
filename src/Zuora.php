<?php

namespace Spira\ZuoraSdk;

use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Contact;
use Spira\ZuoraSdk\DataObjects\PaymentMethod;
use Spira\ZuoraSdk\DataObjects\Product;
use Spira\ZuoraSdk\Exception\LogicException;
use Spira\ZuoraSdk\DataObjects\ProductRatePlan;
use Spira\ZuoraSdk\DataObjects\ProductRatePlanCharge;
use Spira\ZuoraSdk\DataObjects\ProductRatePlanChargeTier;

/**
 * Business logic to interact with Zuora.
 */
class Zuora
{
    /** @var API */
    protected $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    /**
     * Get first resultset of objects from $table.
     *
     * @param $filtered - lambda called with QueryBuilder argument for adding conditions
     *
     * @return DataObject[]|bool
     */
    public function getAll($table, array $columns, $limit = null, \Closure $filtered = null)
    {
        $query = new QueryBuilder($table, $columns);

        if ($filtered) {
            $filtered($query);
        }

        $result = $this->api->query($query->toZoql(), $limit);

        if (empty($result->result->records)) {
            return false;
        }

        // Zuora API returns 1 object itself not in array
        if (is_object($result->result->records)) {
            return [$result->result->records];
        }

        return $result->result->records;
    }

    /**
     * Get one object from $table.
     *
     * @param $filtered - lambda called with QueryBuilder argument for adding conditions
     *
     * @return DataObject|bool
     */
    public function getOne($table, array $columns, \Closure $filtered = null)
    {
        $query = new QueryBuilder($table, $columns);

        if ($filtered) {
            $filtered($query);
        }

        return $this->fetchOne($query);
    }

    /**
     * Get one object from $table by id.
     *
     * @return DataObject|bool
     */
    public function getOneById($table, $columns, $id)
    {
        $query = new QueryBuilder($table, $columns);
        $query->where('id', '=', $id);

        return $this->fetchOne($query);
    }

    /**
     * Get all products.
     *
     * @return Product[]|bool
     */
    public function getAllProducts(array $columns = null, $limit = null)
    {
        return $this->getAll('Product', $columns ?: Product::getDefaultColumns(), $limit);
    }

    /**
     * Get one product by ID.
     *
     * @return Product|bool
     */
    public function getOneProduct($id, array $columns = null)
    {
        return $this->getOneById('Product', $columns ?: Product::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all product's rate plans.
     *
     * @param Product|string $product
     *
     * @return DataObject[]|bool
     */
    public function getAllProductRatePlans($product, array $columns = null, $limit = null)
    {
        $id = $this->getIdFromArg($product);

        return $this->getAll(
            'ProductRatePlan',
            $columns ?: ProductRatePlan::getDefaultColumns(),
            $limit,
            function (QueryBuilder $query) use ($id) {
                $query->where('ProductID', '=', $id);
            }
        );
    }

    /**
     * Get one product rate plan.
     *
     * @return ProductRatePlan|bool
     */
    public function getOneProductRatePlan($id, array $columns = null)
    {
        return $this->getOneById('ProductRatePlan', $columns ?: ProductRatePlan::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all available currencies for product rate plan.
     *
     * @param ProductRatePlan|string $ratePlan
     *
     * @return array
     */
    public function getOneProductRatePlanActiveCurrencies($ratePlan)
    {
        $result = $this->getOneProductRatePlan($this->getIdFromArg($ratePlan), ['ActiveCurrencies']);

        if (!$result || empty($result['ActiveCurrencies'])) {
            return [];
        }

        return array_map('trim', explode(',', $result['ActiveCurrencies']));
    }

    /**
     * Get all product rate plan charges.
     *
     * @param $ratePlan ProductRatePlan|string
     *
     * @return ProductRatePlanCharge[]|bool
     */
    public function getAllProductRatePlanCharges($ratePlan, array $columns = null, $limit = null)
    {
        $id = $this->getIdFromArg($ratePlan);

        return $this->getAll(
            'ProductRatePlanCharge',
            $columns ?: ProductRatePlanCharge::getDefaultColumns(),
            $limit,
            function (QueryBuilder $query) use ($id) {
                $query->where('ProductRatePlanId', '=', $id);
            }
        );
    }

    /**
     * Get one product rate plan charge.
     *
     * @return ProductRatePlanCharge|bool
     */
    public function getOneProductRatePlanCharge($id, array $columns = null)
    {
        return $this->getOneById('ProductRatePlanCharge', $columns ?: ProductRatePlanCharge::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all product rate plan charge tiers.
     *
     * @param ProductRatePlanCharge|string $ratePlanCharge
     *
     * @return ProductRatePlanChargeTier[]|bool
     */
    public function getAllProductRatePlanChargeTiers($ratePlanCharge, array $columns = null, $limit = null)
    {
        $id = $this->getIdFromArg($ratePlanCharge);

        return $this->getAll(
            'ProductRatePlanChargeTier',
            $columns ?: ProductRatePlanChargeTier::getDefaultColumns(),
            $limit,
            function (QueryBuilder $query) use ($id) {
                $query->where('ProductRatePlanChargeId', '=', $id);
            }
        );
    }

    /**
     * Get one product rate plan charge tiers.
     *
     * @return ProductRatePlanChargeTier|bool
     */
    public function getOneProductRatePlanChargeTier($id, array $columns = null)
    {
        return $this->getOneById('ProductRatePlanChargeTier', $columns ?: ProductRatePlanChargeTier::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all accounts.
     *
     * @return bool|Account[]
     */
    public function getAllAccounts(array $columns = null, $limit = null)
    {
        return $this->getAll('Account', $columns ?: Account::getDefaultColumns(), $limit);
    }

    /**
     * Get one account.
     *
     * @return bool|Account
     */
    public function getOneAccount($id, array $columns = null)
    {
        return $this->getOneById('Account', $columns ?: Account::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all account's contacts.
     *
     * @param $account Account|string
     *
     * @return bool|Contact[]
     */
    public function getAllContacts($account, array $columns = null, $limit = null)
    {
        $id = $this->getIdFromArg($account);

        return $this->getAll(
            'Contact',
            $columns ?: Contact::getDefaultColumns(),
            $limit,
            function (QueryBuilder $query) use ($id) {
                $query->where('AccountId', '=', $id);
            }
        );
    }

    /**
     * @return bool|Contact
     */
    public function getOneContact($id, array $columns = null)
    {
        return $this->getOneById('Contact', $columns ?: Contact::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all account's payment mehtods.
     *
     * @param $account Account|string
     *
     * @return bool|DataObject[]
     */
    public function getAllPaymentMethods($account, array $columns = null, $limit = null)
    {
        $id = $this->getIdFromArg($account);

        return $this->getAll(
            'PaymentMethod',
            $columns ?: PaymentMethod::getDefaultColumns(),
            $limit,
            function (QueryBuilder $query) use ($id) {
                $query->where('AccountId', '=', $id);
            }
        );
    }

    /**
     * @return bool|PaymentMethod
     */
    public function getOnePaymentMethod($id, array $columns = null)
    {
        return $this->getOneById('PaymentMethod', $columns ?: PaymentMethod::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get ID from DataObject or return value.
     */
    protected function getIdFromArg($object)
    {
        if ($object instanceof DataObject) {
            return $object->Id;
        }

        if (is_array($object) || is_object($object)) {
            throw new LogicException('Cannot get ID from '.gettype($object).': you should pass string or DataObject');
        }

        return (string) $object;
    }

    protected function fetchOne(QueryBuilder $query)
    {
        $result = $this->api->query($query->toZoql(), 1);

        return !empty($result->result->records) ? $result->result->records : false;
    }
}
