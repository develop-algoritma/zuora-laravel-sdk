<?php

namespace Spira\ZuoraSdk;

use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Contact;
use Spira\ZuoraSdk\DataObjects\Invoice;
use Spira\ZuoraSdk\DataObjects\Payment;
use Spira\ZuoraSdk\DataObjects\Product;
use Spira\ZuoraSdk\DataObjects\Subscription;
use Spira\ZuoraSdk\Exception\LogicException;
use Spira\ZuoraSdk\DataObjects\PaymentMethod;
use Spira\ZuoraSdk\Exception\NotFoundException;
use Spira\ZuoraSdk\DataObjects\ProductRatePlan;
use Spira\ZuoraSdk\DataObjects\SubscribeOptions;
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

    /** @return API */
    public function getApi()
    {
        return $this->api;
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
     *
     * @throws NotFoundException
     */
    public function getOne($table, array $columns, \Closure $filtered = null)
    {
        $query = new QueryBuilder($table, $columns);

        if ($filtered) {
            $filtered($query);
        }

        if ($result = $this->fetchOne($query)) {
            return $result;
        }

        throw new NotFoundException($table.' not found');
    }

    /**
     * Get one object from $table by id.
     *
     * @return DataObject|bool
     *
     * @throws NotFoundException
     */
    public function getOneById($table, $columns, $id)
    {
        $query = new QueryBuilder($table, $columns);
        $query->where('id', '=', $id);

        if ($result = $this->fetchOne($query)) {
            return $result;
        }

        throw new NotFoundException(sprintf('%s with id "%s" does not exists', $table, $id));
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
     *
     * @throws NotFoundException
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
     * @return ProductRatePlan[]|bool
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
     *
     * @throws NotFoundException
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
     *
     * @throws NotFoundException
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
     *
     * @throws NotFoundException
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
     *
     * @throws NotFoundException
     */
    public function getOneProductRatePlanChargeTier($id, array $columns = null)
    {
        return $this->getOneById('ProductRatePlanChargeTier', $columns ?: ProductRatePlanChargeTier::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Create a subscription.
     */
    public function subscribe(
        Account $account,
        Subscription $subscription,
        ProductRatePlan $ratePlan,
        ProductRatePlanCharge $ratePlanCharge = null,
        PaymentMethod $paymentMethod = null,
        Contact $contact = null,
        SubscribeOptions $subscribeOptions = null
    ) {
        $data = [];

        $data['Account'] = $account->toArray();
        $paymentMethod && $data['PaymentMethod'] = $paymentMethod->toArray();
        $contact && $data['BillToContact'] = $contact->toArray();
        $subscribeOptions && $data['SubscribeOptions'] = $subscribeOptions->toArray();

        $ratePlanData = ['RatePlan' => ['ProductRatePlanId' => $ratePlan['Id']]];
        if ($ratePlanCharge) {
            $ratePlanData['RatePlanChargeData'] = [
                ['RatePlanCharge' => ['ProductRatePlanChargeId' => $ratePlanCharge['Id']]],
            ];
        }

        $data['SubscriptionData'] = [
            'Subscription' => $subscription->toArray(),
            'RatePlanData' => $ratePlanData,
        ];

        return $this->api->subscribe($data);
    }

    /**
     * Create and activate account.
     * If payment method supplied it saved as default payment method to account.
     *
     * @param Account       $account
     * @param Contact       $contact
     * @param PaymentMethod $method
     *
     * @return Account
     */
    public function createAccount(Account $account, Contact $contact, PaymentMethod $paymentMethod = null)
    {
        if (empty($account['Status'])) {
            $account['Status'] = Account::STATUS_DRAFT;
        }

        // Creation of account
        $result = $this->api->create($account);
        $account['Id'] = $result->result->Id;

        // Creation of contact
        $contact['AccountId'] = $account['Id'];

        $result = $this->api->create($contact);
        $contact['Id'] = $result->result->Id;

        // Saving of payment method if supplied
        if ($paymentMethod) {
            $paymentMethod['AccountId'] = $account['Id'];

            $result = $this->api->create($paymentMethod);
            $paymentMethod['Id'] = $result->result->Id;

            $account['DefaultPaymentMethodId'] = $paymentMethod['Id'];
        }

        // Updating account with contact and payment method and activating it
        $account['Status'] = Account::STATUS_ACTIVE;
        $account['BillToId'] = $contact['Id'];
        $account['SoldToId'] = $contact['Id'];

        $this->api->update($account);

        return $account;
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
     *
     * @throws NotFoundException
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
        return $this->getAll('Contact', $columns ?: Contact::getDefaultColumns(), $limit, $this->filterForAccount($account));
    }

    /**
     * Get one contact.
     *
     * @return bool|Contact
     *
     * @throws NotFoundException
     */
    public function getOneContact($id, array $columns = null)
    {
        return $this->getOneById('Contact', $columns ?: Contact::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all payment methods.
     *
     * @return bool|DataObject[]
     */
    public function getAllPaymentMethods(array $columns = null, $limit = null)
    {
        return $this->getAll('PaymentMethod', $columns ?: PaymentMethod::getDefaultColumns(), $limit);
    }

    /**
     * Get all account's payment methods.
     *
     * @param $account Account|string
     *
     * @return bool|DataObject[]
     */
    public function getPaymentMethodsForAccount($account, array $columns = null, $limit = null)
    {
        return $this->getAll('PaymentMethod', $columns ?: PaymentMethod::getDefaultColumns(), $limit, $this->filterForAccount($account));
    }

    /**
     * @return bool|PaymentMethod
     *
     * @throws NotFoundException
     */
    public function getOnePaymentMethod($id, array $columns = null)
    {
        return $this->getOneById('PaymentMethod', $columns ?: PaymentMethod::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all subscriptions.
     *
     * @return bool|Subscription[]
     */
    public function getAllSubscriptions(array $columns = null, $limit = null)
    {
        return $this->getAll('Subscription', $columns ?: Subscription::getDefaultColumns(), $limit);
    }

    /**
     * @return bool|Subscription
     *
     * @throws NotFoundException
     */
    public function getOneSubscription($id, array $columns = null)
    {
        return $this->getOneById('Subscription', $columns ?: Subscription::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all subscriptions for account.
     *
     * @param string|Account $account
     *
     * @return Subscription[]
     */
    public function getSubscriptionsForAccount($account, array $columns = null, $limit = null)
    {
        return $this->getAll('Subscription', $columns ?: Subscription::getDefaultColumns(), $limit, $this->filterForAccount($account));
    }

    /**
     * Get all payments.
     *
     * @return bool|Payment[]
     */
    public function getAllPayments(array $columns = null, $limit = null)
    {
        return $this->getAll('Payment', $columns ?: Payment::getDefaultColumns(), $limit);
    }

    /**
     * @return bool|Subscription
     *
     * @throws NotFoundException
     */
    public function getOnePayment($id, array $columns = null)
    {
        return $this->getOneById('Payment', $columns ?: Payment::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all payments for account.
     *
     * @param string|Account $account
     *
     * @return Payment[]
     */
    public function getPaymentsForAccount($account, array $columns = null, $limit = null)
    {
        return $this->getAll('Payment', $columns ?: Payment::getDefaultColumns(), $limit, $this->filterForAccount($account));
    }

    /**
     * Get all invoices.
     *
     * @return Invoice[]|bool
     */
    public function getAllInvoices(array $columns = null, $limit = null)
    {
        return $this->getAll('Invoice', $columns ?: Invoice::getDefaultColumns(), $limit);
    }

    /**
     * @return Invoice
     *
     * @throws NotFoundException
     */
    public function getOneInvoice($id, array $columns = null)
    {
        return $this->getOneById('Invoice', $columns ?: Invoice::getDefaultColumns(), $this->getIdFromArg($id));
    }

    /**
     * Get all invoices for account.
     *
     * @param string|Account $account
     *
     * @return Invoice[]
     */
    public function getInvoicesForAccount($account, array $columns = null, $limit = null)
    {
        return $this->getAll('Invoice', $columns ?: Invoice::getDefaultColumns(), $limit, $this->filterForAccount($account));
    }

    /**
     * Make query filter closure for account condition.
     *
     * @param string|Account $account
     */
    protected function filterForAccount($account)
    {
        $id = $this->getIdFromArg($account);

        return function (QueryBuilder $query) use ($id) {
            $query->where('AccountId', '=', $id);
        };
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

    /**
     * @return array|bool
     */
    protected function fetchOne(QueryBuilder $query)
    {
        $result = $this->api->query($query->toZoql(), 1);

        return $result->result->records;
    }
}
