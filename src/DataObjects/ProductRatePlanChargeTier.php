<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/E1_SOAP_API_Object_Reference/ProductRatePlanChargeTier
 */
class ProductRatePlanChargeTier extends DataObject
{
    protected $type = 'ProductRatePlanChargeTier';

    public static function getDefaultColumns()
    {
        // Note: You can only use Price or DiscountAmount or DiscountPercentage in one ProductRatePlanChargeTier query.

        return [
            'Id',
            'IsOveragePrice',
            'ProductRatePlanChargeId',
            'Currency',
            'Tier',
            'Price',
            // 'DiscountAmount',
            // 'DiscountPercentage',
            'PriceFormat',
            'StartingUnit',
            'EndingUnit',
            'CreatedById',
            'CreatedDate',
            'UpdatedById',
            'UpdatedDate',
        ];
    }

    public static function getOptionalColumns()
    {
        return [
            'EndingUnit',
            'Price',
            'DiscountAmount',
            'DiscountPercentage',
        ];
    }
}
