<?php

use Faker\Generator as Faker;

$factory->define(App\Models\TimeEvent::class, function (Faker $faker) {
    $s = new DateTime('first day of this month');
    $e = new DateTime('last day of this month');
    return [
        'check_date' => $faker->dateTimeBetween($s, $e, 'Asia/Jakarta'),
        'check_time' => $faker->time(),
        'time_event_type_id' => App\Models\TimeEventType::all()->random()->id,
        'stage_id' => App\Models\Stage::waitingApprovalStage()->id,
        'note' => $faker->text(100),
    ];
});
