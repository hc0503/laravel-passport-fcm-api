<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group( function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::post('save-token', [HomeController::class, 'postSaveToken'])->name('save-token');
    Route::post('send-notification', [HomeController::class, 'postSendNotification'])->name('send.notification');

    Route::get('payments', [PaymentController::class, 'getPayments']);
    Route::post('charge', [PaymentController::class, 'postCharge'])->name('charge');
    Route::get('test', [PaymentController::class, 'getTest'])->name('test');
});

Route::group(['prefix' => 'social'], function () {
    Route::get('redirect/{provider}', [ProfileController::class, 'getSocialRedirect']);
    Route::get('callback/{provider}', [ProfileController::class, 'getSocialCallback']);
});