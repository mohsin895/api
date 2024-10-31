<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->id();
            $table->string('couponCode')->nullable();
            $table->decimal('CouponAmount',10,2)->nullable();
            $table->string('expiredDate')->nullable();
             $table->string('couponType')->nullable();
            // $table->tinyInteger('banner_type')->default(2)->comment('1=small,2=Medium,3=large');
            $table->tinyInteger('status')->default(1)->comment('0=deleted,1=Active,2=Inactive');
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
        Schema::dropIfExists('coupon_codes');
    }
}
