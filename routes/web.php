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
Route::get('login', [
    'as' => 'login', 
    'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', [
    'as' => '', 
    'uses' => 'Auth\LoginController@login']);
Route::post('logout', [
    'as' => 'logout', 
    'uses' => 'Auth\LoginController@logout']);

Route::get('/', 'HomeController@index')->name('dashboards.employee');

Route::group([
    'prefix' => 'personnel_service', 
    'middleware' => ['auth', 'role:personnel_service']], function (){
        Route::get('', 'HomeController@personnelServiceDashboard')->name('dashboards.personnel_service');
        Route::resource('all_leaves', 'AllLeaveController', ['except' => [
            'destroy', 'show', 'update', 'edit', 'create' ]]);
        Route::resource('all_absence_quotas', 'AllAbsenceQuotaController', ['except' => [
            'destroy', 'show', 'update', 'edit', 'create' ]]);
});

Route::group([
    'prefix' => 'basis', 
    'middleware' => ['auth', 'role:basis']], function () {
        Route::get('', 'HomeController@basisDashboard')->name('dashboards.basis');
        Route::get('settings', 'SettingController@index')->name('settings');
        Route::post('settings', 'SettingController@store')->name('settings.store');    
});

Route::group([
    'middleware' => ['auth', 'role:employee']], function () {
        Route::get('debug', 'DebugController@debug');
        Route::resource('leaves', 'LeaveController', ['except' => [ 
            'destroy', 'show', 'update', 'edit' ]]);
        Route::post('approve/{id}','HomeController@approve')->name('dashboards.approve');
        Route::post('reject/{id}','HomeController@reject')->name('dashboards.reject');
});