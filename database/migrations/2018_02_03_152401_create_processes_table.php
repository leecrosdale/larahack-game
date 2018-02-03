<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('computer_id')->unsigned();
            $table->integer('software_id')->unsigned();
            $table->tinyInteger('complete')->unsigned();
            $table->timestamp('finish_time')->nullable(); // Whether the process ever finishes
            $table->timestamps();

            $table->foreign('computer_id')->references('id')->on('computers');
            $table->foreign('software_id')->references('id')->on('software');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processes');
    }
}
