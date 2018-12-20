<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'structdisp', 'middleware' => 'api'], function() {
    Route::get('/', 'StructDispController@index');
    Route::get('{empnik}', 'StructDispController@show');
    Route::get('{empnik}/subordinates', 'StructDispController@subordinates');
    Route::get('{empnik}/foremanAndOperatorSubordinates', 'StructDispController@foremanAndOperatorSubordinates');
    Route::get('{empnik}/bosses', 'StructDispController@bosses');
    Route::get('{empnik}/closestBoss', 'StructDispController@closestBoss');
    Route::get('{empnik}/minSuperintendentBoss', 'StructDispController@minSuperintendentBoss');
    Route::get('{empnik}/minManagerBoss', 'StructDispController@minManagerBoss');
    Route::get('{empnik}/generalManagerBoss', 'StructDispController@generalManagerBoss');    
    Route::get('costCenter/{cost_center}', 'StructDispController@showByCostCenter');
});

Route::group(['prefix' => 'family', 'middleware' => 'api'], function() {
    Route::get('{personnel_no}', 'FamilyController@show');
    Route::get('{personnel_no}/spouse', 'FamilyController@spouse');
    Route::get('{personnel_no}/parents', 'FamilyController@parents');
    Route::get('{personnel_no}/children', 'FamilyController@children');
});

Route::group(['prefix' => 'position', 'middleware' => 'api'], function() {
    Route::get('{personnel_no}', 'PositionController@show');
    Route::get('{personnel_no}/last', 'PositionController@last');
});

Route::group(['prefix' => 'organization', 'middleware' => 'api'], function() {
    Route::get('/', 'OrganizationController@index');
    Route::get('ObjectID/{ObjectID}/{date?}', 'OrganizationController@show');
    Route::get('level/{unitkerja?}/{date?}', 'OrganizationController@unitkerja');
    Route::get('old/{unitkerjaold?}/{date?}', 'OrganizationController@unitkerjaold');
});
