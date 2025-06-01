<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayuRefundsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('payu_refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refund_id')->unique();
            $table->string('txnid');
            $table->string('payuid')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, success, failed, cancelled
            $table->string('type')->default('refund'); // refund, cancel
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('payu_refunds');
    }
}
