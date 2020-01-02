<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('low_price')->nullable();
            $table->string('high_price')->nullable();
            $table->tinyInteger('status')->default('1')->comment('0 =>draft, 1 => active');
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
        Schema::dropIfExists('discount_categories');
    }
}
