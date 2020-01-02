<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmationCodeToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
                $table->string('confirmation_code')->nullable();
                $table->string('phone')->nullable();
                $table->text('comment')->nullable();
                $table->string('state')->nullable();
                $table->string('company')->nullable();
                $table->date('dob')->nullable();
                $table->tinyInteger('is_complete')->default('0')->comment('0 => not complete, 1 => complete');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
