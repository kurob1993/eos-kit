<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// route untuk login
Route::get('login', [
    'as' => 'login', 
    'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', [
    'as' => '', 
    'uses' => 'Auth\LoginController@login']);
Route::post('logout', [
    'as' => 'logout', 
    'uses' => 'Auth\LoginController@logout']);

// route untuk default home --> dashboard
Route::get('/', 'HomeController@index')->name('dashboards.employee');

// route untuk role employee
Route::group([
    'middleware' => ['auth', 'role:employee']], function () {
        Route::get('debug', 'DebugController@debug');

        // route untuk persetujuan di dashboard
        Route::post('approve/{approval}/{id}','HomeController@approve')
            ->name('dashboards.approve');
        Route::post('reject/{approval}/{id}','HomeController@reject')
            ->name('dashboards.reject');
        Route::post('approve_all/{approval}', 'HomeController@approveAll')
            ->name('dashboards.approve_all');
        Route::post('reject_all/{approval}', 'HomeController@rejectAll')
            ->name('dashboards.reject_all');
        
        // route untuk datatables masing-masing persetujuan
        Route::get('leave_approval', 'HomeController@leaveApproval')
            ->name('dashboards.leave_approval');
        Route::get('permit_approval', 'HomeController@permitApproval')
            ->name('dashboards.permit_approval');
        Route::get('overtime_approval', 'HomeController@overtimeApproval')
            ->name('dashboards.overtime_approval');
        Route::get('time_event_approval', 'HomeController@timeEventApproval')
            ->name('dashboards.time_event_approval');
        
        // route untuk summary modal masing-masing persetujuan
        Route::get('detail/leave/{id}', 'HomeController@leaveSummary')
            ->name('dashboards.leave_summary');
        Route::get('detail/time_event/{id}', 'HomeController@timeEventSummary')
            ->name('dashboards.time_event_summary');
        Route::get('detail/overtime/{id}', 'HomeController@overtimeSummary')
            ->name('dashboards.overtime_summary');
        Route::get('detail/permit/{approval}/{id}', 'HomeController@permitSummary')
            ->name('dashboards.permit_summary');

        // route untuk leave
        Route::resource('leaves', 'LeaveController', ['except' => [ 
            'destroy', 'update', 'edit' ]]);
        
        // route untuk permit
        Route::resource('permits', 'PermitController',  ['except' => [ 
            'destroy', 'show', 'update', 'edit' ]]);
        Route::get('permits/absence/{id}', 'PermitController@showAbsence')
            ->name('permits.absence');
        Route::get('permits/attendance/{id}', 'PermitController@showAttendance')
            ->name('permits.attendance');

        // route untuk  time event
        Route::resource('time_events', 'TimeEventController',  ['except' => [ 
            'destroy', 'update', 'edit' ]]);

        // route untuk  overtime
        Route::resource('overtimes', 'OvertimeController',  ['except' => [ 
            'destroy', 'update', 'edit' ]]);
});

// route untuk role personnel_service
Route::group([
    'prefix' => 'personnel_service', 
    'middleware' => ['auth', 'role:personnel_service']], function (){
        
        // route default home role personnel service
        Route::get('', 'HomeController@personnelServiceDashboard')
            ->name('dashboards.personnel_service');
        
        // route untuk manage daftar semua cuti
        Route::resource('all_leaves', 'AllLeaveController', ['except' => [
            'destroy', 'show', 'update', 'edit', 'create' ]]);
            Route::post('integrate/{id}','AllLeaveController@integrate')
            ->name('all_leaves.integrate');
            Route::post('confirm/{id}','AllLeaveController@confirm')
            ->name('all_leaves.confirm');

        // route untuk manage daftar semua kuota cuti
        Route::resource('all_absence_quotas', 'AllAbsenceQuotaController', ['except' => [
            'destroy', 'show', 'update', 'edit', 'create' ]]);
        });

// route untuk role basis
Route::group([
    'prefix' => 'basis', 
    'middleware' => ['auth', 'role:basis']], function () {
        Route::get('', 'HomeController@basisDashboard')->name('dashboards.basis');
        Route::get('settings', 'SettingController@index')->name('settings');
        Route::post('settings', 'SettingController@store')->name('settings.store');    
});

Route::get('/soap', 'SoapController@show');