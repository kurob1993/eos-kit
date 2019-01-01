<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Attendance::class, function (Faker $faker) {
    $stage = App\Models\Stage::waitingApprovalStage();
    $interval = $faker->numberBetween(1, 6) . ' days';
    $start_date = $faker->dateTimeThisMonth('now', 'Asia/Jakarta');
    $end_date = $faker->dateTimeInInterval($start_date, $interval, 'Asia/Jakarta');
    return [
        'start_date' => $start_date,
        'end_date' => $end_date,
        'stage_id' => $stage->id,
        'note' => $faker->text(100),
    ];
});
