<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/E1_SOAP_API_Object_Reference/ProductRatePlan
 */
class ProductRatePlan extends DataObject
{
    protected $type = 'ProductRatePlan';

    public static function getDefaultColumns()
    {
        return [
            'Id',
            'Name',
            'Description',
            'ProductId',
            'EffectiveEndDate',
            'EffectiveStartDate',
            'CreatedById',
            'CreatedDate',
            'UpdatedById',
            'UpdatedDate',
            'PortalTitle__c'
        ];
    }

    public static function getOptionalColumns()
    {
        return ['Description'];
    }
}
