<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->integer('seller_id')->nullable();
            $table->integer('staff_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->tinyInteger('staff_views')->default(0)->comment('1=Not Views,0=Views');
            $table->tinyInteger('seller_views')->default(0)->comment('1=Not Views,0=Views');
            $table->tinyInteger('seller_views_all')->default(0)->comment('1=new,0=old');
            $table->tinyInteger('staff_views_all')->default(0)->comment('1=new,0=old');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
