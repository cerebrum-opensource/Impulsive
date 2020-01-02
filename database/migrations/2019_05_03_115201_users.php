<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('users')){
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('salutation')->nullable();
                $table->string('username')->unique()->nullable();
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->enum('status',['0','1','2','3']);
              //  $table->enum('user_type',['0','1' ,'2','3','4']);
                $table->enum('is_logged_in',['0','1' ]);
                $table->string('street')->nullable();
                $table->string('postal_code')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->nullable();
                $table->text('additional_address')->nullable();
                $table->text('remember_token')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
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
