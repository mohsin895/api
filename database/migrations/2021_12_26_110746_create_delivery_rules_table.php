<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('details',1000)->nullable();
            $table->tinyInteger('discount_in_per')->default(0)->comment('0=no,1=yes');
            $table->decimal('discount',10,2)->default(0.00);
            $table->tinyInteger('is_price_wise')->default(0);
            $table->decimal('price_required',10,2)->default(0.00);
            $table->tinyInteger('is_city_wise')->default(0);
            $table->string('offeredAreas',4000)->nullable();
            $table->dateTime('start_at')->nullable()->comment('Will start from the date');
            $table->dateTime('end_at')->nullable()->comment('Will close from the date');
            $table->tinyInteger('rules_for')->default(0)->comment('0=Frontend User,1=Backend User,2=For Both User');
            $table->tinyInteger('status')->default(1)->comment('0=Deleted,1=Active,2=Inactive');
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
        Schema::dropIfExists('delivery_rules');
    }
}
