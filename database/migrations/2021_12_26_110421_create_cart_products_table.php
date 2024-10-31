<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rule_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('discount')->default(1);
            $table->tinyInteger('apply_index')->default(1);
            $table->tinyInteger('type')->default(1)->comment('1=required product,2=discount product,3=free product');
            $table->tinyInteger('status')->default(1)->comment('0=Deleted,1=Active,2=Inactive,');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('rule_id')->references('id')->on('cart_rules');
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
        Schema::dropIfExists('cart_products');
    }
}
