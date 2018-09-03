<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceQuotaApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_quota_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attendance_quota_id');
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
        Schema::dropIfExists('attendance_quota_approval');
    }
}
