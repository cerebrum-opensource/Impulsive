<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToHomePageWidgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_page_widgets', function (Blueprint $table) {
            $table->tinyInteger('type')->default(0)->comment('0=>slider, 1=>news_letter, 2=>others');
            $table->text('image')->nullable();
            $table->text('paragraph_1')->nullable();
            $table->text('paragraph_2')->nullable();
            $table->text('btn_text')->nullable();
            $table->text('place_holder')->nullable();
            $table->tinyInteger('page_type')->nullable()->comment('0=>homepage, 1=>running_auction, 2=>comming_auction, 3=>auction_overview, 4=>win_list, 5=>recall_list, 6=>wish_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_page_widgets', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('image');
            $table->dropColumn('paragraph_1');
            $table->dropColumn('paragraph_2');
            $table->dropColumn('btn_text');
            $table->dropColumn('place_holder');
            $table->dropColumn('page_type');
        });
    }
}
