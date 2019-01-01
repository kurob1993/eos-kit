<?php

use Faker\Generator as Faker;

$factory->define(App\Models\TimeEvent::class, function (Faker $faker) {
    return [
        'check_date' => $faker->dateTimeThisMonth('now', 'Asia/Jakarta'),
        'check_time' => $faker->time(),
        'time_event_type_id' => App\Models\TimeEventType::all()->random()->id,
        'stage_id' => App\Models\Stage::waitingApprovalStage()->id,
        'note' => $faker->text(100),
    ];
});
