<?php

use Illuminate\Support\Facades\Route;
use Modules\Exam\App\Http\Controllers\Api\ExamController;
use Modules\Exam\App\Http\Controllers\Api\ExamAdminController;

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

//Super Admin & Trainer
Route::group(['prefix' => 'admin'], function () {
    Route::post('exams/import', [ExamAdminController::class, 'import']);
    Route::apiResource('exams', ExamAdminController::class)->only(['index', 'destroy']);
    Route::post('exams/{exam}', [ExamAdminController::class, 'update']);
    Route::post('exams/{exam}/toggle-activate', [ExamAdminController::class, 'toggleActivate']);
    Route::post('enrollments/{enrollment}/exams', [ExamAdminController::class, 'store']);
});

Route::get('exams', [ExamController::class, 'index']);
