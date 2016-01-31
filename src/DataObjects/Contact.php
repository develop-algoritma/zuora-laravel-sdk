<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/E1_SOAP_API_Object_Reference/Contact
 */
class Contact extends DataObject
{
    protected $type = 'Contact';

    public static function getDefaultColumns()
    {
        return [
            'AccountId',
            'Address1',
            'Address2',
            'City',
            'Country',
            'CreatedById',
            'CreatedDate',
            'Description',
            'Fax',
            'FirstName',
            'HomePhone',
            'Id',
            'LastName',
            'MobilePhone',
            'NickName',
            'OtherPhone',
            'OtherPhoneType',
            'PersonalEmail',
            'PostalCode',
            'State',
            'TaxRegion',
            'UpdatedById',
            'UpdatedDate',
            'WorkEmail',
            'WorkPhone',
        ];
    }

    public static function getOptionalColumns()
    {
        return [
            'Address1',
            'Address2',
            'City',
            'Description',
            'Fax',
            'HomePhone',
            'MobilePhone',
            'NickName',
            'OtherPhone',
            'OtherPhoneType',
            'PersonalEmail',
            'PostalCode',
            'State',
            'TaxRegion',
            'WorkPhone',
        ];
    }
}
