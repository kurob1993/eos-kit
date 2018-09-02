<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('personnel_no');
            $table->date('check_date');
            $table->time('check_time');
            $table->unsignedInteger('time_event_type_id');
            $table->string('note');
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
        Schema::dropIfExists('time_events');
    }
}
