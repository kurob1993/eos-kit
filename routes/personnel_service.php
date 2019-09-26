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
        Route::post('absence/download', 'SendToSapAbsenceController@download')
        ->name('sendtosap.absence.download');

        Route::post('attendance/download', 'SendToSapAttendanceController@download')
        ->name('sendtosap.attendance.download');
        
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

    Route::group(['prefix' => 'periode'], function () {
        Route::get('', 'PreferdisPeriodeController@index')
            ->name('preferdis.periode.index');
        Route::delete('/{id}/destroy', 'PreferdisPeriodeController@destroy')
            ->name('preferdis.periode.delete');
        Route::get('/create', 'PreferdisPeriodeController@create')
            ->name('preferdis.periode.create');
        Route::post('', 'PreferdisPeriodeController@store')
            ->name('preferdis.periode.store');
        Route::get('/{id}/edit', 'PreferdisPeriodeController@edit')
            ->name('preferdis.periode.edit');
        Route::put('/{id}/update', 'PreferdisPeriodeController@update')
            ->name('preferdis.periode.update');
    });

    

    