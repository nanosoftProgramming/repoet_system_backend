<?php

use Illuminate\Support\Facades\Route;
use Modules\Question\App\Http\Controllers\Api\QuestionAdminController;
use Modules\Question\App\Http\Controllers\Api\QuestionCategoryAdminController;

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
Route::group(['prefix' => 'admin'], function () {
    Route::apiResource('question-categories', QuestionCategoryAdminController::class)->except(['show', 'update']);
    Route::post('question-categories/{questionCategory}', [QuestionCategoryAdminController::class, 'update']);
    Route::delete('questions/{question}', [QuestionAdminController::class, 'destroy']);
});
