<?php

namespace Spira\ZuoraSdk;

use Illuminate\Support\Fluent;

abstract class DataObject extends Fluent
{
    protected $namespace = 'http://object.api.zuora.com/';
    protected $type      = 'zObject';

    /** @return \SoapVar */
    public function toSoap()
    {
        return new \SoapVar($this->toArray(), SOAP_ENC_OBJECT, $this->type, $this->namespace);
    }
}