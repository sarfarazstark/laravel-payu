<?php

namespace SarfarazStark\LaravelPayU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayUTransaction extends Model {
    protected $table = 'payu_transactions';

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
        return $this->status === 'success';
    }

    /**
     * Check if transaction is failed.
     */
    public function isFailed(): bool {
        return $this->status === 'failed';
    }

    /**
     * Check if transaction is pending.
     */
    public function isPending(): bool {
        return $this->status === 'pending';
    }

    /**
     * Get total refund amount for this transaction.
     */
    public function getTotalRefundedAttribute(): float {
        return $this->refunds()
            ->where('status', 'success')
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
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed transactions.
     */
    public function scopeFailed($query) {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for pending transactions.
     */
    public function scopePending($query) {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for transactions by payment mode.
     */
    public function scopeByPaymentMode($query, $mode) {
        return $query->where('payment_mode', $mode);
    }
}
