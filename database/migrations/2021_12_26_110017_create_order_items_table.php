<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('seller_id')->default(1);
            $table->unsignedBigInteger('order_id')->default(0)->comment('Belongs To Order Table.');
            $table->unsignedBigInteger('stock_id')->default(0)->comment('Belongs To product Quantity  Table.');
            $table->unsignedBigInteger('product_id')->default(0)->comment('Belongs To Product  Table.');
            $table->integer('quantity')->default(0)->comment('Sell Quantity');
            $table->decimal('buy_rate',10,2)->default(0.0)->comment('Buy Price Per Quantity.');
            $table->decimal('sell_rate',10,2)->default(0.0)->comment('Sell Price Per Quantity.');
            $table->decimal('sell_price',10,2)->default(0.0)->comment('Including Discount.');
            $table->decimal('discount',10,2)->default(0.0)->comment('Sell Quantity.');
            $table->decimal('commission',10,2)->default(0.0)->comment('commission amount.');
            $table->tinyInteger('is_free')->default(0)->comment('0=No,1=Yes');
            $table->tinyInteger('status')->default()->comment('0=Deleted,1=Added,2=Updated');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('seller_id')->references('id')->on('sellers');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('stock_id')->references('id')->on('stock_infos');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
