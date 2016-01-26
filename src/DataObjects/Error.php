<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/L_Error_Handling/Errors
 */
class Error extends DataObject
{
    protected $type = 'zError';
}
