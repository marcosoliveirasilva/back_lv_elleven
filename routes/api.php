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

Route::post('login',    'App\Http\Controllers\AuthController@login');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    //Route::post('login',    'App\Http\Controllers\AuthController@login');
    Route::post('logout',   'App\Http\Controllers\AuthController@logout');
    Route::post('refresh',  'App\Http\Controllers\AuthController@refresh');
    Route::post('me',       'App\Http\Controllers\AuthController@me');
});

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/user/log', 'UserController@getCurrentUser');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login'          , 'AuthController@login')->name('login');
    Route::post('recuperar-senha', 'AuthController@recuperarSenha')->name('recuperar-senha');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout'         , 'AuthController@logout')->name('logout');
        Route::get('refreshToken'  , 'AuthController@refreshToken')->name('refreshToken');
    });
});*/

Route::prefix('locais')->group(function () {
    Route::get('/'              ,'App\Http\Controllers\Api\LocaisController@index');
    Route::put('restore'        ,'App\Http\Controllers\Api\LocaisController@restore');
    Route::put('update'         ,'App\Http\Controllers\Api\LocaisController@update');
    Route::post('add'           ,'App\Http\Controllers\Api\LocaisController@store');
    Route::delete('delete/{id}' ,'App\Http\Controllers\Api\LocaisController@destroy');
});
