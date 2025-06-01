<?php

namespace SarfarazStark\LaravelPayU\Models;

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
     * Check if refund is successful.
     */
    public function isSuccessful(): bool {
        return $this->status === self::STATUS_SUCCESS;
    }
    /**
     * Check if refund is failed.
     */
    public function isFailed(): bool {
        return $this->status === self::STATUS_FAILED;
    }
    /**
     * Check if refund is pending.
     */
    public function isPending(): bool {
        return $this->status === self::STATUS_PENDING;
    }
    /**
     * Check if refund is cancelled.
     */
    public function isCancelled(): bool {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Get all available refund statuses
     */
    public static function getStatuses(): array {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SUCCESS => 'Success',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_PROCESSING => 'Processing',
        ];
    }

    /**
     * Get all available refund types
     */
    public static function getTypes(): array {
        return [
            self::TYPE_REFUND => 'Refund',
            self::TYPE_CANCEL => 'Cancel',
            self::TYPE_CHARGEBACK => 'Chargeback',
        ];
    }

    /**
     * Check if refund is of type cancel
     */
    public function isCancel(): bool {
        return $this->type === self::TYPE_CANCEL;
    }

    /**
     * Check if refund is of type chargeback
     */
    public function isChargeback(): bool {
        return $this->type === self::TYPE_CHARGEBACK;
    }
    /**
     * Scope for successful refunds.
     */
    public function scopeSuccessful($query) {
        return $query->where('status', self::STATUS_SUCCESS);
    }
    /**
     * Scope for failed refunds.
     */
    public function scopeFailed($query) {
        return $query->where('status', self::STATUS_FAILED);
    }
    /**
     * Scope for pending refunds.
     */
    public function scopePending($query) {
        return $query->where('status', self::STATUS_PENDING);
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
