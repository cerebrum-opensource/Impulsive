<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_product_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('article_number')->nullable();
            $table->text('article_name')->nullable();
            $table->text('article_description')->nullable();
            $table->text('article_price')->nullable();
            $table->text('article_manufacturer')->nullable();
            $table->text('article_productgroupkey')->nullable();
            $table->text('article_productgroup')->nullable();
            $table->text('article_ean')->nullable();
            $table->text('article_hbnr')->nullable();
            $table->text('article_shippingcosttext')->nullable();
            $table->text('article_amount')->nullable();
            $table->text('article_paymentinadvance')->nullable();
            $table->text('article_maxdeliveryamount')->nullable();
            $table->text('article_energyefficiencyclass')->nullable();
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
        Schema::dropIfExists('table_product_data');
    }
}
