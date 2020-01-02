<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_sliders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->text('heading')->nullable();
            $table->text('heading_detail')->nullable();
            $table->text('image')->nullable();
            $table->Integer('priority')->nullable();
            $table->tinyInteger('type')->default('1')->comment('1=>home, 2=>other');
            $table->tinyInteger('status')->default('1')->comment('0 =>draft, 1 => active');
            $table->string('background_color')->nullable();
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
        Schema::dropIfExists('page_sliders');
    }
}
