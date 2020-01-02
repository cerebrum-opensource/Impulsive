<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->tinyInteger('status')->default('0')->comment('0 => pending, 1 => complete,2 => failed, 3 => refund');
            $table->string('transaction_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('bid_count')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('refund_date')->nullable();
            $table->string('payer_id')->nullable();
            $table->tinyInteger('payment_method')->default('0')->comment('0 => paypal, 1 => klarna, 2=> sofort');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
