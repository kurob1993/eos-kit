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
    Route::get('{empnik}', 'StructDispController@show');
    Route::get('{empnik}/subordinates', 'StructDispController@subordinates');
    Route::get('{empnik}/bosses', 'StructDispController@bosses');
    Route::get('{empnik}/closestBoss', 'StructDispController@closestBoss');
    Route::get('{empnik}/minSuperintendentBoss', 'StructDispController@minSuperintendentBoss');
    Route::get('{empnik}/minManagerBoss', 'StructDispController@minManagerBoss');
    Route::get('{empnik}/generalManagerBoss', 'StructDispController@generalManagerBoss');
});