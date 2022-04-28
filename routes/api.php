<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
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

    Route::post('change-password', [UserController::class, 'changePassword'])
        ->name('password');

});

/**
 * Routes For Channel
 */
Route::group(['middleware' => 'auth:api', 'prefix' => 'channel'], function () {

    Route::put('/{id?}', [ChannelController::class, 'update'])
        ->name('channel.update');

    Route::match(['put', 'post'], '', [ChannelController::class, 'updloadBanner'])
        ->name('channel.upload.banner');


    Route::post('/update-socials', [ChannelController::class, 'updateSocials'])
        ->name('channel.update.socials');
});


/**
 * Route For Video
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/video'], function () {

    Route::post('/upload', [VideoController::class, 'upload'])
        ->name('video.upload');

    Route::post('/upload-banner', [VideoController::class, 'uploadBanner'])
        ->name('video.upload.banner');


    Route::post('/', [VideoController::class, 'create'])
        ->name('video.create');


});

/**
 * Roue For Categories
 */
Route::group(['middleware'=>'auth:api' , 'prefix'=>'/category'], function(){

    Route::get('/' , [CategoryController::class , 'getAllCategories'] )
        ->name('category.get.all');

    Route::get('/my' , [CategoryController::class , 'getMyCategories'])
        ->name('category.get.my');
});


//------------- get grand and secret (for auth) ----
Route::get('/passport' , function(){
    return \Illuminate\Support\Facades\DB::table('oauth_clients')->where('id',2)->first();
});
