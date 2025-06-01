<?php

namespace SarfarazStark\LaravelPayU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayUTransaction extends Model {
    protected $table = 'payu_transactions';

    // PayU transaction status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILURE = 'failure';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_FAILED = 'failed';

    // PayU payment mode constants
    public const MODE_CREDIT_CARD = 'CC';
    public const MODE_DEBIT_CARD = 'DC';
    public const MODE_NET_BANKING = 'NB';
    public const MODE_UPI = 'UPI';
    public const MODE_EMI = 'EMI';
    public const MODE_WALLET = 'WALLET';
    public const MODE_CASH = 'CASH';

    protected $fillable = [
        'txnid',
        'payuid',
        'amount',
        'productinfo',
        'firstname',
        'lastname',
        'email',
        'phone',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'zipcode',
        'udf1',
        'udf2',
        'udf3',
        'udf4',
        'udf5',
        'status',
        'payment_mode',
        'payment_gateway',
        'gateway_txn_id',
        'bank_ref_num',
        'hash',
        'request_data',
        'response_data',
        'payment_initiated_at',
        'payment_completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'request_data' => 'array',
        'response_data' => 'array',
        'payment_initiated_at' => 'datetime',
        'payment_completed_at' => 'datetime',
    ];

    protected $dates = [
        'payment_initiated_at',
        'payment_completed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the refunds for the transaction.
     */
    public function refunds(): HasMany {
        return $this->hasMany(PayURefund::class, 'txnid', 'txnid');
    }

    /**
     * Get the webhooks for the transaction.
     */
    public function webhooks(): HasMany {
        return $this->hasMany(PayUWebhook::class, 'txnid', 'txnid');
    }
    /**
     * Check if transaction is successful.
     */
    public function isSuccessful(): bool {
        return $this->status === self::STATUS_SUCCESS;
    }
    /**
     * Check if transaction is failed.
     */
    public function isFailed(): bool {
        return $this->status === self::STATUS_FAILED;
    }
    /**
     * Check if transaction is pending.
     */
    public function isPending(): bool {
        return $this->status === self::STATUS_PENDING;
    }
    /**
     * Get total refund amount for this transaction.
     */
    public function getTotalRefundedAttribute(): float {
        return $this->refunds()
            ->where('status', PayURefund::STATUS_SUCCESS)
            ->sum('amount');
    }

    /**
     * Check if transaction can be refunded.
     */
    public function canBeRefunded(): bool {
        return $this->isSuccessful() && $this->getTotalRefundedAttribute() < $this->amount;
    }

    /**
     * Get remaining refundable amount.
     */
    public function getRemainingRefundableAmount(): float {
        return $this->amount - $this->getTotalRefundedAttribute();
    }
    /**
     * Scope for successful transactions.
     */
    public function scopeSuccessful($query) {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    /**
     * Scope for failed transactions.
     */
    public function scopeFailed($query) {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope for pending transactions.
     */
    public function scopePending($query) {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for transactions by payment mode.
     */
    public function scopeByPaymentMode($query, $mode) {
        return $query->where('payment_mode', $mode);
    }

    /**
     * Get all available transaction statuses
     */
    public static function getStatuses(): array {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SUCCESS => 'Success',
            self::STATUS_FAILURE => 'Failure',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_FAILED => 'Failed',
        ];
    }

    /**
     * Get all available payment modes
     */
    public static function getPaymentModes(): array {
        return [
            self::MODE_CREDIT_CARD => 'Credit Card',
            self::MODE_DEBIT_CARD => 'Debit Card',
            self::MODE_NET_BANKING => 'Net Banking',
            self::MODE_UPI => 'UPI',
            self::MODE_EMI => 'EMI',
            self::MODE_WALLET => 'Wallet',
            self::MODE_CASH => 'Cash',
        ];
    }

    /**
     * Check if transaction is using credit card
     */
    public function isCreditCard(): bool {
        return $this->payment_mode === self::MODE_CREDIT_CARD;
    }

    /**
     * Check if transaction is using UPI
     */
    public function isUpi(): bool {
        return $this->payment_mode === self::MODE_UPI;
    }

    /**
     * Check if transaction is using net banking
     */
    public function isNetBanking(): bool {
        return $this->payment_mode === self::MODE_NET_BANKING;
    }
}
