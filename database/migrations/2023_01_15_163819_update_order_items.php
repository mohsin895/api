<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function(Blueprint $table){
            $table->dropForeign('order_items_product_id_foreign');
            $table->dropForeign('order_items_stock_id_foreign');
            $table->dropForeign('order_items_order_id_foreign');
            $table->dropForeign('order_items_seller_id_foreign');
            $table->foreign('seller_id')->references('id')->on('sellers')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('stock_id')->references('id')->on('stock_infos')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
