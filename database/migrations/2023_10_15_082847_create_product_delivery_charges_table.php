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
        Schema::create('product_delivery_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cat_id')->nullable();
            $table->unsignedBigInteger('subCat_id')->nullable();
            $table->unsignedBigInteger('sub_sub_cat_id')->nullable();
            $table->decimal('charge_dhaka',10,2)->default(0.00);
            $table->decimal('charge_outside',10,2)->default(0.00);
            $table->tinyInteger('status')->default(1)->comment('0=Deleted,1=Active,2=Inactive');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_delivery_charges');
    }
};
