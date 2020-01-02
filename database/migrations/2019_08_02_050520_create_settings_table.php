<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('free_bid_amount')->nullable();
            $table->string('invite_bid_amount')->nullable();
            $table->string('maximum_live_auction')->nullable();
            $table->string('per_bid_price_raise')->nullable();
            $table->string('per_bid_count_raise')->nullable();
            $table->string('per_bid_count_descrease')->nullable();
            $table->string('discount_per_bid_price')->nullable();
            $table->string('bonus_bid_day_count')->nullable();
            $table->string('instant_purchase_expire_day')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
