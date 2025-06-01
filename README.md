# Laravel PayU Package

A Laravel package for integrating PayU payment gateway.

## Installation

1. Install via Composer:

```bash
composer require sarfarazstark/laravel-payu
```

2. Publish the configuration file:

```bash
php artisan vendor:publish --tag=payu-config
```

3. Add your PayU credentials to `.env`:

```env
PAYU_KEY=your_payu_key
PAYU_SALT=your_payu_salt
PAYU_ENV_PROD=false
PAYU_SUCCESS_URL=http://your-site.com/payu/success
PAYU_FAILURE_URL=http://your-site.com/payu/failure
```

## Configuration

You can customize the PayU configuration in `config/payu.php` after publishing. All values can be set via `.env` for security.

## Usage

### Basic Payment Form

```php
use PayU\LaravelPayU\Facades\PayU;

$params = [
    'txnid' => 'TXN' . time(),
    'amount' => 100,
    'productinfo' => 'Test Product',
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'phone' => '9999999999',
    'address1' => 'Test Address',
    'city' => 'Test City',
    'state' => 'Test State',
    'country' => 'India',
    'zipcode' => '110001',
];

echo PayU::showPaymentForm($params);
```

### Generate Payment URL

```php
$url = PayU::generatePaymentUrl($params);
```

### Get Payment Form Data (for custom forms)

```php
$formData = PayU::getPaymentFormData($params);
// $formData['url'] and $formData['params']
```

### Verify Payment

```php
$params = ['txnid' => 'your_transaction_id'];
$transaction = PayU::verifyPayment($params);
```

### Verify Hash

```php
$isValid = PayU::verifyHash($request->all());
```

### Other API Methods

- Transaction details: `PayU::getTransaction($params)`
- Refund operations: `PayU::cancelRefundTransaction($params)`, `PayU::checkRefundStatus($params)`
- EMI eligibility: `PayU::checkEmiEligibleBins($params)`, `PayU::getEmiAmount($params)`
- UPI validation: `PayU::validateUpi($params)`
- Invoice: `PayU::createPaymentInvoice($params)`, `PayU::expirePaymentInvoice($params)`
- Card/bin/netbanking: `PayU::getCardBin($params)`, `PayU::getBinDetails($params)`, `PayU::getNetbankingStatus($params)`, `PayU::getIssuingBankStatus($params)`

See the `doc/` folder for detailed API documentation and parameter requirements for each method.

## Testing

Run the test suite to ensure everything works:

```bash
./vendor/bin/phpunit
```

## Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

## License

MIT License. See [LICENSE](LICENSE) file for details.
