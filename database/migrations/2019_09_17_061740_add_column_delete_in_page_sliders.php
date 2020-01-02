<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDeleteInPageSliders extends Migration
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
        Schema::table('page_sliders', function (Blueprint $table) {
            //
            if (Schema::hasColumn('deleted_at')) {
                 $table->dropColumn('deleted_at');
            }
            
        });
    }
}
