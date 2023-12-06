<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/login', [App\Http\Controllers\Backend\UserController::class, 'login']);
Route::post('/login_otp', [App\Http\Controllers\Backend\UserController::class, 'login_otp']);
Route::post('/login_submit', [App\Http\Controllers\Backend\UserController::class, 'login_submit']);

Route::get('/registration', [App\Http\Controllers\Backend\UserController::class, 'registration']);
Route::post('/registration_otp', [App\Http\Controllers\Backend\UserController::class, 'registration_otp']);
Route::post('/registration_submit', [App\Http\Controllers\Backend\UserController::class, 'registration_submit']);


Route::middleware('check.auth')->group(function () {
    Route::get('/', [App\Http\Controllers\Backend\UserController::class, 'index']);
    // Route::post('/calculate', [App\Http\Controllers\Backend\UserController::class, 'calculate']);
    // Route::get('/calculate_page', [App\Http\Controllers\Backend\UserController::class, 'calculate_page']);

    Route::post('/calculate_percentile', [App\Http\Controllers\Backend\UserController::class, 'calculate_percentile']);
});