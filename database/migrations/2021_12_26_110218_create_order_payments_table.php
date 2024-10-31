<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->default(0);
            $table->string("transaction_id")->nullable()->comment("unique payment transaction  id");
            $table->string("payment_id")->nullable()->comment("unique payment id");
            $table->decimal("amount",10,2)->default(0.00);
            $table->tinyInteger('status')->default(1)->comment("0=Deleted,1=Success,2=Processing,3=Cancel,4=Fail");
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
}
