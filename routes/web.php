<?php

use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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


Route::post('api/register', UserController::class .'@register');
Route::post('login', UserController::class .'@login');
Route::post('api/logout',UserController::class .'@logout');

Route::get('api/email/verify/notice', UserController::class .'@verificationNotice')->name('verification.notice');
Route::get('api/email/verify/{id}/{hash}',UserController::class .'@verifyEmail')->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('api/forgot-password', UserController::class .'@forgotPassword')->middleware('guest')->name('password.email');
Route::post('api/reset-password',UserController::class .'@resetPassword')->middleware('guest')->name('password.update');
Route::get('api/reset-password/{token}', UserController::class .'@showResetPasswordForm')->middleware('guest')->name('password.reset');

Route::get('/{url}', UrlController::class . '@click');
