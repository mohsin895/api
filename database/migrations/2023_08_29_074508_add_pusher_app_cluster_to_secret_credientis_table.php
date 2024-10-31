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
        Schema::table('secret_credientials', function (Blueprint $table) {
           $table->string('pusher_app_cluster')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('secret_credientials', function (Blueprint $table) {
            $table->dropColumn('pusher_app_cluster');
        });
    }
};
