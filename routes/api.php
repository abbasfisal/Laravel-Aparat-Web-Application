<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\TransientTokenController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//----- 2 route form passport routes which used in api -----
Route::group(['middleware' => 'throttle'], function () {

    //For Login
    Route::post('/login', [AccessTokenController::class, 'issueToken'])
        ->name('passport.login');

    //For Refresh Token
    Route::post('/refresh', [TransientTokenController::class, 'refresh'])
        ->name('passport.token.refresh');

});
