<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/DC_Developers/SOAP_API/E1_SOAP_API_Object_Reference/Invoice
 */
class Invoice extends DataObject
{
    protected $type = 'Invoice';

    public static function getDefaultColumns()
    {
        return [
            'AccountId',
            'AdjustmentAmount',
            'Amount',
            'AmountWithoutTax',
            'Balance',
            'Comments',
            'CreatedById',
            'CreatedDate',
            'CreditBalanceAdjustmentAmount',
            'DueDate',
            'Id',
            'IncludesOneTime',
            'IncludesRecurring',
            'IncludesUsage',
            'InvoiceDate',
            'InvoiceNumber',
            'LastEmailSentDate',
            'PaymentAmount',
            'PostedBy',
            'PostedDate',
            'RefundAmount',
            'Source',
            'SourceId',
            'Status',
            'TargetDate',
            'TaxAmount',
            'TaxExemptAmount',
            'TransferredToAccounting',
            'UpdatedDate',
        ];
    }

    public static function getOptionalColumns()
    {
        return [
            'Comments',
            'LastEmailSentDate',
            'Source',
            'SourceId',
            'TransferredToAccounting',
        ];
    }
}
