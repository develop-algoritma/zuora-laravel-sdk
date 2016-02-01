<?php

class SubscriptionTest extends TestCase
{
    public function testSubscribe()
    {
        $zuora = $this->getZuora();

        $product = current($zuora->getAllProducts(null, 1));
        $ratePlan = current($zuora->getAllProductRatePlans($product, null, 1));
        $ratePlanCharge = current($zuora->getAllProductRatePlanCharges($ratePlan, null, 1));

        $account = $this->makeAccount();
        $contact = $this->makeContact();
        $paymentMethod = $this->makePaymentMethod();
        $subscription = $this->makeSubscription();

        try {
            $result = $zuora->subscribe($account, $subscription, $ratePlan, $ratePlanCharge, $paymentMethod, $contact);

            print_r($result);
        } catch (\Exception $e) {
            //            print_r($zuora->getApi()->getClient()->__getLastRequest());
//            print_r($zuora->getApi()->getClient()->__getLastResponse());

            throw $e;
        }
    }
}
