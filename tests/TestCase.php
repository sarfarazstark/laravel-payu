<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use SarfarazStark\LaravelPayU\PayUServiceProvider;

abstract class TestCase extends BaseTestCase {
    protected function getPackageProviders($app) {
        return [
            PayUServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app) {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup PayU config
        $app['config']->set('payu.key', 'test_key');
        $app['config']->set('payu.salt', 'test_salt');
        $app['config']->set('payu.env_prod', false);
        $app['config']->set('payu.success_url', 'http://localhost/success');
        $app['config']->set('payu.failure_url', 'http://localhost/failure');
    }
}
