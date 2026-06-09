<?php

use Illuminate\Support\Facades\Route;
use Modules\Enrollment\App\Http\Controllers\Api\EnrollmentController;
use Modules\Enrollment\App\Http\Controllers\Api\EnrollmentNoteController;
use Modules\Enrollment\App\Http\Controllers\Api\EnrollmentAdminController;
use Modules\Enrollment\App\Http\Controllers\Api\EnrollmentNoteAdminController;

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
    Route::get('enrollments', [EnrollmentAdminController::class, 'index']);
    Route::post('enrollments/{enrollment}', [EnrollmentAdminController::class, 'update']);

    //Enrollment Notes
    Route::get('enrollments/notes', [EnrollmentNoteAdminController::class, 'index']);
    Route::post('enrollments/notes/{note}/update-status', [EnrollmentNoteAdminController::class, 'updateStatus']);
});

//Students
Route::resource('enrollments', EnrollmentController::class)->only(['index', 'store']);
Route::get('enrollments/notes', [EnrollmentNoteController::class, 'index']);
Route::post('enrollments/{enrollment}/notes', [EnrollmentNoteController::class, 'store']);
Route::post('enrollments/{enrollment}/notes/{note}', [EnrollmentNoteController::class, 'update']);
