<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
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

Route::post('/login', 'App\Http\Controllers\LoginController@login');
Route::post('/register', 'App\Http\Controllers\LoginController@register');
Route::middleware('auth:sanctum')->post('/upload', 'App\Http\Controllers\FileController@upload');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/box/my', 'App\Http\Controllers\BoxController@my');
    Route::post('/box/create', 'App\Http\Controllers\BoxController@store');
    Route::post('/box/set', 'App\Http\Controllers\BoxController@setDefault');
    Route::get('/box/default', 'App\Http\Controllers\BoxController@getDefault');
    Route::get('/files/my', 'App\Http\Controllers\FileController@my');
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/register', 'LoginController@register');
// Route::get('/logout', 'LoginController@logout');
