<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLossAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_loss_auctions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('auction_queue_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->Integer('bid_count')->nullable();
            $table->float('bid_price', 8, 2)->nullable();
            $table->string('link')->nullable();
            $table->dateTime('link_expire_time')->nullable();
            $table->tinyInteger('status')->default('0')->comment('0 => pending,1 => active');
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
        Schema::dropIfExists('user_loss_auctions');
    }
}
