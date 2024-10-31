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
        Schema::table('brands', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('logo');
            $table->string('meta_key_word')->nullable()->after('logo');
            $table->text('meta_description')->nullable()->after('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_key_word');
            $table->dropColumn('meta_description');
        });
    }
};
