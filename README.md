# Laravel PayU Package

A Laravel package for integrating PayU payment gateway with database transaction tracking.

## Installation

1. Install via Composer:

```bash
composer require sarfarazstark/laravel-payu
```

2. Publish the configuration file:

```bash
php artisan vendor:publish --tag=payu-config
```

3. Publish and run the migrations:

```bash
php artisan vendor:publish --tag=payu-migrations
php artisan migrate
```

4. Add your PayU credentials to `.env`:

```env
PAYU_KEY=your_payu_key
PAYU_SALT=your_payu_salt
PAYU_ENV_PROD=false
PAYU_SUCCESS_URL=http://your-site.com/payu/success
PAYU_FAILURE_URL=http://your-site.com/payu/failure
```

## Configuration

You can customize the PayU configuration in `config/payu.php` after publishing. All values can be set via `.env` for security.

## Database Tables

The package creates three database tables for transaction tracking:

- `payu_transactions` - Stores payment transaction details
- `payu_refunds` - Stores refund information
- `payu_webhooks` - Stores webhook data for payment notifications

## Usage

### Basic Payment Form

```php
use LaravelPayU\Facades\PayU;

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

### Working with Transaction Models

```php
use LaravelPayU\Models\PayUTransaction;
use LaravelPayU\Models\PayURefund;
use LaravelPayU\Models\PayUWebhook;

// Create a new transaction record
$transaction = PayUTransaction::create([
    'txnid' => 'TXN' . time(),
    'amount' => 100.00,
    'productinfo' => 'Test Product',
    'firstname' => 'John',
    'email' => 'john@example.com',
    'status' => 'pending',
    'payment_initiated_at' => now(),
]);

// Find transaction by txnid
$transaction = PayUTransaction::where('txnid', 'TXN123456')->first();

// Check transaction status
if ($transaction->isSuccessful()) {
    // Handle successful payment
}

// Get all successful transactions
$successfulTransactions = PayUTransaction::successful()->get();

// Get transaction with refunds
$transaction = PayUTransaction::with('refunds')->find(1);

// Check if transaction can be refunded
if ($transaction->canBeRefunded()) {
    $remainingAmount = $transaction->getRemainingRefundableAmount();
}

// Create a refund record
$refund = PayURefund::create([
    'refund_id' => PayURefund::generateRefundId(),
    'txnid' => $transaction->txnid,
    'amount' => 50.00,
    'status' => 'pending',
    'type' => 'refund',
    'reason' => 'Customer request',
    'refund_requested_at' => now(),
]);

// Create webhook record
$webhook = PayUWebhook::create([
    'webhook_id' => PayUWebhook::generateWebhookId(),
    'txnid' => $transaction->txnid,
    'event_type' => 'payment_success',
    'status' => 'received',
    'payload' => $webhookData,
    'received_at' => now(),
]);

// Mark webhook as processed
$webhook->markAsProcessed();
```

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
