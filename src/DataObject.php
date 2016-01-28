<?php

namespace Spira\ZuoraSdk;

use Illuminate\Support\Fluent;

abstract class DataObject extends Fluent
{
    protected $namespace = 'http://object.api.zuora.com/';
    protected $type = 'zObject';

    /** @return \SoapVar */
    public function toSoap()
    {
        return new \SoapVar($this->toArray(), SOAP_ENC_OBJECT, $this->type, $this->namespace);
    }

    /**
     * List of columns for default select query.
     *
     * @return array
     */
    public static function getDefaultColumns()
    {
        return ['Id'];
    }

    /**
     * Optional columns that can be omitted in response even if requested.
     *
     * @return array
     */
    public static function getOptionalColumns()
    {
        return [];
    }

    /**
     * List of required columns based on default columns minus optional.
     *
     * @return array
     */
    public static function getRequiredColumns()
    {
        return array_diff(static::getDefaultColumns(), static::getOptionalColumns());
    }
}
