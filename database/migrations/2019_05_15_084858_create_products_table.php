<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_title', 255)->nullable();
            $table->string('products_short_title', 255)->nullable();
            $table->text('product_short_desc')->nullable();
            $table->text('product_long_desc')->nullable();
            $table->float('product_price')->nullable();
            $table->float('shipping_price')->nullable();
            $table->float('tax')->nullable();
            $table->text('product_features')->nullable();
            $table->string('product_brand', 255)->nullable();
            $table->bigInteger('category_id');
            $table->bigInteger('user_id');
            $table->bigInteger('seller_id');
            $table->enum('status', [0,1,2])->comment('0=>draft, 1=>active, 2=>de-active');
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
        Schema::dropIfExists('products');
    }
}
