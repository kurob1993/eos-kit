<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecretaryIdToAttendanceQuotas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_quotas', function (Blueprint $table) {
            if ( !Schema::hasColumn('attendance_quotas','secretary_id') ) {
                $table->unsignedInteger('secretary_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_quotas', function (Blueprint $table) {
            if ( Schema::hasColumn('attendance_quotas','secretary_id') ) {
                $table->dropColumn('secretary_id');
            }
        });
    }
}
