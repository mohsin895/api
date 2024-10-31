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
        Schema::create('premium_packges', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable()->unique();
            $table->string('price', 100)->nullable();
            $table->string('logo', 200)->nullable();
         //   $table->integer('serial')->nullable();
          //  $table->integer('top')->nullable();
            $table->string('slug')->unique();
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premium_packges');
    }
};
