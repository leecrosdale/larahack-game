<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lockouts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('computer_id')->unsigned();
            $table->integer('device_id')->unsigned();
            $table->string('device_type', 80);
            $table->tinyInteger('attempts')->unsigned()->default(1);
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
        Schema::dropIfExists('lockouts');
    }
}
