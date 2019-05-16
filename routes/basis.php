<?php
    Route::get('', 'Dashboard\DashboardController@basisDashboard')->name('dashboards.basis');
    Route::get('settings', 'SettingController@index')->name('settings');
    Route::post('settings', 'SettingController@store')->name('settings.store');
