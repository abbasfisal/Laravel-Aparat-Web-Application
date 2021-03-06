<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\PlayListController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Models\Video;
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

    Route::match(['put', 'post'], '/', [ChannelController::class, 'updloadBanner'])
        ->name('channel.upload.banner');


    Route::post('/update-socials', [ChannelController::class, 'updateSocials'])
        ->name('channel.update.socials');
});


/**
 * Route For Video
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/video'], function () {

    //upload video
    Route::post('/upload', [VideoController::class, 'upload'])
        ->name('video.upload');

    //upload video banner
    Route::post('/upload-banner', [VideoController::class, 'uploadBanner'])
        ->name('video.upload.banner');


    //create a new video
    Route::post('/', [VideoController::class, 'create'])
        ->name('video.create');

    //change video state
    Route::put('/{video}/state', [VideoController::class, 'changeState'])
        ->name('video.change.state');


    Route::post('/{video}/republish', [VideoController::class, 'republish'])
        ->name('video.republish');

    Route::get('/liked', [VideoController::class, 'likedByCurrentUser'])
        ->name('video.liked');

    //get video list
    Route::get('/list', [VideoController::class, 'list'])
        ->name('video.list')
        ->withoutMiddleware('auth:api');

    //like or unlike  a video by login or guest user
    Route::post('/{video}/like', [VideoController::class, 'like'])
        ->name('video.like')
        ->withoutMiddleware('auth:api');
});

/**
 * Roue For Categories
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/category'], function () {

    //create cat for loged in user
    Route::post('/', [CategoryController::class, 'create'])
        ->name('category.create');

    //get all cats
    Route::get('/', [CategoryController::class, 'getAllCategories'])
        ->name('category.get.all');

    //get loged in user categories
    Route::get('/my', [CategoryController::class, 'getMyCategories'])
        ->name('category.get.my');

    //upload cat banner
    Route::post('/upload-banner', [CategoryController::class, 'uploadBanner'])
        ->name('category.upload.banner');
});


/**
 * Routes For PlayLists
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/playlist'], function () {

    Route::get('/', [PlayListController::class, 'getAllPlayList'])
        ->name('playlist.get.all');

    Route::get('/my', [PlayListController::class, 'getMyPlaylist'])
        ->name('playlist.get.my');

    Route::post('/create', [PlayListController::class, 'create'])
        ->name('playlist.create');

});


/*
 * Routes For Tags
 */
Route::group(['middleware' => 'auth:api', 'prefix' => 'tag'], function () {

    Route::get('/', [TagController::class, 'getAllTag'])
        ->name('tag.get.all');

    Route::post('/create', [TagController::class, 'create'])
        ->name('tag.create');
});


//------------- get grand and secret (for auth) ----
Route::get('/passport', function () {
    return \Illuminate\Support\Facades\DB::table('oauth_clients')->where('id', 2)->first();
});


Route::get('/ss', function () {

    /*return Video::query()->whereNotIn('id',
        VideoRepublishes::query()->pluck('video_id')->toArray()
    )->get();*/

    return Video::query()->whereRaw('id not in (select video_id from video_republishes)')->get()->toArray();

});
