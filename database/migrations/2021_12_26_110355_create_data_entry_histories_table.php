<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataEntryHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_entry_histories', function (Blueprint $table) {
            $table->id();
            $table->string('data_table',500)->nullable();
            $table->integer('data_id')->nullable();
            $table->tinyInteger('type')->default(1)->comment('0=deleted,1=Created,2=Updated');
            $table->tinyInteger('user_type')->default(1)->comment('1=Staff,2=Seller,3=Customer');
            $table->integer('user_id')->nullable();
            $table->string('optional',1000)->nullable();
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
        Schema::dropIfExists('data_entry_histories');
    }
}
