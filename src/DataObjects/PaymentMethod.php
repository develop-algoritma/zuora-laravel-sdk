<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/E1_SOAP_API_Object_Reference/PaymentMethod
 */
class PaymentMethod extends DataObject
{
    protected $type = 'PaymentMethod';

    const ACH_ACCOUNT_TYPE_BusinessChecking = 'BusinessChecking';
    const ACH_ACCOUNT_TYPE_Checking = 'Checking';
    const ACH_ACCOUNT_TYPE_Saving = 'Saving';

    const BANK_TRANSFER_TYPE_AUTOMATISCH_INCASSO = 'AutomatischIncasso';
    const BANK_TRANSFER_TYPE_LASTSCHRIFT_DE = 'LastschriftDE';
    const BANK_TRANSFER_TYPE_LASTSCHRIFT_AT = 'LastschriftAT';
    const BANK_TRANSFER_TYPE_DEMANDE_DE_PRELEVEMENT = 'DemandeDePrelevement';
    const BANK_TRANSFER_TYPE_DIRECT_DEBIT_UK = 'DirectDebitUK';
    const BANK_TRANSFER_TYPE_DOMICIL = 'Domicil';
    const BANK_TRANSFER_TYPE_LASTSCHRIFT_CH = 'LastschriftCH';
    const BANK_TRANSFER_TYPE_RID = 'RID';
    const BANK_TRANSFER_TYPE_ORDEN_DE_DOMICILIACION = 'OrdenDeDomiciliacion';

    const PAYMENT_METHOD_STATUS_ACTIVE = 'Active';
    const PAYMENT_METHOD_STATUS_CLOSED = 'Closed';

    const TYPE_ACH = 'ACH';
    const TYPE_BANK_TRANSFER = 'BankTransfer';
    const TYPE_CASH = 'Cash';
    const TYPE_CHECK = 'Check';
    const TYPE_CREDIT_CARD = 'CreditCard';
    const TYPE_CREDIT_CARD_REFERENCE_TRANSACTION = 'CreditCardReferenceTransaction';
    const TYPE_DEBIT_CARD = 'DebitCard';
    const TYPE_OTHER = 'Other';
    const TYPE_PAYPAL = 'PayPal';
    const TYPE_WIRE_TRANSFER = 'WireTransfer';

    const PAYPAL_TYPE_EXPRESS_CHECKOUT = 'ExpressCheckout';
    const PAYPAL_TYPE_ADAPTIVE_PAYMENTS = 'AdaptivePayments';

    public static function getDefaultColumns()
    {
        return [
            'AccountId',
            'Active',
            'AchAbaCode',
            'AchAccountName',
            // 'AchAccountNumber', // Insert-only field
            'AchAccountNumberMask',
            'AchAccountType',
            'AchAddress1',
            'AchAddress2',
            'AchBankName',
            'BankBranchCode',
            'BankCheckDigit',
            'BankCity',
            'BankCode',
            'BankIdentificationNumber',
            'BankName',
            'BankPostalCode',
            'BankStreetName',
            'BankStreetNumber',
            'BankTransferAccountName',
            // 'BankTransferAccountNumber',// Insert-only field
            'BankTransferAccountNumberMask',
            'BankTransferAccountType',
            'BankTransferType',
            'BusinessIdentificationCode',
            'City',
            'Country',
            'CreatedById',
            'CreatedDate',
            'CreditCardAddress1',
            'CreditCardAddress2',
            'CreditCardCity',
            'CreditCardCountry',
            'CreditCardExpirationMonth',
            'CreditCardExpirationYear',
            'CreditCardHolderName',
            'CreditCardMaskNumber',
            // 'CreditCardNumber', // Insert-only field
            'CreditCardPostalCode',
            // 'CreditCardSecurityCode', // To ensure PCI compliance, this value is not stored and cannot be queried.
            'CreditCardState',
            'CreditCardType',
            'DeviceSessionId',
            'Email',
            'FirstName',
            'IBAN',
            'Id',
            'IPAddress',
            'LastFailedSaleTransactionDate',
            'LastName',
            'LastTransactionDateTime',
            'LastTransactionStatus',
            'ExistingMandate',
            'MandateCreationDate',
            'MandateID',
            'MandateReceived',
            'MandateUpdateDate',
            'MaxConsecutivePaymentFailures',
            'Name',
            'NumConsecutiveFailures',
            'PaymentMethodStatus',
            'PaymentRetryWindow',
            'PaypalBaid',
            'PaypalEmail',
            'PaypalPreapprovalKey',
            'PaypalType',
            'Phone',
            'PostalCode',
            // 'SkipValidation', // Insert-only field
            'State',
            'StreetName',
            'StreetNumber',
            'TotalNumberOfErrorPayments',
            'TotalNumberOfProcessedPayments',
            'Type',
            'UpdatedById',
            'UpdatedDate',
            'UseDefaultRetryRule',
        ];
    }

    public static function getOptionalColumns()
    {
        return [
            'AccountId',
            'AchAbaCode',
            'AchAccountName',
            'AchAccountNumberMask',
            'AchAccountType',
            'AchAddress1',
            'AchAddress2',
            'AchBankName',
            'BankBranchCode',
            'BankCheckDigit',
            'BankCity',
            'BankCode',
            'BankIdentificationNumber',
            'BankName',
            'BankPostalCode',
            'BankStreetName',
            'BankStreetNumber',
            'BankTransferAccountName',
            'BankTransferAccountNumberMask',
            'BankTransferAccountType',
            'BankTransferType',
            'BusinessIdentificationCode',
            'City',
            'Country',
            'CreditCardAddress1',
            'CreditCardAddress2',
            'CreditCardCity',
            'CreditCardCountry',
            'CreditCardExpirationMonth',
            'CreditCardExpirationYear',
            'CreditCardHolderName',
            'CreditCardMaskNumber',
            'CreditCardPostalCode',
            'CreditCardState',
            'CreditCardType',
            'DeviceSessionId',
            'Email',
            'FirstName',
            'IBAN',
            'IPAddress',
            'LastFailedSaleTransactionDate',
            'LastTransactionDateTime',
            'LastTransactionStatus',
            'LastName',
            'ExistingMandate',
            'MandateCreationDate',
            'MandateID',
            'MandateReceived',
            'MandateUpdateDate',
            'Name',
            'PaypalPreapprovalKey',
            'Phone',
            'PostalCode',
            'State',
            'StreetName',
            'StreetNumber',
            'MaxConsecutivePaymentFailures',
            'PaymentMethodStatus',
            'PaymentRetryWindow',
            'PaypalBaid',
            'PaypalEmail',
            'PaypalType',
        ];
    }
}
