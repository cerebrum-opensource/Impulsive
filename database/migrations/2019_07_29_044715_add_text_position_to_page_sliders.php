<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTextPositionToPageSliders extends Migration
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
            $table->string('header_text_position')->nullable();
            $table->string('header_detail_text_position')->nullable();
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
            $table->dropColumn('header_text_position');
            $table->dropColumn('header_detail_text_position');
        });
    }
}
