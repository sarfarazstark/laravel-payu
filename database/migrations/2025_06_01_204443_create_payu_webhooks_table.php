<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payu_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('webhook_id')->unique();
            $table->string('txnid')->nullable();
            $table->string('payuid')->nullable();
            $table->enum('event_type', [
                'payment_success',
                'payment_failed',
                'payment_pending',
                'refund_success',
                'refund_failed',
                'refund_pending',
                'settlement',
                'chargeback',
                'dispute'
            ]);
            $table->enum('status', ['received', 'processed', 'failed', 'ignored'])->default('received');
            $table->json('payload');
            $table->json('headers')->nullable();
            $table->string('hash')->nullable();
            $table->boolean('verified')->default(false);
            $table->text('processing_error')->nullable();
            $table->timestamp('received_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['txnid', 'event_type']);
            $table->index(['webhook_id']);
            $table->index(['status', 'verified']);
            $table->index(['event_type']);
            $table->index(['received_at']);

            // Foreign key relationship (nullable since txnid might not exist)
            $table->index(['txnid']); // Add index for foreign key reference
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('payu_webhooks');
    }
};
