<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\ProfileController;


Route::post('/login', [AuthController::class, 'login']);
Route::get('/schools', [SchoolController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user actions
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/profile', 'profile');
        Route::get('/user/schools', 'getUserSchools');
    });

    // Profile actions
    Route::controller(ProfileController::class)->group(function () {
        Route::post('/profile/update', 'updateProfile');
        Route::post('/profile/password', 'updatePassword');
        Route::post('/profile/social-links', 'updateSocialLinks');
    });

    // School and student data
    Route::get('/schools/{id}/students', [StudentController::class, 'getSchoolStudents']);
    Route::get('/dashboard', [DashboardController::class, 'show']);


    // Permission-restricted routes
    Route::middleware('permission:edit student')->post('/students/update', [StudentController::class, 'update']);
    Route::middleware('permission:upload student image')->post('/students/upload-photo', [StudentController::class, 'uploadStudentPhoto']);
    Route::middleware('permission:add student')->post('/students', [StudentController::class, 'saveStudent']);

});
