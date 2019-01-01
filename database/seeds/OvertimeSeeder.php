<?php

use Illuminate\Database\Seeder;

class OvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Employee::find(10709)
            ->foremanAndOperatorSubordinates()
            ->each(function ($employee) {
                $employee->attendanceQuotas()->save(factory(
                    App\Models\AttendanceQuota::class)->create([
                        'personnel_no' => $employee->personnel_no,
                    ])
                );
            });
    }
}
