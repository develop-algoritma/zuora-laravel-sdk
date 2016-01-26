<?php

namespace Spira\ZuoraSdk\Exception;

use Spira\ZuoraSdk\DataObjects\Error;

class ApiException extends \RuntimeException
{
    const API_DISABLED = 1;
    const CANNOT_DELETE = 2;
    const CREDIT_CARD_PROCESSING_FAILURE = 3;
    const DUPLICATE_VALUE = 4;
    const INVALID_FIELD = 5;
    const INVALID_ID = 6;
    const INVALID_LOGIN = 7;
    const INVALID_SESSION = 8;
    const INVALID_TYPE = 9;
    const INVALID_VALUE = 10;
    const INVALID_VERSION = 11;
    const LOCK_COMPETITION = 12;
    const MALFORMED_QUERY = 13;
    const MAX_RECORDS_EXCEEDED = 14;
    const MISSING_REQUIRED_VALUE = 15;
    const REQUEST_EXCEEDED_LIMIT = 16;
    const REQUEST_EXCEEDED_RATE = 17;
    const SERVER_UNAVAILABLE = 18;
    const TEMPORARY_ERROR = 19;
    const TRANSACTION_FAILED = 20;
    const TRANSACTION_TERMINATED = 21;
    const TRANSACTION_TIMEOUT = 22;
    const UNKNOWN_ERROR = 23;

    public function getCodeName()
    {
        $reflection = new \ReflectionObject($this);

        return array_search($this->getCode(), $reflection->getConstants());
    }

    /**
     * @param Error $error
     * @throws ApiException
     */
    public static function createFromApiObject(Error $error = null)
    {
        $code = static::UNKNOWN_ERROR;
        $message = 'Unknown error';

        if ($error) {
            $const = static::class.'::'.$error->Code;
            $message = $error->Message;
            defined($const) && $code = constant($const);
        }

        return new static($message, $code);
    }
}
