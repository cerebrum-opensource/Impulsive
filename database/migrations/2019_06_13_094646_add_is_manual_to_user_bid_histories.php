<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class AddIsManualToUserBidHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bid_histories', function (Blueprint $table) {
            $table->float('bid_price', 8, 2)->nullable();
            $table->tinyInteger('is_manual')->default(1)->comment('1: Yes for Manual, 0: For Automate'); 
            $table->date('bid_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bid_histories', function (Blueprint $table) {
            $table->dropColumn('bid_price');
            $table->dropColumn('is_manual');
            $table->date('bid_date');
        });
    }
}
