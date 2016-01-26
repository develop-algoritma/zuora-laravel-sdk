<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/E1_SOAP_API_Object_Reference/Product
 */
class Product extends DataObject
{
    protected $type = 'Product';

    public static function getDefaultColumns()
    {
        return [
            'Id',
            'Name',
            'Description',
            'EffectiveEndDate',
            'EffectiveStartDate',
            'AllowFeatureChanges',
            'SKU',
            'Category',
            'UpdatedById',
            'UpdatedDate',
            'CreatedById',
            'CreatedDate',
        ];
    }
}
