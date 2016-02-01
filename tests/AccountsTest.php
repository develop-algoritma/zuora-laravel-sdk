<?php

use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Contact;
use Spira\ZuoraSdk\DataObjects\PaymentMethod;

/**
 * @group integration
 */
class AccountsTest extends TestCase
{
    public function testGetAllAccounts()
    {
        $api = $this->getZuora();
        $accounts = $api->getAllAccounts();

        $this->assertTrue(is_array($accounts));
        $this->assertGreaterThanOrEqual(1, count($accounts), 'There has to be at least 1 account');
        $this->checkAccountObject($accounts[0]);

        return $accounts[1];
    }

    /**
     * @depends testGetAllAccounts
     */
    public function testGetOneAccount(Account $account)
    {
        $api = $this->getZuora();

        $result = $api->getOneAccount($account['Id']);
        $this->checkAccountObject($result);
        $this->assertEquals($account->toArray(), $result->toArray());
    }

    /**
     * @depends testGetAllAccounts
     */
    public function testGetAllAccountContacts(Account $account)
    {
        $api = $this->getZuora();

        $contacts = $api->getAllContacts($account);
        $this->assertTrue(is_array($contacts));
        $this->assertGreaterThanOrEqual(1, count($contacts), 'There has to be at least 1 contact for account');
        $this->checkContactObject($contacts[0]);

        return $contacts[0];
    }

    /**
     * @depends testGetAllAccountContacts
     */
    public function testGetOneContact(Contact $contact)
    {
        $api = $this->getZuora();

        $result = $api->getOneContact($contact);
        $this->checkContactObject($result);
        $this->assertEquals($contact->toArray(), $result->toArray());
    }

    public function testGetAllPaymentMethods()
    {
        $api = $this->getZuora();

        $paymentMethods = $api->getAllPaymentMethods();

        $this->assertTrue(is_array($paymentMethods));
        $this->assertGreaterThanOrEqual(1, count($paymentMethods));
        $this->checkPaymentTypeObject($paymentMethods[0]);
    }

    /**
     * @depends testGetAllAccounts
     */
    public function testGetPaymentMethodsForAccount(Account $account)
    {
        $api = $this->getZuora();

        $paymentMethods = $api->getPaymentMethodsForAccount($account);
        $this->assertTrue(is_array($paymentMethods));
        $this->assertGreaterThanOrEqual(1, count($paymentMethods));
        $this->checkPaymentTypeObject($paymentMethods[0]);

        return $paymentMethods[0];
    }

    /**
     * @depends testGetPaymentMethodsForAccount
     */
    public function testGetOnePaymentMethod(PaymentMethod $paymentMethod)
    {
        $api = $this->getZuora();

        $result = $api->getOnePaymentMethod($paymentMethod['Id']);
        $this->checkPaymentTypeObject($result);
        $this->assertEquals($paymentMethod->toArray(), $result->toArray());
    }
}
