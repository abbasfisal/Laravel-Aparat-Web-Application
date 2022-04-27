<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\UserController;
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

//----- 2 route form passport routes which used in api -----
Route::group(['middleware' => 'throttle'], function () {
    //For Login
    Route::post('/login', [AccessTokenController::class, 'issueToken'])
        ->name('auth.login');

    //For Refresh Token
    Route::post('/refresh', [TransientTokenController::class, 'refresh'])
        ->name('auth.token.refresh');

});


//---------
/**
 * روت های مربوط به auth
 */
Route::group([], function () {

    Route::post('/register', [AuthController::class, 'register'])
        ->name('auth.register');

    Route::post('/register-verify', [AuthController::class, 'registerVerify'])
        ->name('auth.register.verify');

    Route::post('/resend-verification-code', [AuthController::class, 'resendVerificationCode'])
        ->name('auth.register.resend.verification.code');

});

/**
 * Routes For Change and verify  Email
 */
Route::group(['middleware' => 'auth:api', 'as' => 'change.'], function () {

//chage Email
    Route::post('change-email', [UserController::class, 'changeEmail'])
        ->name('email');

//submit for change email
    Route::post('change-email-submit', [UserController::class, 'ChangeEmailSubmit'])
        ->name('email.submit');


});

/**
 * Routes For Channel
 */
Route::group(['middleware'=>'auth:api' ,'prefix'=>'channel'] , function (){

    Route::put('/{id?}',[ChannelController::class , 'update']);

});
