<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name');
            $table->unsignedBigInteger('seller_id');
            $table->string('shop_description')->nullable();
            $table->string('shop_logo')->nullable();
            $table->string('shop_banner')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('trade_license_no')->nullable();
            $table->string('shop_photo')->nullable();
            $table->string('seller_nid_frontend')->nullable();
            $table->string('seller_nid_backend')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            // $table->boolean('social')->default('0');
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('rate')->default(5);
            $table->integer('rated')->default(1);
            $table->tinyInteger('vacation')->default(0)->comment('0=Off, 1=On');
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('seller_id')->references('id')->on('sellers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
