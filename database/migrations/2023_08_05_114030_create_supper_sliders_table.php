<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupperSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supper_sliders', function (Blueprint $table) {
            $table->id();
            $table->integer('supper_id')->nullable();
            $table->string('slider_url',1000)->nullable();
            $table->string('target_url',1000)->nullable();
            $table->tinyInteger('url_type')->default(1)->comment('1=_blank ,2=_self,3=_parent,4=_top');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            // $table->integer('position')->default(1);
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
        Schema::dropIfExists('supper_sliders');
    }
}
