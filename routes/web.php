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
Route::get('debug', 'DebugController@debug');

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

// route for programatically employee login to system
Route::get('a/{personnel_no}/{email}', 'Auth\LoginController@programaticallyEmployeeLogin')
    ->name('login.a');

// route for programatically secretary login to system
Route::get('b/{email}', 'Auth\LoginController@programaticallySecretaryLogin')
    ->name('login.b');

// route untuk user yang belum di set Role-nya
Route::get('noRole', 'HomeController@noRole')
    ->name('noRole');

// route untuk role employee
Route::group([
    'middleware' => ['auth', 'role:employee']], function () {

    // route untuk halaman help
    Route::resource('help', 'HelpController');

    // route untuk default home --> dashboard
    Route::get('/', 'HomeController@index')->name('dashboards.employee');

    // route untuk persetujuan di dashboard
    Route::post('approve/{approval}/{id}', 'HomeController@approve')
        ->name('dashboards.approve');
    Route::post('reject/{approval}/{id}', 'HomeController@reject')
        ->name('dashboards.reject');
    Route::post('approve_all', 'HomeController@approveAll')
        ->name('dashboards.approve_all');
    Route::post('reject_all', 'HomeController@rejectAll')
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

    // route untuk curriculum vitae
    Route::resource('cvs', 'CVController', ['only' => 'index']);
    Route::get('cvs/donwload', 'CVController@download');

    // route untuk wakers
    Route::resource('wakers', 'WakerController', ['only' => 'index']);
});

// route untuk role personnel_service
Route::group([
    'prefix' => 'personnel_service',
    'middleware' => ['auth', 'role:personnel_service']], function () {

    // route default home role personnel service
    Route::get('', 'HomeController@personnelServiceDashboard')
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
});

// route untuk role basis
Route::group([
    'prefix' => 'basis',
    'middleware' => ['auth', 'role:basis']], function () {
    Route::get('', 'HomeController@basisDashboard')->name('dashboards.basis');
    Route::get('settings', 'SettingController@index')->name('settings');
    Route::post('settings', 'SettingController@store')->name('settings.store');
});

// route untuk role secretary
Route::group([
    'prefix' => 'secretary',
    'middleware' => ['auth:secr', 'role:secretary']], function (){

    Route::get('', 'SecretaryController@index')
        ->name('secretary.index');

    // route untuk overtime
    Route::get('overtimes', 'SecretaryController@overtime')
        ->name('secretary.overtimes.index');
    Route::get('overtimes/create', 'SecretaryController@createOvertime')
        ->name('secretary.overtimes.create');
    Route::post('overtimes', 'SecretaryController@storeOvertime')
        ->name('secretary.overtimes.store');

        // route untuk travel
    Route::get('travels', 'SecretaryController@travel')
        ->name('secretary.travels.index');
    Route::get('travels/create', 'SecretaryController@createTravel')
        ->name('secretary.travels.create');
    Route::post('travels', 'SecretaryController@storeTravels')
        ->name('secretary.travels.store');
});

Route::get('/soap', 'SoapController@show');
