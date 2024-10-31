<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryOfferedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_offereds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_rule_id')->nullable();
            $table->unsignedBigInteger('thana_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('delivery_rule_id')->references('id')->on('delivery_rules');
            $table->foreign('thana_id')->references('id')->on('thanas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_offereds');
    }
}
