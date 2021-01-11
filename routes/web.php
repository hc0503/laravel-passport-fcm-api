<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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
});

Route::group(['prefix' => 'social'], function () {
    Route::get('google-redirect', [ProfileController::class, 'googleRedirect']);
    Route::get('google-callback', [ProfileController::class, 'googleCallback']);
});