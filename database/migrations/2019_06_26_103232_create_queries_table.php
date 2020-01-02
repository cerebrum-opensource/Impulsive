<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->nullable();
            $table->text('email')->nullable();
            $table->text('subject')->nullable();
            $table->text('message')->nullable();
            $table->text('username')->nullable();
            $table->text('is_checked')->nullable();
            $table->tinyInteger('type')->default('1')->comment('1 =>contact_us, 2=>other');
            $table->tinyInteger('status')->default('1')->comment('0 =>receive, 1 => seen, 2=>response');
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
        Schema::dropIfExists('queries');
    }
}
