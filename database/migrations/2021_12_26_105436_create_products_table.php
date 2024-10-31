<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('sub_subcategory_id')->nullable();
            // $table->boolean('cat_position')->default(1);
            $table->string('name');
            $table->tinyInteger('added_by')->default(1)->comment('1=seller,2=staff');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->tinyInteger('refundable')->default(1)->comment('1=Yes, 0=No');
            // $table->string('weight', 100)->nullable();
            $table->mediumText('tags')->nullable();
            // $table->string('dimension')->nullable();
            $table->tinyInteger('product_type')->default(1)->comment('1=New, 2=Used');
            $table->tinyInteger('has_color')->default(0)->comment('1=Yes, 0=No');
            $table->tinyInteger('has_size')->default(0)->comment('1=Yes, 0=No');
            // $table->mediumText('attribute_option')->nullable();
            // $table->string('photos', 2000)->nullable();
            $table->string('thumbnail_img')->nullable();
            $table->string('video_link')->nullable();
            $table->longText('description')->nullable();
            // $table->boolean('color_image')->default(1)->comment('1=Yes, 2=No');
            // $table->mediumText('color')->nullable();
            // $table->boolean('qty_manage')->default(1)->comment('1=Yes, 2=No');
            $table->integer('min_qty')->default(1);
            $table->integer('max_qty')->default(1);
            // $table->boolean('price_type')->default(1)->comment('1=Simple Product, 2=Variable Product, 3=Volume tier Pricing');
         //   $table->boolean('stock_manage')->default(2)->comment('2=Yes, 1=No');
            $table->integer('quantity')->default(1);
           
            $table->boolean('has_warranty')->default(0)->comment('1=yes,0=no');

            $table->string('warranty_type')->nullable();
            $table->string('warranty_period')->nullable();
            // $table->boolean('shipping')->default(1)->comment('1=Cost, 2=Free');
            // $table->float('shipping_cost')->default(1);
            $table->mediumText('slug');
            $table->string('sku')->nullable();
             $table->tinyInteger('has_discount')->default(0);
            $table->integer('discount')->default(0);
            $table->dateTime('discount_start')->nullable();
            $table->dateTime('discount_end')->nullable();
            $table->tinyInteger('published')->default(0)->comment('1=yes,0=no');
            $table->tinyInteger('is_b_to_b')->default(0)->comment('1=yes,0=no');
            $table->tinyInteger('is_b_to_c')->default(1)->comment('1=yes,0=no');
            $table->bigInteger('total_view')->default(0);
            $table->bigInteger('rating')->default(5);
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('subcategory_id')->references('id')->on('categories');
            $table->foreign('sub_subcategory_id')->references('id')->on('categories');
            $table->foreign('seller_id')->references('id')->on('sellers');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('shop_id')->references('id')->on('shops');
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
