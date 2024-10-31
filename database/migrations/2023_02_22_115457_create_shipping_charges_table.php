<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_charges', function (Blueprint $table) {
            $table->id();
            $table->decimal('argentInsideDhaka',10,2)->nullable();
            $table->decimal('argentOutsideDhaka',10,2)->nullable();
            $table->decimal('veryArgentInsideDhaka',10,2)->nullable();
             $table->decimal('veryArgentOutsideDhaka',10,2)->nullable();
             $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_charges');
    }
}
