<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Reminder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('todo_id')->unsigned();
            $table->enum('reminder_offset', ['hour', 'minute', 'day'])->default('day');
            $table->smallInteger('offset_unit')->nullable();
            $table->boolean('sent_status')->default(0);
            $table->dateTime('sent_on')->nullable();
            $table->timestamps();
            $table->foreign('todo_id')->references('id')->on('todo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminder');
    }
}
