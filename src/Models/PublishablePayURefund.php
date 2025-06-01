<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayURefund extends Model {
    protected $table = 'payu_refunds';

    // PayU refund status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_PROCESSING = 'processing';

    // PayU refund type constants
    public const TYPE_REFUND = 'refund';
    public const TYPE_CANCEL = 'cancel';
    public const TYPE_CHARGEBACK = 'chargeback';

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
     * Check if the refund is successful.
     */
    public function isSuccessful(): bool {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * Check if the refund is pending.
     */
    public function isPending(): bool {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the refund is processing.
     */
    public function isProcessing(): bool {
        return $this->status === self::STATUS_PROCESSING;
    }

    /**
     * Check if the refund failed.
     */
    public function isFailed(): bool {
        return in_array($this->status, [self::STATUS_FAILED, self::STATUS_CANCELLED]);
    }

    /**
     * Get the refund type display name.
     */
    public function getTypeDisplayName(): string {
        return match ($this->type) {
            self::TYPE_REFUND => 'Refund',
            self::TYPE_CANCEL => 'Cancellation',
            self::TYPE_CHARGEBACK => 'Chargeback',
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
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_PROCESSING => 'Processing',
            default => 'Unknown',
        };
    }

    /**
     * Get the status color for display.
     */
    public function getStatusColor(): string {
        return match ($this->status) {
            self::STATUS_PENDING, self::STATUS_PROCESSING => 'yellow',
            self::STATUS_SUCCESS => 'green',
            self::STATUS_FAILED, self::STATUS_CANCELLED => 'red',
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
     * Scope to filter successful refunds.
     */
    public function scopeSuccessful($query) {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    /**
     * Scope to filter failed refunds.
     */
    public function scopeFailed($query) {
        return $query->whereIn('status', [self::STATUS_FAILED, self::STATUS_CANCELLED]);
    }

    /**
     * Scope to filter pending refunds.
     */
    public function scopePending($query) {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to filter processing refunds.
     */
    public function scopeProcessing($query) {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    /**
     * Scope to filter by refund type.
     */
    public function scopeByType($query, string $type) {
        return $query->where('type', $type);
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

    /**
     * Scope to filter by refund ID.
     */
    public function scopeByRefundId($query, string $refundId) {
        return $query->where('refund_id', $refundId);
    }
}
