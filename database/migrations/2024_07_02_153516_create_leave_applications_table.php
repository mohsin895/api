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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();

			$table->unsignedInteger("employee_id");
			$table->string('leaveType',100)->nullable();
			$table->string('halfDayType',100)->nullable();

			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->integer('days');
			$table->date('applied_on')->nullable();
			$table->string('updated_by',100)->nullable();
			$table->text('reason');
			$table->enum('application_status',array('approved','rejected','pending'))->nullable();

			$table->index('leaveType');
			$table->index('updated_by');

			$table->index('halfDayType');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
