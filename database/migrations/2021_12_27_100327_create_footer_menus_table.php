<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFooterMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footer_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title',1000)->nullable();
            $table->tinyInteger('look_type')->default(1)->comment('1=Mega,2=Sub,3=Normal');
            // $table->tinyInteger('position')->default(1)->comment('1=Header,2=Footer');
            $table->integer('serial')->default(1);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('meta_key',500)->nullable();
            $table->string('meta_title',500)->nullable();
            $table->string('meta_details',500)->nullable();
            $table->string('slug',500)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=Deleted,1=Active,2=Inactive');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('footer_menus');
    }
}
