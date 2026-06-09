<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Attendance\App\Http\Controllers\Api\AttendanceAdminController;


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
    Route::get('courses/{course}/students', [AttendanceAdminController::class, 'getStudentsByCourse']);
    Route::apiResource('attendances', AttendanceAdminController::class)->only(['index','store']);
    Route::get('attendances/today', [AttendanceAdminController::class, 'todayAttendance']);
});
