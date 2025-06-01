<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payu_refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refund_id')->unique();
            $table->string('txnid');
            $table->string('payuid')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled', 'processing'])->default('pending');
            $table->enum('type', ['refund', 'cancel', 'chargeback'])->default('refund');
            $table->string('reason')->nullable();
            $table->string('gateway_refund_id')->nullable();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamp('refund_requested_at')->nullable();
            $table->timestamp('refund_processed_at')->nullable();
            $table->timestamps();

            $table->index(['txnid', 'status']);
            $table->index(['refund_id']);
            $table->index(['status']);
            $table->index(['created_at']);

            // Foreign key relationship
            $table->foreign('txnid')->references('txnid')->on('payu_transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('payu_refunds');
    }
};
