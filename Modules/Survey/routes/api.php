<?php

use Illuminate\Support\Facades\Route;
use Modules\Survey\App\Http\Controllers\Api\SurveyAdminController;
use Modules\Survey\App\Http\Controllers\Api\SurveyController;

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
Route::middleware(['auth:user', 'role:Super Admin|Academy'])->prefix('admin')->group(function () {
Route::post('surveys', [SurveyAdminController::class, 'storeSurvey']); 
        Route::get('test-answers', [SurveyAdminController::class, 'testAnswers']);
Route::get('survey/answers', [SurveyAdminController::class, 'answers']);
Route::put('surveys/{id}/toggle', [SurveyAdminController::class, 'toggleActive']);
   Route::get('survey/answers/by-category', [SurveyAdminController::class, 'getAnswersByCategory']);
    Route::get('survey/questions', [SurveyAdminController::class, 'getQuestions']);
Route::get('surveys', [SurveyAdminController::class, 'index']); // لجلب الاستبيانات
Route::delete('surveys/{id}', [SurveyAdminController::class, 'deleteSurvey']);
// Route::get('surveys/{id}', [SurveyAdminController::class, 'showSurvey']);    
Route::put('surveys/{id}', [SurveyAdminController::class, 'updateSurvey']);
});
Route::get('surveys/{id}', [SurveyAdminController::class, 'showSurvey']);

// Students
Route::middleware(['auth:user', 'role:Student'])->group(function () {
Route::get('my-submissions', [SurveyController::class, 'mySubmissions']);
// Route::get('surveys', [SurveyController::class, 'surveys']);
  Route::get('survey/my-submissions', [SurveyController::class, 'mySubmissions']);
});
    Route::post('survey/answers', [SurveyController::class, 'store']);
Route::get('surveys', [SurveyController::class, 'surveys']);

// Super Admin
// Route::middleware(['auth:user', 'role:Super Admin'])->prefix('admin')->group(function () {
//     Route::get('survey/answers', [SurveyAdminController::class, 'index']);
//     Route::get('survey/answers/by-category', [SurveyAdminController::class, 'getAnswersByCategory']);
// });
