<?php

use Spira\ZuoraSdk\QueryBuilder;
use Spira\ZuoraSdk\DataObjects\Account;
use Spira\ZuoraSdk\DataObjects\Contact;
use Spira\ZuoraSdk\DataObjects\Invoice;
use Spira\ZuoraSdk\DataObjects\Payment;
use Spira\ZuoraSdk\DataObjects\PaymentMethod;

/**
 * @group integration
 */
class AccountsTest extends TestCase
{
    public function testCreateAccount()
    {
        $zuora = $this->getZuora();

        $account = $this->makeAccount();
        $contact = $this->makeContact();
        $paymentMethod = $this->makePaymentMethod();

        try {
            $zuora->createAccount($account, $contact, $paymentMethod);

            $this->assertNotEmpty($account['Id']);
            $this->assertNotEmpty($contact['Id']);
            $this->assertNotEmpty($paymentMethod['Id']);

            $account = $zuora->getOneAccount($account['Id']);

            $this->assertEquals(Account::STATUS_ACTIVE, $account['Status'], 'Account is activated after creation');
            $this->assertEquals($contact['Id'], $account['BillToId'], 'Contact is assigned to Bill to');
            $this->assertEquals($contact['Id'], $account['SoldToId'], 'Contact is assigned to Sold to');
            $this->assertEquals($paymentMethod['Id'], $account['DefaultPaymentMethodId'], 'Default payment method is set');
        } finally {
            if ($id = $account['Id']) {
                $zuora->getApi()->delete('Account', $id);
            }
        }
    }

    public function testGetAllAccounts()
    {
        $api = $this->getZuora();
        $result = $api->getAllAccounts(null, 1);

        $this->assertTrue(is_array($result));
        $this->assertCount(1, $result, 'There has to be at least 1 account');

        $account = current($result);
        $this->checkAccountObject($account);

        return $account;
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
    public function testGetContactsForAccount(Account $account)
    {
        $api = $this->getZuora();

        $result = $api->getContactsForAccount($account);
        $this->assertTrue(is_array($result));
        $this->assertGreaterThanOrEqual(1, count($result), 'There has to be at least 1 contact for account');

        $account = current($result);
        $this->checkContactObject($account);

        return $account;
    }

    /**
     * @depends testGetContactsForAccount
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

        $result = $api->getAllPaymentMethods(
            null,
            1,
            function (QueryBuilder $query) {
                $query->where('AccountId', '!=', 'null');
            }
        );
        $this->assertTrue(is_array($result));
        $this->assertCount(1, $result);

        $paymentMethod = current($result);
        $this->checkPaymentTypeObject($paymentMethod);

        return $paymentMethod;
    }

    /**
     * @depends testGetAllPaymentMethods
     */
    public function testGetPaymentMethodsForAccount(PaymentMethod $paymentMethod)
    {
        $api = $this->getZuora();

        $result = $api->getPaymentMethodsForAccount($paymentMethod['AccountId']);
        $this->assertTrue(is_array($result));
        $this->assertGreaterThanOrEqual(1, count($result));
        $this->checkPaymentTypeObject($result[0]);
    }

    /**
     * @depends testGetAllPaymentMethods
     */
    public function testGetOnePaymentMethod(PaymentMethod $paymentMethod)
    {
        $api = $this->getZuora();

        $result = $api->getOnePaymentMethod($paymentMethod['Id']);
        $this->checkPaymentTypeObject($result);
        $this->assertEquals($paymentMethod->toArray(), $result->toArray());
    }

    public function testGetAllPayments()
    {
        $api = $this->getZuora();
        $result = $api->getAllPayments(null, 1);

        $this->assertTrue(is_array($result));
        $this->assertCount(1, $result, 'There has to be at least 1 payment');

        $payment = current($result);
        $this->checkPaymentObject($payment);

        return $payment;
    }

    /**
     * @depends testGetAllPayments
     */
    public function testGetPaymentsForAccount(Payment $payment)
    {
        $api = $this->getZuora();

        $result = $api->getPaymentsForAccount($payment['AccountId']);
        $this->checkPaymentObject($result[0]);

        $this->assertTrue(in_array($payment['Id'], array_pluck($result, 'Id')));
    }

    /**
     * @depends testGetAllPayments
     */
    public function testGetOnePayment(Payment $payment)
    {
        $api = $this->getZuora();

        $result = $api->getOnePayment($payment);
        $this->checkPaymentObject($result);
        $this->assertEquals($payment->toArray(), $result->toArray());
    }

    public function testGetAllInvoices()
    {
        $api = $this->getZuora();
        $result = $api->getAllInvoices(null, 1);

        $this->assertTrue(is_array($result));
        $this->assertCount(1, $result, 'There has to be at least 1 invoice');

        $invoice = current($result);
        $this->checkInvoiceObject($invoice);

        return $invoice;
    }

    /**
     * @depends testGetAllInvoices
     */
    public function testGetOneInvoice(Invoice $invoice)
    {
        $api = $this->getZuora();

        $result = $api->getOneInvoice($invoice['Id']);
        $this->checkInvoiceObject($result);
        $this->assertEquals($invoice->toArray(), $result->toArray());
    }

    /**
     * @depends testGetAllInvoices
     */
    public function testGetAllAccountInvoices(Invoice $invoice)
    {
        $api = $this->getZuora();

        $result = $api->getInvoicesForAccount($invoice['AccountId']);
        $this->assertTrue(is_array($result));
        $this->assertGreaterThanOrEqual(1, count($result));

        $this->assertTrue(in_array($invoice['Id'], array_pluck($result, 'Id')));
    }
}
