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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('employeeID', 11)->unique();

			$table->string('full_name', 100);
			$table->string('email', 150)->unique();
			$table->string('password', 100);
			$table->enum('gender',['male','female']);
			$table->string('father_name', 100);
			$table->string('mobile_number', 20);
			$table->date('date_of_birth')->nullable();
			$table->integer('designations')->unsigned()->nullable();
			$table->date('joining_date')->nullable();
			$table->string('profile_image')->default('default.jpg')->nullable();
			$table->text('local_address');
			$table->text('permanent_address');
			$table->integer('annual_leave')->default(0);
			$table->enum('status',['active','inactive']);
			$table->dateTime('last_login')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->date('exit_date')->nullable();
			$table->string('reset_code')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
