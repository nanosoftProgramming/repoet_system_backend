<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\App\Http\Controllers\Api\CourseAdminController;
use Modules\Course\App\Http\Controllers\Api\CourseController;
use Modules\Course\App\Http\Controllers\Api\CourseNoteAdminController;
use Modules\Course\App\Http\Controllers\Api\CourseNoteController;

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

// Super Admin & Trainer
Route::group(['prefix' => 'admin'], function () {
    Route::post('courses/import', [CourseAdminController::class, 'import']);
    Route::apiResource('courses', CourseAdminController::class)->except(['show', 'update']);
    Route::post('courses/{course}', [CourseAdminController::class, 'update']);
    Route::post('courses/{course}/toggle-activate', [CourseAdminController::class, 'toggleActivate']);
    Route::post('courses/{course}/accept', [CourseAdminController::class, 'accept']);

    // Course Notes
    Route::get('courses/notes', [CourseNoteAdminController::class, 'index']);
    Route::post('courses/notes/{note}/update-status', [CourseNoteAdminController::class, 'updateStatus']);
});

// Students
Route::get('courses', [CourseController::class, 'index']);

// Trainers
Route::get('courses/notes', [CourseNoteController::class, 'index']);
Route::apiResource('courses.notes', CourseNoteController::class)->only(['store', 'destroy']);
Route::post('courses/{course}/notes/{note}', [CourseNoteController::class, 'update']);
