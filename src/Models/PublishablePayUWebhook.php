<?php

namespace App\Models;

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
     * Check if the webhook is verified.
     */
    public function isVerified(): bool {
        return $this->verified === true;
    }

    /**
     * Check if the webhook has been processed.
     */
    public function isProcessed(): bool {
        return $this->status === self::STATUS_PROCESSED;
    }

    /**
     * Check if the webhook processing failed.
     */
    public function isFailed(): bool {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if the webhook was ignored.
     */
    public function isIgnored(): bool {
        return $this->status === self::STATUS_IGNORED;
    }

    /**
     * Check if the webhook was just received.
     */
    public function isReceived(): bool {
        return $this->status === self::STATUS_RECEIVED;
    }

    /**
     * Get the event type display name.
     */
    public function getEventTypeDisplayName(): string {
        return match ($this->event_type) {
            self::EVENT_PAYMENT_SUCCESS => 'Payment Success',
            self::EVENT_PAYMENT_FAILED => 'Payment Failed',
            self::EVENT_PAYMENT_PENDING => 'Payment Pending',
            self::EVENT_REFUND_SUCCESS => 'Refund Success',
            self::EVENT_REFUND_FAILED => 'Refund Failed',
            self::EVENT_REFUND_PENDING => 'Refund Pending',
            self::EVENT_SETTLEMENT => 'Settlement',
            self::EVENT_CHARGEBACK => 'Chargeback',
            self::EVENT_DISPUTE => 'Dispute',
            default => 'Unknown Event',
        };
    }

    /**
     * Get the status display name.
     */
    public function getStatusDisplayName(): string {
        return match ($this->status) {
            self::STATUS_RECEIVED => 'Received',
            self::STATUS_PROCESSED => 'Processed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_IGNORED => 'Ignored',
            default => 'Unknown',
        };
    }

    /**
     * Get the status color for display.
     */
    public function getStatusColor(): string {
        return match ($this->status) {
            self::STATUS_RECEIVED => 'blue',
            self::STATUS_PROCESSED => 'green',
            self::STATUS_FAILED => 'red',
            self::STATUS_IGNORED => 'gray',
            default => 'gray',
        };
    }

    /**
     * Scope to filter by event type.
     */
    public function scopeByEventType($query, string $eventType) {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, string $status) {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter verified webhooks.
     */
    public function scopeVerified($query) {
        return $query->where('verified', true);
    }

    /**
     * Scope to filter unverified webhooks.
     */
    public function scopeUnverified($query) {
        return $query->where('verified', false);
    }

    /**
     * Scope to filter processed webhooks.
     */
    public function scopeProcessed($query) {
        return $query->where('status', self::STATUS_PROCESSED);
    }

    /**
     * Scope to filter failed webhooks.
     */
    public function scopeFailed($query) {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope to filter received webhooks.
     */
    public function scopeReceived($query) {
        return $query->where('status', self::STATUS_RECEIVED);
    }

    /**
     * Scope to filter ignored webhooks.
     */
    public function scopeIgnored($query) {
        return $query->where('status', self::STATUS_IGNORED);
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
     * Scope to filter by webhook ID.
     */
    public function scopeByWebhookId($query, string $webhookId) {
        return $query->where('webhook_id', $webhookId);
    }

    /**
     * Scope to filter payment related webhooks.
     */
    public function scopePaymentEvents($query) {
        return $query->whereIn('event_type', [
            self::EVENT_PAYMENT_SUCCESS,
            self::EVENT_PAYMENT_FAILED,
            self::EVENT_PAYMENT_PENDING,
        ]);
    }

    /**
     * Scope to filter refund related webhooks.
     */
    public function scopeRefundEvents($query) {
        return $query->whereIn('event_type', [
            self::EVENT_REFUND_SUCCESS,
            self::EVENT_REFUND_FAILED,
            self::EVENT_REFUND_PENDING,
        ]);
    }

    /**
     * Mark the webhook as processed.
     */
    public function markAsProcessed(): void {
        $this->update([
            'status' => self::STATUS_PROCESSED,
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark the webhook as failed.
     */
    public function markAsFailed(string $error = null): void {
        $this->update([
            'status' => self::STATUS_FAILED,
            'processing_error' => $error,
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark the webhook as ignored.
     */
    public function markAsIgnored(): void {
        $this->update([
            'status' => self::STATUS_IGNORED,
            'processed_at' => now(),
        ]);
    }
}
