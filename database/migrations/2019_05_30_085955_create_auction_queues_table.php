<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_queues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('product_id');
            $table->dateTime('end_time')->nullable();
            $table->Integer('bid_count')->nullable();
            $table->float('bid_price', 8, 2)->nullable();
            $table->tinyInteger('status')->default('1')->comment('0 => not active, 1 => active');
            $table->tinyInteger('auction_type')->default('0')->comment('0 => live, 1 => queue, 2=>planned');
            $table->time('duration')->nullable();
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
        Schema::dropIfExists('auction_queues');
    }
}
