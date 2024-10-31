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
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('suspended')->default(0)->comment('1=Yes, 0=No')->after('status');
            $table->tinyInteger('rejacted')->default(0)->comment('1=Yes, 0=No')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('suspended');
            $table->dropColumn('rejected');
        });
    }
};
