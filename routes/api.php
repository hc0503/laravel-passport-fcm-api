<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;

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

Route::group(['middleware' => ['throttle:60,1']], function () {
    Route::post('login', [AuthController::class, 'postLogin']);
    Route::post('signup', [AuthController::class, 'postSignup']);
    Route::post('forgot-password', [AuthController::class, 'postForgotPassword']);

    Route::middleware('auth:api')->group( function () {
        Route::get('user', [AuthController::class, 'getUser']);
        Route::get('logout', [AuthController::class, 'getLogout']);
        
        Route::resource('products', ProductController::class);

        Route::group(['prefix' => 'profile'], function () {
            Route::get('detail/{profile}', [ProfileController::class, 'getDetail']);
            Route::post('store', [ProfileController::class, 'postStore']);
            Route::post('cover-photo', [ProfileController::class, 'postCoverPhoto']);
            Route::post('profile-photo', [ProfileController::class, 'postProfilePhoto']);
        });
    });

    Route::fallback(function(){
        return response()->json([
            'success' => false,
            'message' => 'Page Not Found. If error persists, contact info@website.com',
        ], 404);
    });
});
