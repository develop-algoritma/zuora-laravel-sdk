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
        return static::convertToSoap($this->toArray(), $this->type, $this->namespace);
    }

    /**
     * Convert values and structures to nested SoapVar.
     *
     * @return \SoapVar
     */
    public static function convertToSoap($mixed, $type = null, $namespace = null)
    {
        if (is_array($mixed)) {
            $arr = [];
            foreach ($mixed as $key => $val) {
                if ($val instanceof self) {
                    $arr[$key] = $val->toSoap();
                } elseif (is_array($val)) {
                    $arr[$key] = static::convertToSoap($val, $type, $namespace);
                } else {
                    $arr[$key] = $val;
                }
            }
            $mixed = $arr;
        }

        return new \SoapVar($mixed, SOAP_ENC_OBJECT, $type, $namespace);
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

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
