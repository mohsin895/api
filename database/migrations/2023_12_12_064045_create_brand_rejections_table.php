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
        Schema::create('brand_rejections', function (Blueprint $table) {
            $table->id();
            $table->integer('document_id')->nullable();
            $table->integer('staff_id')->nullable();
            $table->integer('seller_id')->nullable();
            $table->string('recommendation')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_rejections');
    }
};
