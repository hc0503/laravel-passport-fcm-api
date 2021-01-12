<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;

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
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::post('save-token', [HomeController::class, 'postSaveToken'])->name('save-token');
    Route::post('send-notification', [HomeController::class, 'postSendNotification'])->name('send.notification');
});

Route::group(['prefix' => 'social'], function () {
    Route::get('redirect/{provider}', [ProfileController::class, 'getSocialRedirect']);
    Route::get('callback/{provider}', [ProfileController::class, 'getSocialCallback']);
});