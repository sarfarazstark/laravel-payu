# Model Publishing Guide

This guide explains how to use the new model publishing feature in the Laravel PayU package.

## Overview

The Laravel PayU package now supports publishing Eloquent models to your Laravel application's `app/Models` directory. This allows you to:

- Customize model behavior for your specific needs
- Add additional relationships
- Modify validation rules
- Extend functionality while maintaining all package features

## Publishing Models

### Command

```bash
php artisan vendor:publish --tag=payu-models
```

This command will publish three model files to your `app/Models` directory:

- `PayUTransaction.php` - Complete transaction model
- `PayURefund.php` - Refund management model
- `PayUWebhook.php` - Webhook processing model

### What Gets Published

Each published model includes:

✅ **All constants** - Status, payment mode, and type constants
✅ **All relationships** - Transaction ↔ Refunds ↔ Webhooks
✅ **All helper methods** - `isSuccessful()`, `canBeRefunded()`, etc.
✅ **All scopes** - `successful()`, `pending()`, `recent()`, etc.
✅ **App\Models namespace** - Ready to use in your Laravel app

## Usage Examples

### Before Publishing (Package Models)

```php
use SarfarazStark\LaravelPayU\Models\PayUTransaction;
use SarfarazStark\LaravelPayU\Models\PayURefund;

// Using package models
$transaction = PayUTransaction::where('txnid', 'TXN123')->first();
$refunds = PayURefund::successful()->get();
```

### After Publishing (Your App Models)

```php
use App\Models\PayUTransaction;
use App\Models\PayURefund;

// Using published models - identical functionality
$transaction = PayUTransaction::where('txnid', 'TXN123')->first();
$refunds = PayURefund::successful()->get();
```

## Customization Examples

### Adding Custom Relationships

```php
// In app/Models/PayUTransaction.php
class PayUTransaction extends Model
{
    // ...existing code...

    /**
     * Get the user who made this transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Get the order associated with this transaction
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'transaction_id', 'txnid');
    }
}
```

### Adding Custom Methods

```php
// In app/Models/PayUTransaction.php
class PayUTransaction extends Model
{
    // ...existing code...

    /**
     * Send payment confirmation email
     */
    public function sendConfirmationEmail()
    {
        if ($this->isSuccessful()) {
            Mail::to($this->email)->send(new PaymentConfirmationMail($this));
        }
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }
}
```

### Adding Custom Scopes

```php
// In app/Models/PayUTransaction.php
class PayUTransaction extends Model
{
    // ...existing code...

    /**
     * Scope for high-value transactions
     */
    public function scopeHighValue($query, $amount = 10000)
    {
        return $query->where('amount', '>', $amount);
    }

    /**
     * Scope for transactions from specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('udf1', $userId);
    }
}
```

## Migration Strategy

### Option 1: Start Fresh (New Projects)

1. Install the package
2. Publish models immediately
3. Use published models from the beginning

### Option 2: Gradual Migration (Existing Projects)

1. Keep using package models initially
2. Publish models when you need customization
3. Update imports from package to app models
4. Test thoroughly before deploying

### Option 3: Mixed Usage

- Use package models for basic operations
- Use published models only where customization is needed
- Both approaches work simultaneously

## Best Practices

### 1. Keep Package Features Intact

```php
// ✅ Good - Extend functionality
class PayUTransaction extends Model
{
    // ...existing package code...

    // Add your custom methods here
    public function yourCustomMethod() { }
}

// ❌ Avoid - Don't remove package features
class PayUTransaction extends Model
{
    // Don't remove existing constants, relationships, or methods
}
```

### 2. Use Traits for Shared Logic

```php
// Create app/Traits/PaymentNotifiable.php
trait PaymentNotifiable
{
    public function sendPaymentNotification()
    {
        // Shared notification logic
    }
}

// Use in published models
class PayUTransaction extends Model
{
    use PaymentNotifiable;
    // ...existing code...
}
```

### 3. Maintain Database Compatibility

```php
// ✅ Good - Add new fillable fields if you add columns
protected $fillable = [
    // ...existing package fields...
    'custom_field',
    'another_field',
];

// ❌ Avoid - Don't remove existing fillable fields
protected $fillable = [
    'only_my_fields', // This breaks package functionality
];
```

## Testing Your Customizations

```php
// tests/Unit/PayUTransactionTest.php
class PayUTransactionTest extends TestCase
{
    public function test_custom_user_relationship()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $transaction = PayUTransaction::factory()->create(['email' => 'test@example.com']);

        $this->assertEquals($user->id, $transaction->user->id);
    }

    public function test_package_methods_still_work()
    {
        $transaction = PayUTransaction::factory()->create(['status' => 'success']);

        $this->assertTrue($transaction->isSuccessful());
    }
}
```

## Troubleshooting

### Models Not Found After Publishing

```bash
# Clear application cache
php artisan cache:clear
php artisan config:clear

# Dump autoload
composer dump-autoload
```

### Namespace Conflicts

```php
// If you have conflicts, use aliases
use App\Models\PayUTransaction as AppPayUTransaction;
use SarfarazStark\LaravelPayU\Models\PayUTransaction as PackagePayUTransaction;
```

### Missing Methods/Constants

Check that you haven't accidentally removed package code when customizing. Compare with the original package models.

## Support

If you encounter issues with model publishing:

1. Check this guide first
2. Verify your models have the correct namespace: `App\Models`
3. Ensure all package constants and methods are preserved
4. Test with both package and published models to isolate issues

For additional help, please open an issue on the [GitHub repository](https://github.com/sarfarazstark/laravel-payu/issues).
