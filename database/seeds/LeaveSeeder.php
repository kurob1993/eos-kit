<?php

use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
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
                
                $active_absence_quota = App\Models\AbsenceQuota::activeAbsenceQuota(
                    $employee->personnel_no
                )->first();
                $absence_type = $active_absence_quota->absenceType;
                
                $employee->leaves()->save(factory(
                    App\Models\Absence::class)->create([
                        'personnel_no' => $employee->personnel_no,
                        'absence_type_id' => $absence_type->id,
                    ])
                );
            });
    }
}
