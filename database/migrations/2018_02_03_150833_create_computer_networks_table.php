<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComputerNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computer_network', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('computer_id')->unsigned();
            $table->integer('network_id')->unsigned();
            $table->timestamps();

            $table->foreign('computer_id')->references('id')->on('computers');
            $table->foreign('network_id')->references('id')->on('networks');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('computer_network');
    }
}
