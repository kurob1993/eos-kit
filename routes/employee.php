<?php
    // route untuk halaman help
    Route::resource('help', 'HelpController');

    // route untuk default home
    Route::get('/', 'HomeController@index')->name('home');
    
    // route untuk dashboard
    Route::get('/employee', 'Dashboard\DashboardController@index')->name('dashboard.index');
    Route::get('/employee/leave', 'Dashboard\Employee\LeaveController@chart')
        ->name('dashboards.employee.leave');
    Route::get('/employee/leave/filter', 'Dashboard\Employee\LeaveController@filter')
        ->name('dashboards.employee.leave.filter');

    Route::get('/employee/overtime', 'Dashboard\Employee\OvertimeController@chart')
        ->name('dashboards.employee.overtime');
    Route::get('/employee/overtime/filter', 'Dashboard\Employee\OvertimeController@filter')
        ->name('dashboards.employee.overtime.filter');

    Route::get('/employee/permit', 'Dashboard\Employee\PermitController@chart')
        ->name('dashboards.employee.permit');
    Route::get('/employee/permit/filter', 'Dashboard\Employee\PermitController@filter')
        ->name('dashboards.employee.permit.filter');

    Route::get('/employee/time_event', 'Dashboard\Employee\TimeEventController@chart')
        ->name('dashboards.employee.time_event');
    Route::get('/employee/time_event/filter', 'Dashboard\Employee\TimeEventController@filter')
        ->name('dashboards.employee.time_event.filter');

    // route untuk approval
    Route::group(['prefix' => 'approval'], function () {
        // route untuk halaman approval
        Route::get('/', 'ApprovalController@approval')->name('dashboards.approval');
        // route untuk action persetujuan
        Route::post('approve/{approval}/{id}', 'ApprovalController@approve')
            ->name('dashboards.approve');
        Route::post('reject/{approval}/{id}', 'ApprovalController@reject')
            ->name('dashboards.reject');
        Route::post('approve_all', 'ApprovalController@approveAll')
            ->name('dashboards.approve_all');
        Route::post('reject_all', 'ApprovalController@rejectAll')
            ->name('dashboards.reject_all');
        // route untuk data persetujuan
        Route::get('leave_approval', 'ApprovalController@leaveApproval')
            ->name('dashboards.leave_approval');
        Route::get('permit_approval', 'ApprovalController@permitApproval')
            ->name('dashboards.permit_approval');
        Route::get('overtime_approval', 'ApprovalController@overtimeApproval')
            ->name('dashboards.overtime_approval');
        Route::get('ski_approval', 'ApprovalController@skiApproval')
            ->name('dashboards.ski_approval');
        Route::get('time_event_approval', 'ApprovalController@timeEventApproval')
            ->name('dashboards.time_event_approval');
    });

    // route untuk leave
    Route::resource('leaves', 'LeaveController', ['except' => [
        'destroy', 'update', 'edit']]);

    // route untuk permit
    Route::resource('permits', 'PermitController', ['except' => [
        'destroy', 'show', 'update', 'edit']]);
    Route::get('permits/absence/{id}', 'PermitController@showAbsence')
        ->name('permits.absence');
    Route::get('permits/attendance/{id}', 'PermitController@showAttendance')
        ->name('permits.attendance');

    // route untuk  time event
    Route::resource('time_events', 'TimeEventController', ['except' => [
        'destroy', 'update', 'edit']]);

    // route untuk  overtime
    Route::resource('overtimes', 'OvertimeController', ['except' => [
        'destroy', 'update', 'edit']]);

    Route::resource('ski', 'SkiController', ['except' => [ 'destroy']]);

    // route untuk curriculum vitae
    Route::resource('cvs', 'CVController', ['only' => 'index']);
    Route::get('cvs/download', 'CVController@download')->name('cvs.download');

    // route untuk wakers
    Route::resource('wakers', 'WakerController', ['only' => 'index']);

    // route activiti report
    Route::get('activity', 'ActivitiReportController@index')->name('activity.index');
    Route::get('activity/list/{personnel_no?}', 'ActivitiReportController@list')->name('activity.list');
    Route::get('activity/download/{file?}', 'ActivitiReportController@download')->name('activity.download');

    //route untuk update cv
    Route::resource('internal-activity', 'InternalActivityController',[
        'parameters'=> [
            'internal-activity' => 'id'
        ],
        'except' => ['destroy', 'update', 'edit']
    ]);
    Route::resource('external-activity', 'ExternalActivityController',[
        'parameters'=> [
            'external-activity' => 'id'
        ],
        'except' => ['destroy', 'update', 'edit']
    ]);
    Route::resource('other-activity', 'OtherActivityController',[
        'parameters'=> [
            'other-activity' => 'id'
        ],
        'except' => ['destroy', 'update', 'edit']
    ]);