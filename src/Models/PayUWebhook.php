<?php

namespace SarfarazStark\LaravelPayU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayUWebhook extends Model {
    protected $table = 'payu_webhooks';

    // PayU webhook event type constants
    public const EVENT_PAYMENT_SUCCESS = 'payment_success';
    public const EVENT_PAYMENT_FAILED = 'payment_failed';
    public const EVENT_PAYMENT_PENDING = 'payment_pending';
    public const EVENT_REFUND_SUCCESS = 'refund_success';
    public const EVENT_REFUND_FAILED = 'refund_failed';
    public const EVENT_REFUND_PENDING = 'refund_pending';
    public const EVENT_SETTLEMENT = 'settlement';
    public const EVENT_CHARGEBACK = 'chargeback';
    public const EVENT_DISPUTE = 'dispute';

    // PayU webhook status constants
    public const STATUS_RECEIVED = 'received';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_IGNORED = 'ignored';

    protected $fillable = [
        'webhook_id',
        'txnid',
        'payuid',
        'event_type',
        'status',
        'payload',
        'headers',
        'hash',
        'verified',
        'processing_error',
        'received_at',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'verified' => 'boolean',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    protected $dates = [
        'received_at',
        'processed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the transaction that owns the webhook.
     */
    public function transaction(): BelongsTo {
        return $this->belongsTo(PayUTransaction::class, 'txnid', 'txnid');
    }
    /**
     * Check if webhook is processed.
     */
    public function isProcessed(): bool {
        return $this->status === self::STATUS_PROCESSED;
    }
    /**
     * Check if webhook processing failed.
     */
    public function isFailed(): bool {
        return $this->status === self::STATUS_FAILED;
    }
    /**
     * Check if webhook is received but not processed.
     */
    public function isReceived(): bool {
        return $this->status === self::STATUS_RECEIVED;
    }
    /**
     * Check if webhook is ignored.
     */
    public function isIgnored(): bool {
        return $this->status === self::STATUS_IGNORED;
    }

    /**
     * Check if webhook is verified.
     */
    public function isVerified(): bool {
        return $this->verified === true;
    }
    /**
     * Mark webhook as processed.
     */
    public function markAsProcessed(): bool {
        return $this->update([
            'status' => self::STATUS_PROCESSED,
            'processed_at' => now(),
        ]);
    }
    /**
     * Mark webhook as failed.
     */
    public function markAsFailed(string $error = null): bool {
        return $this->update([
            'status' => self::STATUS_FAILED,
            'processing_error' => $error,
            'processed_at' => now(),
        ]);
    }
    /**
     * Mark webhook as ignored.
     */
    public function markAsIgnored(string $reason = null): bool {
        return $this->update([
            'status' => self::STATUS_IGNORED,
            'processing_error' => $reason,
            'processed_at' => now(),
        ]);
    }
    /**
     * Scope for processed webhooks.
     */
    public function scopeProcessed($query) {
        return $query->where('status', self::STATUS_PROCESSED);
    }
    /**
     * Scope for failed webhooks.
     */
    public function scopeFailed($query) {
        return $query->where('status', self::STATUS_FAILED);
    }
    /**
     * Scope for received webhooks.
     */
    public function scopeReceived($query) {
        return $query->where('status', self::STATUS_RECEIVED);
    }

    /**
     * Scope for verified webhooks.
     */
    public function scopeVerified($query) {
        return $query->where('verified', true);
    }

    /**
     * Scope for webhooks by event type.
     */
    public function scopeByEventType($query, $eventType) {
        return $query->where('event_type', $eventType);
    }

    /**
     * Generate unique webhook ID.
     */
    public static function generateWebhookId(): string {
        return 'WH_' . time() . '_' . random_int(1000, 9999);
    }

    /**
     * Get all available webhook event types
     */
    public static function getEventTypes(): array {
        return [
            self::EVENT_PAYMENT_SUCCESS => 'Payment Success',
            self::EVENT_PAYMENT_FAILED => 'Payment Failed',
            self::EVENT_PAYMENT_PENDING => 'Payment Pending',
            self::EVENT_REFUND_SUCCESS => 'Refund Success',
            self::EVENT_REFUND_FAILED => 'Refund Failed',
            self::EVENT_REFUND_PENDING => 'Refund Pending',
            self::EVENT_SETTLEMENT => 'Settlement',
            self::EVENT_CHARGEBACK => 'Chargeback',
            self::EVENT_DISPUTE => 'Dispute',
        ];
    }

    /**
     * Get all available webhook statuses
     */
    public static function getStatuses(): array {
        return [
            self::STATUS_RECEIVED => 'Received',
            self::STATUS_PROCESSED => 'Processed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_IGNORED => 'Ignored',
        ];
    }

    /**
     * Check if webhook is for payment event
     */
    public function isPaymentEvent(): bool {
        return in_array($this->event_type, [
            self::EVENT_PAYMENT_SUCCESS,
            self::EVENT_PAYMENT_FAILED,
            self::EVENT_PAYMENT_PENDING,
        ]);
    }

    /**
     * Check if webhook is for refund event
     */
    public function isRefundEvent(): bool {
        return in_array($this->event_type, [
            self::EVENT_REFUND_SUCCESS,
            self::EVENT_REFUND_FAILED,
            self::EVENT_REFUND_PENDING,
        ]);
    }

    /**
     * Check if webhook is for settlement event
     */
    public function isSettlementEvent(): bool {
        return $this->event_type === self::EVENT_SETTLEMENT;
    }
}
