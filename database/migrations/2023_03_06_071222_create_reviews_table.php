<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->integer('stock_info_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->string('rating')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('show_customer_name')->default(2)->comment('1=Yes,2=No');
            $table->tinyInteger('status')->default(1)->comment('1=Active,2=Inactive');
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
        Schema::dropIfExists('reviews');
    }
}
