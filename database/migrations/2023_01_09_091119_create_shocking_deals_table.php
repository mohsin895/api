<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShockingDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shocking_deals', function (Blueprint $table) {
            $table->id();
            $table->string('base_url')->nullable();
            $table->string('shockingDeal_url',1000)->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
        //    $table->integer('position')->default(1);
            // $table->tinyInteger('banner_type')->default(2)->comment('1=small,2=Medium,3=large');
            $table->tinyInteger('status')->default(1)->comment('0=deleted,1=Active,2=Inactive');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shocking_deals');
    }
}
