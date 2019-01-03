<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Absence::class, function (Faker $faker) {
    $stage = App\Models\Stage::waitingApprovalStage();
    $interval = $faker->numberBetween(1, 6) . ' days';
    $s = new DateTime('first day of this month');
    $e = new DateTime('last day of this month');
    $start_date = $faker->dateTimeBetween($s, $e, 'Asia/Jakarta');
    $end_date = $faker->dateTimeInInterval($start_date, $interval, 'Asia/Jakarta');
    return [
        'start_date' => $start_date,
        'end_date' => $end_date,
        'stage_id' => $stage->id,
        'note' => $faker->text(100),
        'address' => $faker->text(100),
    ];
});
