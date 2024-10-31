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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("employee_id");
			
			$table->string('month');
			$table->string('year');
			$table->enum('payment_mode',['cash','bank_transfer','cheque']);
			$table->string('basic');
			$table->string('overtime_hours');
			$table->string('overtime_pay');

			$table->longText('allowances')->nullable();
			$table->string('total_allowance');

			$table->longText('deductions')->nullable();
			$table->string('total_deduction');

			$table->string('additionals');
			$table->string('total_additional');
			$table->string('net_salary');
			$table->date('pay_date');
            $table->enum('status', ['paid', 'unpaid'])->default('paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
