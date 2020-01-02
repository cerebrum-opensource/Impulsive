<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinalCounterToAuctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->Integer('final_countdown')->nullable();
        });

        Schema::table('auction_queues', function (Blueprint $table) {
            $table->Integer('final_countdown')->nullable();
            $table->dateTime('final_countdown_start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            //
            $table->dropColumn('final_countdown');
        });

        Schema::table('auction_queues', function (Blueprint $table) {
            $table->dropColumn('final_countdown');
            $table->dropColumn('final_countdown_start_time');
        });
    }
}
