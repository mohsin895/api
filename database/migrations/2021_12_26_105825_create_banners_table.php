<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('base_url')->default('https://sodaykori.com/');
            $table->string('banner_url',1000)->nullable();
            $table->string('slug',1000)->nullable();
            $table->unsignedBigInteger('category_id');
           
            $table->string('title')->nullable();
            $table->string('description')->nullable();
        //    $table->integer('position')->default(1);
            // $table->tinyInteger('banner_type')->default(2)->comment('1=small,2=Medium,3=large');
            $table->tinyInteger('status')->default(1)->comment('0=deleted,1=Active,2=Inactive');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
