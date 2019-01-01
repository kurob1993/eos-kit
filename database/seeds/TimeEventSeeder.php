<?php

use Illuminate\Database\Seeder;

class TimeEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Employee::find(10112)
            ->subordinates()
            ->each(function ($employee) {
                $employee->timeEvents()->save(
                    factory(App\Models\TimeEvent::class)->create([
                        'personnel_no' => $employee->personnel_no,
                    ])
                );
            });
    }
}