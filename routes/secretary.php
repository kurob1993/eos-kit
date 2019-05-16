<?php
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