<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayuTransactionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('payu_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('txnid')->unique()->index();
            $table->string('mihpayid')->nullable()->index();
            $table->decimal('amount', 10, 2);
            $table->string('productinfo');
            $table->string('firstname');
            $table->string('lastname')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->text('address1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('udf1')->nullable();
            $table->string('udf2')->nullable();
            $table->string('udf3')->nullable();
            $table->string('udf4')->nullable();
            $table->string('udf5')->nullable();
            $table->string('status')->default('pending')->index(); // pending, success, failure, cancelled
            $table->string('payment_mode')->nullable();
            $table->string('bankcode')->nullable();
            $table->string('bank_ref_num')->nullable();
            $table->string('error')->nullable();
            $table->text('error_message')->nullable();
            $table->string('name_on_card')->nullable();
            $table->string('cardnum')->nullable();
            $table->string('cardhash')->nullable();
            $table->decimal('net_amount_debit', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('additional_charges', 10, 2)->default(0);
            $table->string('hash')->nullable();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['email', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('payu_transactions');
    }
}
