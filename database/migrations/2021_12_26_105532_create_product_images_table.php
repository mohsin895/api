<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('product_id')->default(0);
            $table->unsignedBigInteger('color_id')->nullable();
            $table->string('base_url',500)->nullable();
            $table->string('product_image',1000)->nullable();
            $table->string('alt_name')->nullable();
            $table->tinyInteger('status')->comment('0=Deleted,1=Active,2=Inactive');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('color_id')->references('id')->on('colors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
