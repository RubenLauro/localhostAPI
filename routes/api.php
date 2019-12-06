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

Route::post('login', 'LoginControllerAPI@login');

Route::post('register', 'UserControllerAPI@store');

Route::post('reset/password', 'UserControllerAPI@resetPasswordSendLink');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'LoginControllerAPI@logout');

    Route::get('me', 'UserControllerAPI@me');

    Route::put('me/update', 'UserControllerAPI@update');

    Route::post('avatar', 'UserControllerAPI@uploadAvatar'); //como é file tem de ser post
});

Route::get('testYelp','YelpAPIController@test');
Route::get('testZomato','ZomatoAPIController@test');
Route::get('testFoursquare','FoursquareAPIController@test');
Route::get('search','LocalhostAPIController@searchByRadius');
Route::get('searchByName','LocalhostAPIController@searchByName');

