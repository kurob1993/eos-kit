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
Route::get('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', [ 'as' => '', 'uses' => 'Auth\LoginController@login']);
Route::post('logout', [ 'as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

// route for programatically employee login to system
Route::get('a/{personnel_no}/{email}', 'Auth\LoginController@programaticallyEmployeeLogin')
    ->name('login.a');

// route for programatically secretary login to system
Route::get('b/{email}', 'Auth\LoginController@programaticallySecretaryLogin')
    ->name('login.b');

// route untuk user yang belum di set Role-nya
Route::get('noRole', 'HomeController@noRole')->name('noRole');

// preference and dislike
Route::get('admin/preference','PreferenceController@preferncesDashboard')
->name('pref.dashboard');
Route::post('admin/download','PreferenceController@download')
->name('pref.download');

Route::group(['prefix' => 'preferdis/periode'], function () {
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