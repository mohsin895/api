<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_id')->nullable();
            $table->integer('staff_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('total_qty')->nullable();
            $table->integer('total_order_qty')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('debit')->nullable();
            $table->string('credit')->nullable();
            $table->string('credit_name')->nullable();
            $table->string('debit_name')->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
