<?php

use Illuminate\Database\Seeder;

class PermitSeeder extends Seeder
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
                $absence_type = App\Models\AbsenceType::all()->random();
                $employee->attendances()->save(
                    factory(App\Models\Absence::class)->create([
                        'personnel_no' => $employee->personnel_no,
                        'absence_type_id' => $absence_type->id,
                    ])
                );

                $attendance_type = App\Models\AttendanceType::all()->random();
                $employee->absences()->save(
                    factory(App\Models\Attendance::class)->create([
                        'personnel_no' => $employee->personnel_no,
                        'attendance_type_id' => $attendance_type->id
                    ])
                );
            });
    }
}
