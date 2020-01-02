<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsFreebidBonusbidUserBids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('user_bids', function($table) {
            $table->integer('free_bid')->default(0);
            $table->integer('bonus_bid')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('user_bids', function($table) {
            $table->dropColumn('free_bid');
            $table->dropColumn('bonus_bid');
        });
    }
}
