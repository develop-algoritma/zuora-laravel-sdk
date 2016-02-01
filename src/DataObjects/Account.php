<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/E1_SOAP_API_Object_Reference/Account
 */
class Account extends DataObject
{
    protected $type = 'Account';

    const BCD_SETTING_OPTION_AUTO_SET = 'AutoSet';
    const BCD_SETTING_OPTION_MANUAL_SET = 'ManualSet';

    const STATUS_DRAFT = 'Draft';
    const STATUS_ACTIVE = 'Active';
    const STATUS_CANCELED = 'Canceled';

    const PAYMENT_TERM_DUE_UPON_RECEIPT = 'Due Upon Receipt';
    const PAYMENT_TERM_NET_7 = 'Net 7';
    const PAYMENT_TERM_NET_30 = 'Net 30';
    const PAYMENT_TERM_NET_60 = 'Net 60';

    public static function getDefaultColumns()
    {
        return [
            'AccountNumber',
            'AdditionalEmailAddresses',
            'AllowInvoiceEdit',
            'AutoPay',
            'Balance',
            'Batch',
            'BcdSettingOption',
            'BillCycleDay',
            'BillToId',
            'CommunicationProfileId',
            'CreatedById',
            'CreatedDate',
            'CreditBalance',
            'CrmId',
            'Currency',
            'CustomerServiceRepName',
            'DefaultPaymentMethodId',
            'Id',
            'InvoiceDeliveryPrefsEmail',
            'InvoiceDeliveryPrefsPrint',
            'InvoiceTemplateId',
            'LastInvoiceDate',
            'Name',
            'Notes',
            'ParentId',
            'PaymentGateway',
            'PaymentTerm',
            'PurchaseOrderNumber',
            'SalesRepName',
            'SoldToId',
            'Status',
            'TaxCompanyCode',
            'TaxExemptCertificateID',
            'TaxExemptCertificateType',
            'TaxExemptDescription',
            'TaxExemptEffectiveDate',
            'TaxExemptExpirationDate',
            'TaxExemptIssuingJurisdiction',
            'TaxExemptStatus',
            'TotalInvoiceBalance',
            'UpdatedById',
            'UpdatedDate',
            'VATId',
        ];
    }

    public static function getOptionalColumns()
    {
        return [
            'AdditionalEmailAddresses',
            'CrmId',
            'CustomerServiceRepName',
            'LastInvoiceDate',
            'Notes',
            'ParentId',
            'PaymentGateway',
            'PurchaseOrderNumber',
            'SalesRepName',
            'TaxCompanyCode',
            'TaxExemptCertificateID',
            'TaxExemptCertificateType',
            'TaxExemptDescription',
            'TaxExemptEffectiveDate',
            'TaxExemptExpirationDate',
            'TaxExemptIssuingJurisdiction',
            'VATId',
        ];
    }
}
