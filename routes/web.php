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
        Route::post('approve/{approval}/{id}','HomeController@approve')->name('dashboards.approve');
        Route::post('reject/{approval}/{id}','HomeController@reject')->name('dashboards.reject');
        Route::get('absence_approval', 'HomeController@absenceApproval')->name('dashboards.absence_approval');
        Route::get('attendance_approval', 'HomeController@attendanceApproval')->name('dashboards.attendance_approval');

        // route untuk leave
        Route::resource('leaves', 'LeaveController', ['except' => [ 
            'destroy', 'show', 'update', 'edit' ]]);
        
        // route untuk permit
        Route::resource('permits', 'PermitController',  ['except' => [ 
            'destroy', 'show', 'update', 'edit' ]]);
            
        // route untuk  time event
        Route::resource('time_events', 'TimeEventController',  ['except' => [ 
            'destroy', 'show', 'update', 'edit' ]]);

        // route untuk  overtime
        Route::resource('overtimes', 'OvertimeController',  ['except' => [ 
            'destroy', 'show', 'update', 'edit' ]]);
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