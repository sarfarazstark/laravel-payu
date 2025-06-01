<?php

namespace PayU\LaravelPayU\Tests;

use Orchestra\Testbench\TestCase;
use PayU\LaravelPayU\PayUServiceProvider;
use PayU\LaravelPayU\Facades\PayU;

class PayUTest extends TestCase {
    protected function getPackageProviders($app) {
        return [PayUServiceProvider::class];
    }

    protected function getPackageAliases($app) {
        return [
            'PayU' => PayU::class,
        ];
    }

    protected function getEnvironmentSetUp($app) {
        $app['config']->set('payu.key', 'test_key');
        $app['config']->set('payu.salt', 'test_salt');
        $app['config']->set('payu.env_prod', false);
    }

    public function test_payu_facade_works() {
        $this->assertInstanceOf(\PayU\LaravelPayU\PayU::class, app('payu'));
    }

    public function test_hash_generation() {
        $payu = app('payu');
        $params = [
            'txnid' => 'TEST123',
            'amount' => 100,
            'productinfo' => 'Test Product',
            'firstname' => 'John',
            'email' => 'john@example.com',
            'udf1' => '',
            'udf2' => '',
            'udf3' => '',
            'udf4' => '',
            'udf5' => ''
        ];

        $hash = $payu->getHashKey($params);
        $this->assertNotEmpty($hash);
        $this->assertEquals(128, strlen($hash)); // SHA512 produces 128 character hash
    }
}
