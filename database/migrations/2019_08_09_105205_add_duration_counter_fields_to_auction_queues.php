<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDurationCounterFieldsToAuctionQueues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_queues', function (Blueprint $table) {
            //
             $table->bigInteger('duration_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_queues', function (Blueprint $table) {
            //
            $table->dropColumn('duration_count');
        });
    }
}
