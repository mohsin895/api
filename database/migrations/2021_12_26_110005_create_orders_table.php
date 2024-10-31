<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id')->default(0)->comment('Belongs To Customer Table.');
            $table->unsignedBigInteger('address_id')->default(0)->comment('Belongs To Customer Address Table.');
            $table->unsignedBigInteger('thana_id')->default(0)->comment('Belongs To Customer Thana List Table.');
            // $table->unsignedBigInteger('shop_id')->default(0)->comment('Belongs To Shop Table.');
            $table->decimal('price',10,2)->default(0.0);
            $table->decimal('discount',10,2)->default(0.0);
            $table->decimal('invoice_discount',10,2)->default(0.0);
            $table->decimal('promo_discount',10,2)->default(0.0);
            $table->decimal('delivery_charge',10,2)->default(0.0);
            $table->decimal('commission',10,2)->default(0.0);
            $table->unsignedBigInteger('promo_id')->nullable()->comment('Use From Voucher Discount Table');
            $table->tinyInteger('is_bkash_paid')->default(0)->comment('0=No,1=Yes.');
            $table->tinyInteger('is_online_paid')->default(0)->comment('0=No,1=Yes.');
            $table->tinyInteger('is_cash_on')->default(1)->comment('0=No,1=Yes.');
            $table->tinyInteger('is_address_printed')->default(0)->comment('0=No,1=Yes.');
            $table->tinyInteger('is_printed')->default(0)->comment('0=No,1=Yes.');
            $table->tinyInteger('is_delivered')->default(0)->comment('0=No,1=Yes.');
            $table->tinyInteger('is_cancelled')->default(0)->comment('0=No,1=Yes.');
            $table->tinyInteger('is_proccessing')->default(1)->comment('0=No,1=Yes.');
            $table->tinyInteger('is_packing')->default(0)->comment('0=No,1=Yes.');
            $table->tinyInteger('is_shipping')->default(0)->comment('0=No,1=Yes.');
            $table->tinyInteger('payment_type')->default(0)->comment('0=No,1=Yes.');
            $table->tinyInteger('placed_by')->default(0)->comment('1=Customer,2=seller,3=staff');
            // $table->integer('user_id')->default(0)->comment('Staff ID/Customer ID');
            $table->integer('status')->default(1)->comment('Belongs To Order Status');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('address_id')->references('id')->on('customer_addresses');
            $table->foreign('thana_id')->references('id')->on('thanas');
            // $table->foreign('shop_id')->references('id')->on('shops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
