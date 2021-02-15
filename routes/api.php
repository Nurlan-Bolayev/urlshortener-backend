<?php

use App\Http\Controllers\UrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/users/me', function (Request $request) {
    return $request->user();
});

Route::post('urls/add-url', UrlController::class . '@create');
Route::get('urls/{url}', UrlController::class . '@click');

Route::middleware('auth')->group(function () {

    Route::get('{url}/statistics', UrlController::class .'@statistics');
    Route::get('/urls', UrlController::class . '@all');
    Route::delete('urls/{url}', UrlController::class . '@delete');
});
