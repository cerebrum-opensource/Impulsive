<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateToPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            //
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('type', ['default','promotional','other']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            //
            $table->dropColumn('type');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
    }
}
