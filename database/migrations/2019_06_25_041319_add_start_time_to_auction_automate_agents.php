<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartTimeToAuctionAutomateAgents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_automate_agents', function (Blueprint $table) {
            //
            $table->varchar('start_time', 255)->nullable();
            $table->tinyInteger('type')->default('1')->comment('1 =>IMMEDIATLY,2 => FOUR_HOURS,3=> FIVE_MINUTES,4=> FINAL_COUNTDOWN');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_automate_agents', function (Blueprint $table) {
            //
            $table->dropColumn('start_time');
            $table->dropColumn('type');
        });
    }
}
