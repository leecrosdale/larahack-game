<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerminalLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminal_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('computer_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('message_key', 50);
            $table->text('message_value');
            $table->timestamps();
            $table->foreign('computer_id')->references('id')->on('computers');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terminal_lines');
    }
}
