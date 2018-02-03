<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('tile_id')->unsigned();
            $table->string('name', 70);
            $table->integer('capacity')->unsigned()->default(100);

            $table->tinyInteger('location_type')->unsigned();
            $table->timestamps();

            $table->foreign('tile_id')->references('id')->on('tiles');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
