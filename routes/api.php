<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('login/google', 'UserController@googleSignIn');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('user', 'UserController@details');
    Route::post('logout', 'UserController@logout');

    Route::get('task/check/{id}', 'TaskController@index');
    Route::get('task/{id}', 'TaskController@show');
    Route::post('task', 'TaskController@store');
    Route::put('task/{id}', 'TaskController@update');
    Route::put('task/{id}', 'TaskController@update');
    Route::put('task/check/{check}', 'TaskController@checkN');
    Route::delete('task/{id}', 'TaskController@destroy');
}); 
