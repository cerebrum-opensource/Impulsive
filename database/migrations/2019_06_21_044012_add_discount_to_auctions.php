<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountToAuctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            //
            $table->float('discount', 8, 2)->nullable();
        });
        Schema::table('auction_queues', function (Blueprint $table) {
            //
            $table->float('discount', 8, 2)->nullable();
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
            $table->dropColumn('discount');
        });

        Schema::table('auction_queues', function (Blueprint $table) {
            //
            $table->dropColumn('discount');
        });
    }
}
