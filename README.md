# Laravel PayU Package

A comprehensive Laravel package for integrating PayU payment gateway with complete database transaction tracking, webhook handling, and advanced payment management features.

## ✨ Features

- 🚀 **Complete PayU API Integration** - All PayU APIs wrapped in a Laravel-friendly interface
- 💾 **Database Transaction Tracking** - Automatic logging of payments, refunds, and webhooks
- 🔐 **Enhanced Data Integrity** - Database enums for status fields and categorized data
- 🎯 **Eloquent Models** - Rich models with relationships and helper methods
- 🧪 **Comprehensive Testing** - 28+ unit tests covering all functionality
- 📝 **Webhook Management** - Automatic webhook processing and verification
- 🛡️ **Security First** - Hash verification and secure configuration management
- 📊 **Analytics Ready** - Query scopes and aggregation methods for reporting

## 📦 Installation

### 1. Install via Composer

```bash
composer require sarfarazstark/laravel-payu
```

### 2. Publish Configuration, Migrations & Models

```bash
# Publish configuration file
php artisan vendor:publish --tag=payu-config

# Publish migration files
php artisan vendor:publish --tag=payu-migrations

# Publish Eloquent models (optional)
php artisan vendor:publish --tag=payu-models

# Run migrations to create database tables
php artisan migrate
```

> **💡 Note**: Publishing models is optional. You can work with the PayU transactions, refunds, and webhooks using the package's built-in models (`SarfarazStark\LaravelPayU\Models\*`). However, if you want to customize the models or add additional relationships in your application, publish them to your `app/Models` directory.

### 3. Working with Published Models

Once you publish the models with `php artisan vendor:publish --tag=payu-models`, you'll have three Eloquent models in your `app/Models` directory:

#### PayUTransaction Model

- **Location**: `app/Models/PayUTransaction.php`
- **Features**: Complete transaction tracking with relationships and helper methods
- **Constants**: Status and payment mode constants for easy reference

#### PayURefund Model

- **Location**: `app/Models/PayURefund.php`
- **Features**: Refund management with status tracking and relationships
- **Constants**: Refund status and type constants

#### PayUWebhook Model

- **Location**: `app/Models/PayUWebhook.php`
- **Features**: Webhook processing and verification with event tracking
- **Constants**: Event type and status constants

Example usage with published models:

```php
use App\Models\PayUTransaction;
use App\Models\PayURefund;
use App\Models\PayUWebhook;

// Using published models works exactly the same as package models
$transaction = PayUTransaction::where('txnid', 'TXN123')->first();
$refunds = PayURefund::successful()->get();
$webhooks = PayUWebhook::verified()->recent()->get();
```

### 3. Environment Configuration

Add your PayU credentials to `.env`:

```env
PAYU_KEY=your_payu_merchant_key
PAYU_SALT=your_payu_salt_key
PAYU_ENV_PROD=false
PAYU_SUCCESS_URL=https://your-site.com/payu/success
PAYU_FAILURE_URL=https://your-site.com/payu/failure
```

> **⚠️ Important**: The `PAYU_SUCCESS_URL` and `PAYU_FAILURE_URL` are required. If not set in your .env file, you must pass `surl` and `furl` parameters when calling payment methods, otherwise an `InvalidArgumentException` will be thrown.

## 🗄️ Database Schema

The package creates three optimized database tables:

### PayU Transactions (`payu_transactions`)

- **Status Enum**: `pending`, `success`, `failure`, `cancelled`, `failed`
- **Payment Mode Enum**: `CC`, `DC`, `NB`, `UPI`, `EMI`, `WALLET`, `CASH`
- Complete transaction details with customer information
- Indexed for performance on status, email, and payment dates

### PayU Refunds (`payu_refunds`)

- **Status Enum**: `pending`, `success`, `failed`, `cancelled`, `processing`
- **Type Enum**: `refund`, `cancel`, `chargeback`
- Linked to transactions with refund tracking

### PayU Webhooks (`payu_webhooks`)

- **Event Type Enum**: 9 different payment/refund event types
- **Status Enum**: `received`, `processed`, `failed`, `ignored`
- Automatic webhook verification and processing logs

## 🚀 Quick Start Guide

### Basic Payment Integration

```php
<?php

use SarfarazStark\LaravelPayU\Facades\PayU;
use SarfarazStark\LaravelPayU\Models\PayUTransaction;

class PaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {
        // Generate unique transaction ID
        $txnid = 'TXN' . time() . rand(1000, 9999);

        // Prepare payment parameters
        $params = [
            'txnid' => $txnid,
            'amount' => $request->amount,
            'productinfo' => $request->product_name,
            'firstname' => $request->first_name,
            'lastname' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address1' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => 'India',
            'zipcode' => $request->zipcode,
            'udf1' => $request->user_id, // Custom field for user tracking
            // Optional: Override default URLs from config
            // 'surl' => route('payment.success'),
            // 'furl' => route('payment.failure'),
        ];

        // Save transaction to database
        PayUTransaction::create([
            'txnid' => $txnid,
            'amount' => $request->amount,
            'productinfo' => $request->product_name,
            'firstname' => $request->first_name,
            'lastname' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => PayUTransaction::STATUS_PENDING,
            'payment_initiated_at' => now(),
        ]);

        // Generate payment form
        $paymentForm = PayU::showPaymentForm($params);

        return view('payment.form', compact('paymentForm'));
    }
}
```

### Payment Response Handling

```php
public function paymentSuccess(Request $request)
{
    // Verify hash for security
    if (!PayU::verifyHash($request->all())) {
        return redirect()->route('payment.failure')
            ->with('error', 'Payment verification failed');
    }

    // Update transaction in database
    $transaction = PayUTransaction::where('txnid', $request->txnid)->first();

    if ($transaction) {
        $transaction->update([
            'status' => PayUTransaction::STATUS_SUCCESS,
            'mihpayid' => $request->mihpayid,
            'payment_mode' => $request->mode,
            'bankcode' => $request->bankcode,
            'bank_ref_num' => $request->bank_ref_num,
            'payment_date' => now(),
            'response_data' => $request->all(),
        ]);

        return view('payment.success', compact('transaction'));
    }

    return redirect()->route('payment.failure');
}

public function paymentFailure(Request $request)
{
    $transaction = PayUTransaction::where('txnid', $request->txnid)->first();

    if ($transaction) {
        $transaction->update([
            'status' => PayUTransaction::STATUS_FAILED,
            'error' => $request->error,
            'error_message' => $request->error_Message,
            'response_data' => $request->all(),
        ]);
    }

    return view('payment.failure', compact('transaction'));
}
```

## 🔧 Advanced Usage

### Working with Eloquent Models

#### Transaction Management

```php
use SarfarazStark\LaravelPayU\Models\PayUTransaction;

// Get all successful transactions for a user
$userTransactions = PayUTransaction::successful()
    ->where('email', 'user@example.com')
    ->with('refunds', 'webhooks')
    ->orderBy('created_at', 'desc')
    ->get();

// Check transaction status with helper methods
$transaction = PayUTransaction::find(1);

if ($transaction->isSuccessful()) {
    echo "Payment completed successfully";
} elseif ($transaction->isPending()) {
    echo "Payment is still processing";
} elseif ($transaction->isFailed()) {
    echo "Payment failed: " . $transaction->error_message;
}

// Get transactions by payment mode
$creditCardTransactions = PayUTransaction::where('payment_mode', PayUTransaction::MODE_CC)->get();
$upiTransactions = PayUTransaction::where('payment_mode', PayUTransaction::MODE_UPI)->get();

// Analytics and reporting
$monthlyRevenue = PayUTransaction::successful()
    ->whereMonth('created_at', now()->month)
    ->sum('amount');

$paymentModeStats = PayUTransaction::successful()
    ->selectRaw('payment_mode, COUNT(*) as count, SUM(amount) as total')
    ->groupBy('payment_mode')
    ->get();
```

#### Refund Management

```php
use SarfarazStark\LaravelPayU\Models\PayURefund;

// Check if transaction can be refunded
$transaction = PayUTransaction::find(1);

if ($transaction->canBeRefunded()) {
    $maxRefundable = $transaction->getRemainingRefundableAmount();

    // Create refund record
    $refund = PayURefund::create([
        'refund_id' => PayURefund::generateRefundId(),
        'txnid' => $transaction->txnid,
        'amount' => 50.00,
        'status' => PayURefund::STATUS_PENDING,
        'type' => PayURefund::TYPE_REFUND,
        'reason' => 'Customer requested refund',
        'refund_requested_at' => now(),
    ]);

    // Process refund via PayU API
    $refundResponse = PayU::cancelRefundTransaction([
        'txnid' => $transaction->txnid,
        'refund_amount' => 50.00,
        'reason' => 'Customer request'
    ]);

    // Update refund status based on API response
    if ($refundResponse['status'] === 'success') {
        $refund->markAsSuccessful($refundResponse['refund_id']);
    } else {
        $refund->markAsFailed($refundResponse['error']);
    }
}

// Get all refunds for a transaction
$refunds = $transaction->refunds()->successful()->get();
$totalRefunded = $transaction->getTotalRefundedAttribute();
```

#### Webhook Handling

```php
use SarfarazStark\LaravelPayU\Models\PayUWebhook;

public function handleWebhook(Request $request)
{
    // Create webhook record
    $webhook = PayUWebhook::create([
        'webhook_id' => PayUWebhook::generateWebhookId(),
        'txnid' => $request->txnid,
        'event_type' => $request->event_type,
        'status' => PayUWebhook::STATUS_RECEIVED,
        'payload' => $request->all(),
        'headers' => $request->headers->all(),
        'received_at' => now(),
    ]);

    try {
        // Verify webhook authenticity
        if ($this->verifyWebhookSignature($request)) {
            $webhook->update(['verified' => true]);

            // Process webhook based on event type
            switch ($webhook->event_type) {
                case PayUWebhook::EVENT_PAYMENT_SUCCESS:
                    $this->handlePaymentSuccess($webhook);
                    break;
                case PayUWebhook::EVENT_REFUND_SUCCESS:
                    $this->handleRefundSuccess($webhook);
                    break;
                // Handle other event types...
            }

            $webhook->markAsProcessed();
        } else {
            $webhook->markAsIgnored('Invalid signature');
        }
    } catch (Exception $e) {
        $webhook->markAsFailed($e->getMessage());
    }

    return response()->json(['status' => 'ok']);
}

// Helper methods for webhook event handling
private function handlePaymentSuccess(PayUWebhook $webhook)
{
    $transaction = $webhook->transaction;

    if ($transaction && $transaction->status === PayUTransaction::STATUS_PENDING) {
        $transaction->update([
            'status' => PayUTransaction::STATUS_SUCCESS,
            'payment_date' => now(),
        ]);

        // Trigger success events, send emails, etc.
        event(new PaymentSuccessEvent($transaction));
    }
}
```

### Using Available Enum Constants

```php
// Transaction Status Constants
PayUTransaction::STATUS_PENDING     // 'pending'
PayUTransaction::STATUS_SUCCESS     // 'success'
PayUTransaction::STATUS_FAILURE     // 'failure'
PayUTransaction::STATUS_CANCELLED   // 'cancelled'
PayUTransaction::STATUS_FAILED      // 'failed'

// Payment Mode Constants
PayUTransaction::MODE_CC           // 'CC' (Credit Card)
PayUTransaction::MODE_DC           // 'DC' (Debit Card)
PayUTransaction::MODE_NB           // 'NB' (Net Banking)
PayUTransaction::MODE_UPI          // 'UPI'
PayUTransaction::MODE_EMI          // 'EMI'
PayUTransaction::MODE_WALLET       // 'WALLET'
PayUTransaction::MODE_CASH         // 'CASH'

// Refund Status Constants
PayURefund::STATUS_PENDING         // 'pending'
PayURefund::STATUS_SUCCESS         // 'success'
PayURefund::STATUS_FAILED          // 'failed'
PayURefund::STATUS_CANCELLED       // 'cancelled'
PayURefund::STATUS_PROCESSING      // 'processing'

// Refund Type Constants
PayURefund::TYPE_REFUND           // 'refund'
PayURefund::TYPE_CANCEL           // 'cancel'
PayURefund::TYPE_CHARGEBACK       // 'chargeback'

// Get all available values
$statuses = PayUTransaction::getStatuses();        // Array of all status options
$paymentModes = PayUTransaction::getPaymentModes(); // Array of all payment modes
$refundTypes = PayURefund::getTypes();             // Array of all refund types
```

## 🔌 Complete PayU API Integration

### Payment Operations

```php
// Verify payment status
$verification = PayU::verifyPayment(['txnid' => 'TXN123456']);

// Get transaction details
$details = PayU::getTransaction(['txnid' => 'TXN123456']);

// Check payment status
$status = PayU::checkPayment(['txnid' => 'TXN123456']);

// Get checkout page details
$checkoutDetails = PayU::getCheckoutDetails(['var1' => 'value']);
```

### Refund Operations

```php
// Cancel/Refund transaction
$refund = PayU::cancelRefundTransaction([
    'txnid' => 'TXN123456',
    'refund_amount' => 100.00,
    'reason' => 'Customer request'
]);

// Check refund status
$refundStatus = PayU::checkRefundStatus(['request_id' => 'REF123']);
```

### EMI & Card Operations

```php
// Check EMI eligible bins
$emiBins = PayU::checkEmiEligibleBins(['bin' => '512345']);

// Get EMI amount details
$emiAmount = PayU::getEmiAmount([
    'amount' => 10000,
    'bank' => 'HDFC',
    'tenure' => 6
]);

// Get card bin details
$binDetails = PayU::getBinDetails(['cardnum' => '512345']);
```

### UPI & Banking Operations

```php
// Validate UPI ID
$upiValidation = PayU::validateUpi(['vpa' => 'user@paytm']);

// Check netbanking status
$netbankingStatus = PayU::getNetbankingStatus();

// Get issuing bank status
$bankStatus = PayU::getIssuingBankStatus();
```

### Invoice Operations

```php
// Create payment invoice
$invoice = PayU::createPaymentInvoice([
    'txnid' => 'INV123456',
    'amount' => 1000,
    'email' => 'customer@example.com',
    'phone' => '9999999999',
    'productinfo' => 'Service Invoice',
    'firstname' => 'John Doe'
]);

// Expire payment invoice
$expiry = PayU::expirePaymentInvoice(['txnid' => 'INV123456']);
```

## 📊 Reporting & Analytics

### Transaction Analytics

```php
// Daily transaction summary
$dailyStats = PayUTransaction::selectRaw('
    DATE(created_at) as date,
    COUNT(*) as total_transactions,
    SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as successful,
    SUM(CASE WHEN status = "success" THEN amount ELSE 0 END) as revenue,
    AVG(CASE WHEN status = "success" THEN amount ELSE NULL END) as avg_amount
')
->groupBy('date')
->orderBy('date', 'desc')
->limit(30)
->get();

// Payment mode performance
$modePerformance = PayUTransaction::selectRaw('
    payment_mode,
    COUNT(*) as transactions,
    SUM(amount) as total_amount,
    AVG(amount) as avg_amount,
    SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) / COUNT(*) * 100 as success_rate
')
->whereNotNull('payment_mode')
->groupBy('payment_mode')
->get();

// Failed transaction analysis
$failureReasons = PayUTransaction::failed()
    ->selectRaw('error, COUNT(*) as count')
    ->groupBy('error')
    ->orderBy('count', 'desc')
    ->get();
```

### Refund Analytics

```php
// Monthly refund trends
$refundTrends = PayURefund::selectRaw('
    YEAR(created_at) as year,
    MONTH(created_at) as month,
    COUNT(*) as refund_requests,
    SUM(CASE WHEN status = "success" THEN amount ELSE 0 END) as refunded_amount,
    AVG(TIMESTAMPDIFF(HOUR, refund_requested_at, refund_processed_at)) as avg_processing_hours
')
->groupBy('year', 'month')
->orderBy('year', 'desc')
->orderBy('month', 'desc')
->get();
```

## 🧪 Testing

The package includes comprehensive unit tests covering all functionality:

```bash
# Run all tests
./vendor/bin/phpunit

# Run with coverage report
./vendor/bin/phpunit --coverage-html coverage

# Run specific test methods
./vendor/bin/phpunit --filter testVerifyPayment
```

### Writing Custom Tests

```php
use SarfarazStark\LaravelPayU\Models\PayUTransaction;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function testTransactionCreation()
    {
        $transaction = PayUTransaction::create([
            'txnid' => 'TEST123',
            'amount' => 100.00,
            'status' => PayUTransaction::STATUS_PENDING,
            // ... other fields
        ]);

        $this->assertDatabaseHas('payu_transactions', [
            'txnid' => 'TEST123',
            'status' => PayUTransaction::STATUS_PENDING
        ]);
    }
}
```

## 🔒 Security Best Practices

### Hash Verification

```php
// Always verify hash in success/failure callbacks
if (!PayU::verifyHash($request->all())) {
    // Handle invalid hash - possible tampering
    return response()->json(['error' => 'Invalid hash'], 400);
}
```

### Environment Configuration

```php
// Use different credentials for staging/production
// .env.staging
PAYU_ENV_PROD=false
PAYU_KEY=test_key
PAYU_SALT=test_salt

// .env.production
PAYU_ENV_PROD=true
PAYU_KEY=live_key
PAYU_SALT=live_salt
```

### Database Security

```php
// Use fillable arrays to prevent mass assignment
// Models already include proper $fillable arrays

// Use enum constraints for data integrity
// Database migrations include enum constraints for status fields
```

## 🚀 Production Deployment

### Performance Optimization

```php
// Add database indexes for better query performance
// Indexes are already included in migrations for:
// - Transaction status and email lookups
// - Webhook event type filtering
// - Payment date range queries

// Use eager loading to prevent N+1 queries
$transactions = PayUTransaction::with(['refunds', 'webhooks'])
    ->successful()
    ->paginate(50);
```

### Monitoring & Logging

```php
// Log webhook processing for debugging
Log::info('PayU webhook received', [
    'webhook_id' => $webhook->webhook_id,
    'event_type' => $webhook->event_type,
    'txnid' => $webhook->txnid
]);

// Monitor failed transactions
$recentFailures = PayUTransaction::failed()
    ->where('created_at', '>=', now()->subHours(24))
    ->count();

if ($recentFailures > 10) {
    // Alert administrators
    Mail::to('admin@yoursite.com')->send(new HighFailureRateAlert($recentFailures));
}
```

## 🤝 Contributing

We welcome contributions! Please see our [contributing guidelines](CONTRIBUTING.md) for details.

### Development Setup

```bash
# Clone the repository
git clone https://github.com/sarfarazstark/laravel-payu.git

# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit

# Check code style
./vendor/bin/php-cs-fixer fix --dry-run
```

## 📚 API Documentation

For detailed API documentation and parameter requirements, see the `doc/` folder:

- [Payment Verification](doc/verifyPayment.md)
- [Transaction Details](doc/getTransactionDetails.md)
- [Refund Operations](doc/cancelRefundTransaction.md)
- [EMI Operations](doc/eligibleBinsForEMI.md)
- [UPI Validation](doc/validateVPA.md)
- [Invoice Management](doc/createInvoice.md)
- [And more...](doc/)

## 📄 License

This package is open-sourced software licensed under the [MIT License](LICENSE).

## 💬 Support

- 📧 Email: <support@sarfarazstark.com>
- 🐛 Issues: [GitHub Issues](https://github.com/sarfarazstark/laravel-payu/issues)
- 📖 Documentation: [GitHub Wiki](https://github.com/sarfarazstark/laravel-payu/wiki)

---

**Made with ❤️ by [Sarfaraz Stark](https://github.com/sarfarazstark)**
