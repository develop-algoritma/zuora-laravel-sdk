<?php

use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Subscription;

class SubscriptionTest extends TestCase
{
    public function testSubscribeExistingAccount()
    {
        $zuora = $this->getZuora();

        $product = current($zuora->getAllProducts(null, 1));
        $ratePlan = current($zuora->getRatePlansForProduct($product, null, 1));

        $account = $this->makeAccount();
        $contact = $this->makeContact();
        $paymentMethod = $this->makePaymentMethod();
        $subscription = $this->makeSubscription();

        try {
            $acc = $zuora->createAccount($account, $contact, $paymentMethod);
            $result = $zuora->subscribe(new Account(['Id' => $acc['Id']]), $subscription, $ratePlan);

            $this->assertNotEmpty($result->result->SubscriptionId);
            $this->assertEquals($acc['Id'], $result->result->AccountId);
        } catch (\Exception $e) {
            print_r($zuora->getApi()->getClient()->__getLastRequest());

            throw $e;
        } finally {
            if ($acc) {
                $zuora->getApi()->delete('Account', $acc['Id']);
            }
        }
    }

    public function testSubscribeAndCreateAccount()
    {
        $zuora = $this->getZuora();

        $product = current($zuora->getAllProducts(null, 1));
        $ratePlan = current($zuora->getRatePlansForProduct($product, null, 1));
        $ratePlanCharge = current($zuora->getChargesForProductRatePlan($ratePlan, null, 1));

        $account = $this->makeAccount();
        $contact = $this->makeContact();
        $paymentMethod = $this->makePaymentMethod();
        $subscription = $this->makeSubscription();

        try {
            $result = $zuora->subscribe($account, $subscription, $ratePlan, $ratePlanCharge, $paymentMethod, $contact);

            $this->assertNotEmpty($result->result->SubscriptionId);
            $this->assertNotEmpty($result->result->AccountId);
        } catch (\Exception $e) {
            print_r($zuora->getApi()->getClient()->__getLastRequest());

            throw $e;
        }

        $zuora->getApi()->delete('Account', $result->result->AccountId);
    }

    public function testGetAllSubscriptions()
    {
        $zuora = $this->getZuora();

        $subscriptions = $zuora->getAllSubscriptions(null, 1);

        $this->assertTrue(is_array($subscriptions));
        $this->assertCount(1, $subscriptions, 'There has to be at least 1 product');
        $this->checkSubscriptionObject($subscriptions[0]);

        return $subscriptions[0];
    }

    /**
     * @depends testGetAllSubscriptions
     */
    public function testGetOneSubscription(Subscription $subscription)
    {
        $zuora = $this->getZuora();
        $result = $zuora->getOneSubscription($subscription['Id']);

        $this->checkSubscriptionObject($result);
        $this->assertEquals($subscription->toArray(), $result->toArray());
    }

    /**
     * @depends testGetAllSubscriptions
     */
    public function testGetSubscriptionForAccount(Subscription $subscription)
    {
        $zuora = $this->getZuora();
        $result = $zuora->getSubscriptionsForAccount($subscription['AccountId']);

        $this->assertGreaterThanOrEqual(1, $result);
        $this->checkSubscriptionObject($result[0]);
        $this->assertEquals($result[0]['AccountId'], $subscription['AccountId']);
    }
}
