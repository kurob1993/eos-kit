<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeEventApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_event_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('time_event_id');
            $table->unsignedInteger('regno');
            $table->unsignedInteger('sequence');
            $table->unsignedInteger('status_id');
            $table->string('text');
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
        Schema::dropIfExists('time_event_approvals');
    }
}
