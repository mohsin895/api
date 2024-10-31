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
        Schema::create('employee_bank_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("employee_id");

			$table->string('account_name',100)->nullable();
			$table->string('account_number',40)->nullable();
			$table->string('bank',100)->nullable();
			$table->string('bin',10)->nullable();
			$table->string('branch',100)->nullable();
			$table->string('ifsc',20)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_bank_details');
    }
};
