<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('label_staff')->nullable();
            $table->string('label_seller')->nullable();
            $table->string('label_customer')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('font_color')->nullable();
            $table->tinyInteger('is_paid')->default(0)->comment('1=Yes,0=No');
            $table->tinyInteger('is_cancel')->default(0)->comment('1=Yes,0=No');
            $table->tinyInteger('is_delivered')->default(0)->comment('1=Yes,0=No');
            $table->tinyInteger('is_pending')->default(0)->comment('1=Yes,0=No');
            $table->tinyInteger('is_shipping')->default(0)->comment('1=Yes,0=No');
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
        Schema::dropIfExists('order_statuses');
    }
}
