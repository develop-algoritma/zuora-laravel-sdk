<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

class Payment extends DataObject
{
    protected $type = 'Payment';

    public static function getDefaultColumns()
    {
        return [
            'AccountId',
            'AccountingCode',
            'Amount',
            'AppliedCreditBalanceAmount',
//          'AppliedInvoiceAmount',
            'AuthTransactionId',
            'BankIdentificationNumber',
            'CancelledOn',
            'Comment',
            'CreatedById',
            'CreatedDate',
            'EffectiveDate',
            'Gateway',
            'GatewayOrderId',
            'GatewayResponse',
            'GatewayResponseCode',
            'GatewayState',
            'Id',
//          'InvoiceId',
//          'InvoiceNumber',
//          'InvoicePaymentData',
            'MarkedForSubmissionOn',
            'PaymentMethodId',
            'PaymentMethodSnapshotId',
            'PaymentNumber',
            'ReferenceId',
            'RefundAmount',
            'SecondPaymentReferenceId',
            'SettledOn',
            'SoftDescriptor',
            'SoftDescriptorPhone',
            'Source',
            'SourceName',
            'Status',
            'SubmittedOn',
            'TransferredToAccounting',
            'Type',
            'UpdatedById',
            'UpdatedDate',
        ];
    }

    public static function getOptionalColumns()
    {
        return [
            'AccountingCode',
            'AuthTransactionId',
            'BankIdentificationNumber',
            'CancelledOn',
            'Comment',
            'Gateway',
            'GatewayOptionData',
            'GatewayOrderId',
            'GatewayResponse',
            'GatewayResponseCode',
            'GatewayState',
            'MarkedForSubmissionOn',
            'PaymentMethodId',
            'PaymentMethodSnapshotId',
            'PaymentNumber',
            'ReferenceId',
            'RefundAmount',
            'SecondPaymentReferenceId',
            'RefundAmountRefundAmount',
            'SettledOn',
            'SoftDescriptor',
            'SoftDescriptorPhone',
            'Source',
            'SourceName',
            'SubmittedOn',
            'TransferredToAccounting',
        ];
    }
}
