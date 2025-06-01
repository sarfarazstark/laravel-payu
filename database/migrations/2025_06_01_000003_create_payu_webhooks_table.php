<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayuWebhooksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('payu_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('webhook_id')->unique();
            $table->string('txnid')->nullable();
            $table->string('payuid')->nullable();
            $table->string('event_type'); // payment_success, payment_failed, refund_success, etc.
            $table->string('status'); // received, processed, failed, ignored
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('payu_webhooks');
    }
}
