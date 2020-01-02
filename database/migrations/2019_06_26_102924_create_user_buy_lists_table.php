<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBuyListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_buy_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('auction_id')->nullable();
            $table->unsignedBigInteger('auction_winner_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('amount')->nullable();
            $table->string('shipping_price')->nullable();
            $table->string('tax')->nullable();
            $table->tinyInteger('type')->default('1')->comment('1 => win, 2=>instant purchase, 3=>product purchase');
            $table->tinyInteger('status')->default('0')->comment('0 => not active, 1 => active, 2=>failed');
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
        Schema::dropIfExists('user_buy_lists');
    }
}
