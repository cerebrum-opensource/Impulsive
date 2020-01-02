<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->text('page_slug')->nullable();
          /*  $table->text('header1')->nullable();
            $table->text('header2')->nullable();
            $table->text('header3')->nullable();*/
            $table->text('textarea1')->nullable();
         /*   $table->text('textarea2')->nullable();
            $table->text('textarea3')->nullable();
            $table->text('image1')->nullable();
            $table->text('image2')->nullable();
            $table->text('image3')->nullable();
            $table->text('step1')->nullable();
            $table->text('step2')->nullable();
            $table->text('step3')->nullable();
            $table->text('video_link')->nullable();*/
            $table->Integer('priority')->nullable();
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
        Schema::dropIfExists('static_pages');
    }
}
