<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreferdisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preferdis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('otype', 2);
            $table->string('seark', 20);
            $table->string('stext', 255);
            $table->date('begda');
            $table->date('enda');
            $table->char('rsign', 2);
            $table->char('relat', 4);
            $table->char('sclas',2);
            $table->string('sobid', 8);
            $table->char('status', 1);
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
        Schema::dropIfExists('preferdis');
    }
}
