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


Route::group(['prefix' => '/v1', 'namespace' => 'V1'], function () use ($router) {
    
    Route::get('/get-timestamp', function () {
        return ["data" => date("Y-m-d H:i:s")];
    });

    Route::get('sliders', 'SliderController@getSlider');
    Route::get('courses', 'CourseController@getCourse');
    /* Route::post('userlogin', 'LoginController@checkAuth');
    Route::get('login', 'LoginController@checkAuthLogin'); */
});