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
        Schema::table('general_settings', function (Blueprint $table) {
            $table->enum('send_reg_otp_sms', ['yes', 'no'])->default('no');
            $table->enum('send_update_phone_otp_sms', ['yes', 'no'])->default('no');
            $table->enum('send_current_phone_otp_sms', ['yes', 'no'])->default('no');
            $table->enum('send_order_otp_sms', ['yes', 'no'])->default('no');
            $table->enum('send_forget_pass_otp_sms', ['yes', 'no'])->default('no');
            $table->enum('send_reg_otp_email', ['yes', 'no'])->default('no');
            $table->enum('send_update_phone_otp_email', ['yes', 'no'])->default('no');
            $table->enum('send_forget_pass_otp_email', ['yes', 'no'])->default('no');
            $table->enum('send_order_otp_email', ['yes', 'no'])->default('no');
            $table->enum('user_panel_down', ['yes', 'no'])->default('no');
            $table->enum('seller_panel_down', ['yes', 'no'])->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('send_reg_otp_sms');
            $table->dropColumn('send_update_phone_otp_sms');
            $table->dropColumn('send_current_phone_otp_sms');
            $table->dropColumn('send_order_otp_sms');
            $table->dropColumn('send_forget_pass_otp_sms');
            $table->dropColumn('send_reg_otp_email');
            $table->dropColumn('send_update_phone_otp_email');
            $table->dropColumn('send_forget_pass_otp_email');
            $table->dropColumn('send_order_otp_email');
            $table->dropColumn('user_panel_down');
             $table->dropColumn('seller_panel_down');
        });
    }
};
