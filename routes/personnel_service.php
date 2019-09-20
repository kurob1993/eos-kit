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

    //Route untuk delegation    
    Route::get('transition/StructJab', 'TransitionController@StructJab')
        ->name('transition.StructJab');

    Route::get('transition/employee', 'TransitionController@employee')
        ->name('transition.employee');
    
    Route::resource('transition', 'TransitionController');
    
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

    //route untuk update cv
    Route::get('internal-activity/export', 'AllInternalActivityController@export')
        ->name('personnel_service.internal-activity.export');
        
    Route::resource('internal-activity', 'AllInternalActivityController',[
        'parameters'=> [
            'internal-activity' => 'id'
        ],
        'only' => ['index', 'update'],
        'names' => [
            'index' => 'personnel_service.internal-activity.index',
            'update' => 'personnel_service.internal-activity.update',
        ]
    ]);