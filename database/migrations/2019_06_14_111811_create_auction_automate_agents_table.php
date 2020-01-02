<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionAutomateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_automate_agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('auction_queue_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->Integer('remaining_bid')->nullable();
            $table->Integer('total_bids')->nullable();
            $table->tinyInteger('status')->default('1')->comment('0 => not active, 1 => active,2=>running');
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
        Schema::dropIfExists('auction_automate_agents');
    }
}
