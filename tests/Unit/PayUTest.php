<?php

namespace PayU\LaravelPayU\Tests\Unit;

use PayU\LaravelPayU\PayU;
use PayU\LaravelPayU\PayUServiceProvider;
use PayU\LaravelPayU\Facades\PayU as PayUFacade;
use Orchestra\Testbench\TestCase;

class PayUTest extends TestCase {
    protected function getPackageProviders($app) {
        return [
            PayUServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app) {
        return [
            'PayU' => PayUFacade::class,
        ];
    }

    protected function getEnvironmentSetUp($app) {
        // Set up test configuration
        $app['config']->set('payu.key', 'test_key');
        $app['config']->set('payu.salt', 'test_salt');
        $app['config']->set('payu.env_prod', false);
        $app['config']->set('payu.success_url', 'http://localhost/success');
        $app['config']->set('payu.failure_url', 'http://localhost/failure');
        $app['config']->set('payu.urls', [
            'sandbox' => [
                'payment' => 'https://sandboxsecure.payu.in/_payment',
                'api' => 'https://sandboxsecure.payu.in/merchant/postservice?form=2'
            ],
            'production' => [
                'payment' => 'https://secure.payu.in/_payment',
                'api' => 'https://info.payu.in/merchant/postservice?form=2'
            ]
        ]);
    }

    public function testPayUInstantiation() {
        $payU = app('payu');

        $this->assertInstanceOf(PayU::class, $payU);
        $this->assertEquals('test_key', $payU->key);
        $this->assertEquals('test_salt', $payU->salt);
        $this->assertFalse($payU->env_prod);
    }

    public function testPayUFacade() {
        $this->assertInstanceOf(PayU::class, PayUFacade::getFacadeRoot());
    }

    public function testGetHashKey() {
        $payU = app('payu');

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

        $hash = $payU->getHashKey($params);
        $this->assertIsString($hash);
        $this->assertEquals(128, strlen($hash)); // SHA512 produces 128 character hex string
    }

    public function testGetPaymentFormData() {
        $payU = app('payu');

        $params = [
            'txnid' => 'TEST123',
            'amount' => 100,
            'productinfo' => 'Test Product',
            'firstname' => 'John',
            'email' => 'john@example.com'
        ];

        $formData = $payU->getPaymentFormData($params);

        $this->assertIsArray($formData);
        $this->assertArrayHasKey('url', $formData);
        $this->assertArrayHasKey('params', $formData);
        $this->assertStringContainsString('payu.in', $formData['url']);
        $this->assertArrayHasKey('hash', $formData['params']);
        $this->assertArrayHasKey('key', $formData['params']);
        $this->assertEquals('test_key', $formData['params']['key']);
    }

    public function testGeneratePaymentUrl() {
        $payU = app('payu');

        $params = [
            'txnid' => 'TEST123',
            'amount' => 100,
            'productinfo' => 'Test Product',
            'firstname' => 'John',
            'email' => 'john@example.com'
        ];

        $url = $payU->generatePaymentUrl($params);

        $this->assertIsString($url);
        $this->assertStringContainsString('payu.in', $url);
        $this->assertStringContainsString('hash=', $url);
        $this->assertStringContainsString('key=test_key', $url);
    }

    public function testUrlConfiguration() {
        $payU = app('payu');

        // Should use sandbox URLs by default (env_prod = false)
        $this->assertStringContainsString('sandboxsecure.payu.in', $payU->url);
        $this->assertStringContainsString('sandboxsecure.payu.in', $payU->api_url);
    }

    public function testShowPaymentForm() {
        $payU = app('payu');

        $params = [
            'txnid' => 'TEST123',
            'amount' => 100,
            'productinfo' => 'Test Product',
            'firstname' => 'John',
            'email' => 'john@example.com'
        ];

        $form = $payU->showPaymentForm($params);

        $this->assertIsString($form);
        $this->assertStringContainsString('<form', $form);
        $this->assertStringContainsString('payu.in', $form);
        $this->assertStringContainsString('payuForm', $form);
    }

    public function testVerifyHash() {
        $payU = app('payu');
        $params = [
            'key' => 'test_key',
            'txnid' => 'TEST123',
            'amount' => 100,
            'productinfo' => 'Test Product',
            'firstname' => 'John',
            'email' => 'john@example.com',
            'udf5' => '',
            'status' => 'success',
            'hash' => $payU->getHashKey([
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
            ])
        ];
        $this->assertTrue($payU->verifyHash($params));
    }

    public function testVerifyPayment() {
        $payU = app('payu');
        $params = ['txnid' => 'TEST123'];
        $result = $payU->verifyPayment($params);
        $this->assertNotNull($result);
    }

    public function testGetTransactionByTxnId() {
        $payU = app('payu');
        $result = $payU->getTransactionByTxnId('TEST123');
        $this->assertNotNull($result);
    }

    public function testGetTransactionByPayuId() {
        $payU = app('payu');
        $result = $payU->getTransactionByPayuId('123456789');
        $this->assertNotNull($result);
    }

    public function testGetTransaction() {
        $payU = app('payu');
        $params = ['type' => 'date', 'from' => '2022-01-01', 'to' => '2022-01-02'];
        $result = $payU->getTransaction($params);
        $this->assertNotNull($result);
    }

    public function testGetCardBin() {
        $payU = app('payu');
        $params = ['cardnum' => '512345'];
        $result = $payU->getCardBin($params);
        $this->assertNotNull($result);
    }

    public function testGetBinDetails() {
        $payU = app('payu');
        $params = [
            'type' => '1',
            'card_info' => '512345',
            'index' => '0',
            'offset' => '100',
            'zero_redirection_si_check' => '1'
        ];
        $result = $payU->getBinDetails($params);
        $this->assertNotNull($result);
    }

    public function testCancelRefundTransaction() {
        $payU = app('payu');
        $params = [
            'payuid' => '123456789',
            'txnid' => 'TEST123',
            'amount' => 100
        ];
        $result = $payU->cancelRefundTransaction($params);
        $this->assertNotNull($result);
    }

    public function testCheckRefundStatus() {
        $payU = app('payu');
        $params = ['request_id' => 'REQ123'];
        $result = $payU->checkRefundStatus($params);
        $this->assertNotNull($result);
    }

    public function testCheckRefundStatusByPayuId() {
        $payU = app('payu');
        $params = ['payuid' => '123456789'];
        $result = $payU->checkRefundStatusByPayuId($params);
        $this->assertNotNull($result);
    }

    public function testCheckAllRefundOfTransactionId() {
        $payU = app('payu');
        $params = ['txnid' => 'TEST123'];
        $result = $payU->checkAllRefundOfTransactionId($params);
        $this->assertNotNull($result);
    }

    public function testGetNetbankingStatus() {
        $payU = app('payu');
        $params = ['netbanking_code' => 'AXIB'];
        $result = $payU->getNetbankingStatus($params);
        $this->assertNotNull($result);
    }

    public function testGetIssuingBankStatus() {
        $payU = app('payu');
        $params = ['cardnum' => '512345'];
        $result = $payU->getIssuingBankStatus($params);
        $this->assertNotNull($result);
    }

    public function testValidateUpi() {
        $payU = app('payu');
        $params = ['vpa' => '9999999999@upi', 'auto_pay_vpa' => ''];
        $result = $payU->validateUpi($params);
        $this->assertNotNull($result);
    }

    public function testCheckEmiEligibleBins() {
        $payU = app('payu');
        $params = ['payuid' => '123456789', 'txnid' => 'TEST123', 'amount' => 100];
        $result = $payU->checkEmiEligibleBins($params);
        $this->assertNotNull($result);
    }

    public function testCreatePaymentInvoice() {
        $payU = app('payu');
        $details = [
            'amount' => 100,
            'txnid' => 'TEST123',
            'productinfo' => 'Test Product',
            'firstname' => 'John',
            'email' => 'john@example.com',
            'phone' => '9999999999',
            'address1' => 'Test Address',
            'city' => 'Test City',
            'state' => 'Test State',
            'country' => 'India',
            'zipcode' => '110001',
            'validation_period' => '6',
            'send_email_now' => '1',
            'send_sms' => '1'
        ];
        $params = ['details' => json_encode($details)];
        $result = $payU->createPaymentInvoice($params);
        $this->assertNotNull($result);
    }

    public function testExpirePaymentInvoice() {
        $payU = app('payu');
        $params = ['txnid' => 'TEST123'];
        $result = $payU->expirePaymentInvoice($params);
        $this->assertNotNull($result);
    }

    public function testCheckEligibleEMIBins() {
        $payU = app('payu');
        $params = ['bin' => '512345', 'card_num' => '512345', 'bank_name' => 'AXIS'];
        $result = $payU->checkEligibleEMIBins($params);
        $this->assertNotNull($result);
    }

    public function testGetEmiAmount() {
        $payU = app('payu');
        $params = ['amount' => 20000];
        $result = $payU->getEmiAmount($params);
        $this->assertNotNull($result);
    }

    public function testGetSettlementDetails() {
        $payU = app('payu');
        $params = ['data' => '2020-10-26'];
        $result = $payU->getSettlementDetails($params);
        $this->assertNotNull($result);
    }

    public function testGetCheckoutDetails() {
        $payU = app('payu');
        $params = ['data' => ''];
        $result = $payU->getCheckoutDetails($params);
        $this->assertNotNull($result);
    }
}
