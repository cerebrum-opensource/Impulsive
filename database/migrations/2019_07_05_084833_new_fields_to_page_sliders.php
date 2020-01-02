<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewFieldsToPageSliders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_sliders', function (Blueprint $table) {
            //
            $table->text('button_link')->nullable();
            $table->text('button_text')->nullable();
            $table->text('other_text')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_sliders', function (Blueprint $table) {
            //
            $table->dropColumn('button_link');
            $table->dropColumn('button_text');
            $table->dropColumn('other_text');
        });
    }
}
