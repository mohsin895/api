<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('details',1000)->nullable();
            $table->tinyInteger('isproduct_wise')->default(0)->comment('0=No,1=Yes');
            $table->tinyInteger('isprice_wise')->default(0)->comment('0=No,1=Yes');
            $table->tinyInteger('isproduct_required')->default(0)->comment('0=No,1=Yes');
            $table->tinyInteger('isproduct_discount')->default(0)->comment('0=No,1=Yes');
            $table->tinyInteger('isinvoice_discount')->default(0)->comment('0=No,1=Yes');
            $table->tinyInteger('isfree_product')->default(0)->comment('0=No,1=Yes');
            $table->tinyInteger('rules_for')->default(0)->comment('0=Frontend User,1=Backend User,2=For Both User');
            // $table->tinyInteger('applyOnOther')->default(1)->comment('If 1 apply on other cart rules.');
            $table->decimal('total_discount',10,2)->nullable()->comment('If Product Not Require');
            $table->decimal('price_required',10,2)->nullable()->comment('If Rules in Total Price');
            $table->dateTime('start_at')->nullable()->comment('Will start from the date');
            $table->dateTime('end_at')->nullable()->comment('Will close from the date');
            $table->tinyInteger('is_restricted')->default(0);
            $table->tinyInteger('status')->default(1)->comment('0=Deleted,1=Active,2=Inactive');
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
        Schema::dropIfExists('cart_rules');
    }
}
