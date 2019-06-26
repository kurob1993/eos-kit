<?php
    // route default home role personnel service
    Route::get('', 'Dashboard\DashboardController@personnelServiceDashboard')
        ->name('dashboards.personnel_service');

    // route untuk integration & confirmation SAP
    Route::post('integrate/{approval}/{id}', 'PersonnelServiceController@integrate')
        ->name('personnel_service.integrate');
    Route::post('confirm/{approval}/{id}', 'PersonnelServiceController@confirm')
        ->name('personnel_service.confirm');
    Route::post('delete/{approval}/{id}', 'PersonnelServiceController@delete')
        ->name('personnel_service.delete');

    // route untuk manage daftar semua cuti
    Route::resource('all_leaves', 'AllLeaveController', ['only' => ['index']]);

    // route untuk manage daftar semua kuota cuti
    Route::resource('all_absence_quotas', 'AllAbsenceQuotaController', ['only' => ['index']]);

    // route untuk manage daftar semua izin jenis absence
    Route::get('all_permits/absence', 'AllPermitController@absence')
        ->name('all_permits.absence');

    // route untuk manage daftar semua izin jenis attendance
    Route::get('all_permits/attendance', 'AllPermitController@attendance')
        ->name('all_permits.attendance');

    // route untuk manage daftar semua tidak slash
    Route::resource('all_time_events', 'AllTimeEventController', ['only' => ['index']]);

    // route untuk manage daftar semua lembur
    Route::resource('all_overtimes', 'AllOvertimeController', ['only' => ['index']]);

    //route untuk manage data yang dikirim ke sap
    Route::prefix('sendtosap')->group(function () {
        Route::resource('absence', 'SendToSapAbsenceController', [
            'parameters'=> [
                'absence' => 'id'
            ],
            'names' => [
                'index' => 'sendtosap.absence.index',
                'show' => 'sendtosap.absence.show',
                'create' => 'sendtosap.absence.create',
                'update' => 'sendtosap.absence.update',
                'destroy' => 'sendtosap.absence.destroy',
                'edit' => 'sendtosap.absence.edit'
            ]
        ]);
        Route::resource('attendance', 'SendToSapAttendanceController', [
            'parameters'=> [
                'attendance' => 'id'
            ],
            'names' => [
                'index' => 'sendtosap.attendance.index',
                'show' => 'sendtosap.attendance.show',
                'create' => 'sendtosap.attendance.create',
                'update' => 'sendtosap.attendance.update',
                'destroy' => 'sendtosap.attendance.destroy',
                'edit' => 'sendtosap.attendance.edit'
            ]
        ]);
    });
    