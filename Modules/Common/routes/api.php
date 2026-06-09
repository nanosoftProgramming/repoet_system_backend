<?php

use Illuminate\Support\Facades\Route;
use Modules\Common\App\Http\Controllers\Api\IntroController;
use Modules\Common\App\Http\Controllers\Api\CommonController;
use Modules\Common\App\Http\Controllers\Api\SearchController;
use Modules\Common\App\Http\Controllers\Api\SettingController;
use Modules\Common\App\Http\Controllers\Api\SearchAdminController;

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


Route::post('contact', [CommonController::class, 'contact']);
Route::get('admin/search', [SearchAdminController::class, 'search']);
Route::get('search', [SearchController::class, 'search']);
Route::apiResource('intros', IntroController::class)->only(['index', 'store', 'destroy']);
Route::post('intros/{intro}', [IntroController::class, 'update']);
Route::group(['prefix' => 'admin'], function () {
    Route::get('settings', [SettingController::class, 'index']);
    Route::put('settings', [SettingController::class, 'update']);
});
