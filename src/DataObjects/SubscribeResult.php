<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/DC_Developers/SOAP_API/F_SOAP_API_Complex_Types/SubscribeResult
 */
class SubscribeResult extends DataObject
{
    protected $type = 'SubscribeResult';

    public static function getDefaultColumns()
    {
        return [
            'AccountId',
            'AccountNumber',
            'ChargeMetricsData',
            'GatewayResponse',
            'GatewayResponseCode',
            'InvoiceId',
            'InvoiceNumber',
            'InvoiceResult',
            'PaymentId',
            'PaymentTransactionNumber',
            'SubscriptionId',
            'SubscriptionNumber',
            'TotalMrr',
            'TotalTcv',
        ];
    }
}
