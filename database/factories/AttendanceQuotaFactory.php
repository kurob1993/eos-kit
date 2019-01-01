<?php

use Faker\Generator as Faker;

$factory->define(App\Models\AttendanceQuota::class, function (Faker $faker) {
    $stage = App\Models\Stage::waitingApprovalStage();
    $interval = DateInterval::createFromDateString( $faker->numberBetween(1, 8) . ' hours' );
    $start_date = $faker->dateTimeThisMonth('now', 'Asia/Jakarta');
    return [
        'start_date' => $start_date->format('Y-m-d'),
        'from' => $start_date->format('H:i:s'),
        'end_date' => $start_date->add($interval)->format('Y-m-d'),
        'to' => $start_date->format('H:i:s'),
        'attendance_quota_type_id' => App\Models\AttendanceQuotaType::find(1)->id,
        'overtime_reason_id' => App\Models\OvertimeReason::all()->random(),
        'stage_id' => $stage->id,
    ];
});
