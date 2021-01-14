<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\GigItemController;

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
    // Authentication
    Route::post('login', [AuthController::class, 'postLogin']);
    Route::post('signup', [AuthController::class, 'postSignup']);
    Route::post('forgot-password', [AuthController::class, 'postForgotPassword']);

    // Social connection callback
    Route::get('social/{provider}/callback', [ProfileController::class, 'getSocialCallback']);

    Route::middleware('auth:api')->group( function () {
        // Authentication
        Route::get('user', [AuthController::class, 'getUser']);
        Route::get('logout', [AuthController::class, 'getLogout']);

        Route::resource('products', ProductController::class);

        // Social connection redirect
        Route::get('social/{provider}/redirect', [ProfileController::class, 'getSocialRedirect']);

        // Notification
        Route::group(['prefix' => 'notification'], function () {
            Route::get('/', [NotificationController::class, 'getNotifications']);
            Route::post('archive', [NotificationController::class, 'postArchive']);
            Route::post('save-token', [NotificationController::class, 'postSaveFcmDeviceToken']);
            Route::post('send-notification', [NotificationController::class, 'postSendFcmNotification']);
        });
        
        // Profile
        Route::group(['prefix' => 'profile'], function () {
            Route::get('detail/{id}', [ProfileController::class, 'getDetail']);
            Route::post('store', [ProfileController::class, 'postStore']);
            Route::post('cover-photo', [ProfileController::class, 'postCoverPhoto']);
            Route::post('profile-photo', [ProfileController::class, 'postProfilePhoto']);
        });

        // Wallet
        Route::group(['prefix' => 'wallet'], function () {
            Route::get('transactions', [WalletController::class, 'getTransactions']);
            Route::post('withdraw', [WalletController::class, 'postWithdraw']);
            Route::post('deposit', [WalletController::class, 'postDeposit']);
            Route::post('buy', [WalletController::class, 'postBuy']);
        });

        // Gig
        Route::group(['prefix' => 'gig'], function () {
            Route::get('/', [GigItemController::class, 'getGigItems']);
        });
    });

    // Fallback when URL is not existed.
    Route::fallback(function(){
        return response()->json([
            'success' => false,
            'message' => 'Page Not Found. If error persists, contact info@website.com',
        ], 404);
    });
});
