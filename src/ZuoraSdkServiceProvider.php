<?php

namespace Spira\ZuoraSdk;

use Psr\Log\LoggerInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class DatabaseServiceProvider.
 */
class ZuoraSdkServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerApi($this->app['config']['zuora'], $this->app[LoggerInterface::class]);
    }

    protected function registerApi($config, LoggerInterface $logger = null)
    {
        $this->app->bind(
            API::class,
            function () use ($config, $logger) {
                return new API($config, $logger);
            }
        );
    }
}
