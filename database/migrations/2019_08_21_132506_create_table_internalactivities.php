<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInternalactivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internalactivities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pernr', 8);
            $table->date('begda');
            $table->date('enda');
            $table->char('corporate_function_id', 2);
            $table->string('corporate_function_text', 255);
            $table->string('description1',255);
            $table->string('description2', 255);
            $table->string('uname', 8);
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
        //
    }
}
