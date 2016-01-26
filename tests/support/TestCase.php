<?php

use Spira\ZuoraSdk\API;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /** @return API|\Mockery\MockInterface */
    protected function makeApi($credentialsRequired = false, $logger = null)
    {
        $config = require __DIR__.'/../../storage/config.php';

        if ($credentialsRequired && (empty($config['username']) || empty($config['password']))) {
            $this->markTestSkipped('If you want to run integration tests provide sandbox credentials in config.php file');
        }

        return Mockery::mock(API::class, [$config, $logger])->makePartial();
    }

    /**
     * @param int $objectsCount - number of expected objects count
     * @return \Mockery\Matcher\Closure
     */
    protected function makeObjectsExpectation($objectsCount = 1)
    {
        return Mockery::on(
            function ($var) use ($objectsCount) {
                return is_array($var)
                && array_key_exists('zObjects', $var)
                && count($var['zObjects']) == $objectsCount
                && current($var['zObjects']) instanceof SoapVar;
            }
        );
    }

    /**
     * @param int $headersCount - number of expected headers count
     * @return \Mockery\Matcher\Closure
     */
    protected function makeLoginHeadersExpectation($headersCount = 1)
    {
        return Mockery::on(
            function ($var) use ($headersCount) {
                return is_array($var) && count($var) == $headersCount && current($var) instanceof SoapHeader;
            }
        );
    }
}
