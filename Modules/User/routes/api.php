<?php

use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\Api\InstructorAdminController;
use Modules\User\App\Http\Controllers\Api\PasswordResetController;
use Modules\User\App\Http\Controllers\Api\StudentAdminController;
use Modules\User\App\Http\Controllers\Api\TrainerAdminController;
use Modules\User\App\Http\Controllers\Api\UserAdminController;
use Modules\User\App\Http\Controllers\Api\UserAuthController;
use Modules\User\App\Http\Controllers\Api\UserController;
use Modules\User\App\Http\Controllers\Api\AcademyController;
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

Route::group([
    'prefix' => 'user',
], function ($router) {
  
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('login', [UserAuthController::class, 'login']);
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::post('refresh', [UserAuthController::class, 'refresh']);
        Route::post('me', [UserAuthController::class, 'me']);
    });
    Route::post('change-password', [UserController::class, 'changePassword']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);

    // Password Reset API Routes for React
    Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword']);
    Route::post('validate-reset-token', [PasswordResetController::class, 'validateResetToken']);
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);
});

Route::group(['prefix' => 'admin'], function ($router) {

Route::post('academies/import', [AcademyController::class, 'import']); // إذا كنتِ تحتاجين استيراد
    Route::resource('academies', AcademyController::class)->except(['update']); // استبعاد الـ update العادي
    Route::post('academies/{user}', [AcademyController::class, 'update']); // تحديث مخصص مثل باقي الموديلات


    Route::post('trainers/import', [TrainerAdminController::class, 'import']);
    Route::resource('trainers', TrainerAdminController::class)->except(['update']);
    Route::post('trainers/{user}', [TrainerAdminController::class, 'update']);

    Route::post('instructors/import', [InstructorAdminController::class, 'import']);
    Route::resource('instructors', InstructorAdminController::class)->except(['update']);
    Route::post('instructors/{user}', [InstructorAdminController::class, 'update']);
    Route::post('academies/delete/{academy}', [AcademyController::class, 'destroy']); // حذف مخصص مثل باقي الموديلات

    Route::post('students/import', [StudentAdminController::class, 'import']);
    Route::resource('students', StudentAdminController::class)->except('update');
    Route::post('students/{user}', [StudentAdminController::class, 'update']);

    Route::post('users/{user}/toggle-activate', [UserAdminController::class, 'toggleActivate']);
});
    Route::delete('academies/test/{id}', [AcademyController::class, 'testdestroy']); // حذف مخصص مثل باقي الموديلات
