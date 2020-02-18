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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//});
//Route::resource('user', 'UserController');
//Route::resource('task', 'TaskController');

Route::group(['as' => 'api.'], function () {
    Route::get('users', 'UserController@index')->name('index');
    Route::get('user/{id}', 'UserController@show')->name('show');
    Route::post('user', 'UserController@store')->name('store');
    Route::put('user', 'UserController@store')->name('edit');
    Route::delete('user/{id}', 'UserController@destroy')->name('destroy');

    Route::get('tasks', 'TaskController@index')->name('index');
    Route::get('task/{id}', 'TaskController@show')->name('show');
    Route::post('task', 'TaskController@store')->name('store');
    Route::put('task', 'TaskController@store')->name('edit');
    Route::delete('task/{id}', 'TaskController@destroy')->name('destroy');
});
