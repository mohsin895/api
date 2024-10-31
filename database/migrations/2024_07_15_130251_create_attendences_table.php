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
        Schema::create('attendences', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("employee_id");
            $table->date('date');
            $table->enum('status',array('absent','present'));
            $table->string('leaveType',100)->nullable();
            $table->string('halfDayType',100)->nullable();

      		$table->text('reason');
            $table->enum('application_status',array('approved','rejected','pending'))->nullable();
            $table->date('applied_on')->nullable();
			$table->string('updated_by',100)->nullable();
            $table->time('office_start_time')->nullable();
            $table->time('office_end_time')->nullable();
            $table->index('leaveType');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->string('clock_in_ip_address',16)->nullable();
            $table->string('clock_out_ip_address',16)->nullable();
            $table->string('working_from',100)->default('Office')->nullable();
            $table->text('notes')->nullable();
            $table->unique(['employee_id','date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendences');
    }
};
