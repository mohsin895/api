<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductTablev4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('shocking_deal_id')->nullable();
            $table->unsignedBigInteger('right_slider_id')->nullable();
            $table->foreign('shocking_deal_id')->references('id')->on('shocking_deals')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('right_slider_id')->references('id')->on('right_banners')
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
