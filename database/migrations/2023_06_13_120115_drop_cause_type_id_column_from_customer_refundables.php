<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCauseTypeIdColumnFromCustomerRefundables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_refundables', function (Blueprint $table) {
            $table->integer('returnCauseId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_refundables', function (Blueprint $table) {
            $table->dropColumn('returnCauseId');
        });
    }
}
