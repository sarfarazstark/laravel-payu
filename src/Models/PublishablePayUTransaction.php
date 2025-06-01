<?php

namespace App\Models;

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
        'card_name',
        'card_type',
        'card_no',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8',
        'field9',
        'field10',
        'hash',
        'merchant_response',
        'gateway_response',
        'error_message',
        'success_url',
        'failure_url',
        'notify_url',
        'custom_data',
        'webhook_verified',
        'webhook_received_at',
        'payment_completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'merchant_response' => 'array',
        'gateway_response' => 'array',
        'custom_data' => 'array',
        'webhook_verified' => 'boolean',
        'webhook_received_at' => 'datetime',
        'payment_completed_at' => 'datetime',
    ];

    protected $dates = [
        'webhook_received_at',
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
     * Check if the transaction is successful.
     */
    public function isSuccessful(): bool {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * Check if the transaction is pending.
     */
    public function isPending(): bool {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the transaction failed.
     */
    public function isFailed(): bool {
        return in_array($this->status, [self::STATUS_FAILURE, self::STATUS_FAILED, self::STATUS_CANCELLED]);
    }

    /**
     * Get the total refunded amount for this transaction.
     */
    public function getTotalRefundedAmount(): float {
        return $this->refunds()
            ->where('status', PayURefund::STATUS_SUCCESS)
            ->sum('amount');
    }

    /**
     * Get the remaining refundable amount.
     */
    public function getRemainingRefundableAmount(): float {
        if (!$this->isSuccessful()) {
            return 0;
        }

        return $this->amount - $this->getTotalRefundedAmount();
    }

    /**
     * Check if the transaction can be refunded.
     */
    public function canBeRefunded(): bool {
        return $this->isSuccessful() && $this->getRemainingRefundableAmount() > 0;
    }

    /**
     * Get the payment method display name.
     */
    public function getPaymentMethodDisplayName(): string {
        return match ($this->payment_mode) {
            self::MODE_CREDIT_CARD => 'Credit Card',
            self::MODE_DEBIT_CARD => 'Debit Card',
            self::MODE_NET_BANKING => 'Net Banking',
            self::MODE_UPI => 'UPI',
            self::MODE_EMI => 'EMI',
            self::MODE_WALLET => 'Wallet',
            self::MODE_CASH => 'Cash',
            default => 'Unknown',
        };
    }

    /**
     * Get the status display name.
     */
    public function getStatusDisplayName(): string {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SUCCESS => 'Success',
            self::STATUS_FAILURE => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_FAILED => 'Failed',
            default => 'Unknown',
        };
    }

    /**
     * Get the status color for display.
     */
    public function getStatusColor(): string {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_SUCCESS => 'green',
            self::STATUS_FAILURE, self::STATUS_FAILED, self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, string $status) {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter successful transactions.
     */
    public function scopeSuccessful($query) {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    /**
     * Scope to filter failed transactions.
     */
    public function scopeFailed($query) {
        return $query->whereIn('status', [self::STATUS_FAILURE, self::STATUS_FAILED, self::STATUS_CANCELLED]);
    }

    /**
     * Scope to filter pending transactions.
     */
    public function scopePending($query) {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to filter by payment mode.
     */
    public function scopeByPaymentMode($query, string $mode) {
        return $query->where('payment_mode', $mode);
    }

    /**
     * Scope to filter by transaction ID.
     */
    public function scopeByTxnId($query, string $txnid) {
        return $query->where('txnid', $txnid);
    }

    /**
     * Scope to filter by PayU ID.
     */
    public function scopeByPayUId($query, string $payuid) {
        return $query->where('payuid', $payuid);
    }
}
