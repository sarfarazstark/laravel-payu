<?php

namespace SarfarazStark\LaravelPayU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayUWebhook extends Model {
    protected $table = 'payu_webhooks';

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
        return $this->status === 'processed';
    }

    /**
     * Check if webhook processing failed.
     */
    public function isFailed(): bool {
        return $this->status === 'failed';
    }

    /**
     * Check if webhook is received but not processed.
     */
    public function isReceived(): bool {
        return $this->status === 'received';
    }

    /**
     * Check if webhook is ignored.
     */
    public function isIgnored(): bool {
        return $this->status === 'ignored';
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
            'status' => 'processed',
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark webhook as failed.
     */
    public function markAsFailed(string $error = null): bool {
        return $this->update([
            'status' => 'failed',
            'processing_error' => $error,
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark webhook as ignored.
     */
    public function markAsIgnored(string $reason = null): bool {
        return $this->update([
            'status' => 'ignored',
            'processing_error' => $reason,
            'processed_at' => now(),
        ]);
    }

    /**
     * Scope for processed webhooks.
     */
    public function scopeProcessed($query) {
        return $query->where('status', 'processed');
    }

    /**
     * Scope for failed webhooks.
     */
    public function scopeFailed($query) {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for received webhooks.
     */
    public function scopeReceived($query) {
        return $query->where('status', 'received');
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
}
