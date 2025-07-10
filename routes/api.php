<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleOrPermissionMiddleware;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/schools', [SchoolController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user actions
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/profile', 'profile');
    });

    // Profile actions
    Route::controller(ProfileController::class)->group(function () {
        Route::post('/profile/update', 'updateProfile');
        Route::post('/profile/password', 'updatePassword');
        Route::post('/profile/social-links', 'updateSocialLinks');
    });

    // School and student data
    Route::get('/user/schools', [SchoolController::class, 'getUserSchools']);
    Route::get('/user/search-schools', [SchoolController::class, 'search']);
    Route::get('/schools/{schoolId}/students', [StudentController::class, 'getSchoolStudents']);
    Route::post('/schools/{schoolId}/authority', [SchoolController::class, 'addAuthority']);
    Route::get('/dashboard', [DashboardController::class, 'show']);

    // Permission-restricted routes
    Route::post('/students/update', [StudentController::class, 'update'])->middleware([RoleOrPermissionMiddleware::class . ':admin|superadmin,edit student']);
    Route::post('/students/upload-photo', [StudentController::class, 'uploadStudentPhoto'])->middleware([RoleOrPermissionMiddleware::class . ':admin|superadmin,upload student image']);
    Route::post('/students', [StudentController::class, 'saveStudent'])->middleware([RoleOrPermissionMiddleware::class . ':admin|superadmin,add student']);
    Route::delete('/students/{student}/photo', [StudentController::class, 'deletePhoto'])->middleware([RoleOrPermissionMiddleware::class . ':admin|superadmin,remove student image']);;
    
    Route::get('/all-students', [StudentController::class, 'getAllStudents']);

});
