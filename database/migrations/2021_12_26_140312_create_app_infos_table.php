<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name',300)->nullable();
            $table->string('app_url',200)->nullable();
            $table->string('app_logo',500)->nullable();
            $table->string('phone',50)->nullable();
            $table->string('email')->nullable();
            $table->string('address',2000)->nullable();
            $table->string('facebook',2000)->nullable();
            $table->string('twitter',2000)->nullable();
            $table->string('instagram',2000)->nullable();
            $table->string('youtube',2000)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=deleted,1=Active,2=Inactive');
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
        Schema::dropIfExists('app_infos');
    }
}
