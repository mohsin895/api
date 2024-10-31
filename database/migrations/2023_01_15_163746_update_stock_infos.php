<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStockInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_infos', function(Blueprint $table){
            $table->dropForeign('stock_infos_color_id_foreign');
            $table->dropForeign('stock_infos_size_id_foreign');
            $table->dropForeign('stock_infos_seller_id_foreign');
            $table->dropForeign('stock_infos_product_id_foreign');
            $table->foreign('product_id')->references('id')->on('products')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('sellers')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')
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
