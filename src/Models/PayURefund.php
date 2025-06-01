<?php

namespace SarfarazStark\LaravelPayU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayURefund extends Model {
    protected $table = 'payu_refunds';

    protected $fillable = [
        'refund_id',
        'txnid',
        'payuid',
        'amount',
        'status',
        'type',
        'reason',
        'gateway_refund_id',
        'request_data',
        'response_data',
        'refund_requested_at',
        'refund_processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'request_data' => 'array',
        'response_data' => 'array',
        'refund_requested_at' => 'datetime',
        'refund_processed_at' => 'datetime',
    ];

    protected $dates = [
        'refund_requested_at',
        'refund_processed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the transaction that owns the refund.
     */
    public function transaction(): BelongsTo {
        return $this->belongsTo(PayUTransaction::class, 'txnid', 'txnid');
    }

    /**
     * Check if refund is successful.
     */
    public function isSuccessful(): bool {
        return $this->status === 'success';
    }

    /**
     * Check if refund is failed.
     */
    public function isFailed(): bool {
        return $this->status === 'failed';
    }

    /**
     * Check if refund is pending.
     */
    public function isPending(): bool {
        return $this->status === 'pending';
    }

    /**
     * Check if refund is cancelled.
     */
    public function isCancelled(): bool {
        return $this->status === 'cancelled';
    }

    /**
     * Scope for successful refunds.
     */
    public function scopeSuccessful($query) {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed refunds.
     */
    public function scopeFailed($query) {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for pending refunds.
     */
    public function scopePending($query) {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for refunds by type.
     */
    public function scopeByType($query, $type) {
        return $query->where('type', $type);
    }

    /**
     * Generate unique refund ID.
     */
    public static function generateRefundId(): string {
        return 'REF_' . time() . '_' . random_int(1000, 9999);
    }
}
