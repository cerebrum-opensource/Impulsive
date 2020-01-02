<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->nullable();
            $table->string('value', 255)->nullable();
            $table->string('place_holder', 255)->nullable();
            $table->text('other_value')->nullable();
            $table->enum('type',[0,1,2,3,4])->comment('0=> input, 1=> dropdown, 2=> date, 3=> checkbox, 4=> radio')->nullable();
            $table->enum('status', [0,1,2])->comment('0=>draft, 1=>active, 2=>de-active');
            $table->bigInteger('category_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes');
    }
}
