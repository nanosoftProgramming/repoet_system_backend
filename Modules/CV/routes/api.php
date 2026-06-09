<?php

use Illuminate\Support\Facades\Route;
use Modules\CV\App\Http\Controllers\Api\CVAdminController;
use Modules\CV\App\Http\Controllers\Api\CVController;
use Modules\CV\App\Http\Controllers\Api\CVFieldTemplateAdminController;

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

// Super Admin
Route::group(['prefix' => 'admin'], function () {
    Route::apiResource('cv-field-templates', CVFieldTemplateAdminController::class)->except(['show', 'update']);
    Route::post('cv-field-templates/update', [CVFieldTemplateAdminController::class, 'update']);
    Route::post('cv-field-templates/{cvFieldTemplate}/toggle-activate', [CVFieldTemplateAdminController::class, 'toggleActivate']);

    // CV Admin Routes - View all CVs with answers
    Route::get('cvs', [CVAdminController::class, 'index']);
    Route::get('cvs/{id}', [CVAdminController::class, 'show']);
});

// Students, Instructors, Trainers
Route::get('cv', [CVController::class, 'show']);
Route::post('cv', [CVController::class, 'store']);
Route::post('cv/update', [CVController::class, 'update']);
