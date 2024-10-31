<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateVoucherDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('promo_code')->unique();
            $table->integer('canbe_used')->default(1);
            $table->integer('available')->default(1);
            $table->tinyInteger('available_for')->default(0)->comment('0=Customer,1=Staff,2=Both');
            $table->tinyInteger('isprice_required')->default(0)->comment('0=No,1=Yes');
            $table->integer('price_required')->default(0)->comment('Price Required To Get Voucher Discount');
            $table->tinyInteger('isdiscount_in_percent')->default(0)->comment('0=No,1=Yes');
            $table->integer('discount_amount')->default(0)->comment('Discount Amount');
            $table->tinyInteger('status')->default(1)->comment('0=deleted,1=Active,2=Inactive');
            $table->dateTime('start_at')->default(Carbon::today());
            $table->dateTime('end_at')->default(Carbon::today());
            $table->softDeletes();
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
        Schema::dropIfExists('voucher_discounts');
    }
}
