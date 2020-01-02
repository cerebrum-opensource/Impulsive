<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->tinyInteger('live_auction')->default('0')->comment('0 => no, 1 => yes');
            $table->tinyInteger('auction_type')->default('0')->comment('0 => live, 1 => queue, 2=>planned');
            $table->time('duration')->nullable();
            $table->Integer('priority')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->tinyInteger('status')->default('1')->comment('0 => not active,1 => active,2=> running,3=> ended');
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
        Schema::dropIfExists('auctions');
    }
}
