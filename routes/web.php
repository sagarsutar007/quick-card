<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;



use App\Http\Middleware\AuthenticateManagement;
use App\Http\Middleware\RedirectIfAuthenticatedManagement;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login', [AuthController::class, 'showManagementLogin'])->middleware(RedirectIfAuthenticatedManagement::class)->name('login.management');
Route::post('login', [AuthController::class, 'loginToManagement'])->name('management.login');
Route::post('logout', [AuthController::class, 'logoutFromManagement'])->name('logout.management');

Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('management.forgot-password');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('management.forgot-password.submit');

Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'submitResetForm'])->name('password.update');

Route::middleware(AuthenticateManagement::class)->group(function () {
    Route::get('dashboard', [DashboardController::class, 'showDashboard'])->name('management.dashboard');
    Route::get('my-profile', [ProfileController::class, 'myProfile'])->name('management.my-profile');
    Route::get('schools', [SchoolController::class, 'index'])->name('management.schools');
    Route::get('blocks', [BlockController::class, 'index'])->name('management.blocks');
    Route::get('clusters', [ClusterController::class, 'index'])->name('management.clusters');
    Route::get('students', [StudentController::class, 'showStudents'])->name('management.students');
    Route::get('users', [UserController::class, 'showUsers'])->name('management.users');
    Route::get('settings', [SettingsController::class, 'showSettings'])->name('management.settings');

    Route::post('my-profile/update', [ProfileController::class, 'updateMyProfile'])->name('profile.update');
    Route::post('my-profile/update-password', [ProfileController::class, 'updateMyPassword'])->name('profile.update.password');
    Route::post('my-profile/update-social-links', [ProfileController::class, 'updateMySocialLinks'])->name('profile.update.social');

    Route::get('my-profile/logs/data', [LogController::class, 'loggedUserData'])->name('logs.mydata');
    Route::get('blocks/get-data', [BlockController::class, 'getAll'])->name('blocks.getAll');
    Route::post('blocks/create', [BlockController::class, 'createBlock'])->name('blocks.create');
    Route::post('blocks/{id}/update', [BlockController::class, 'updateBlock'])->name('blocks.update');
    Route::delete('blocks/{id}/delete', [BlockController::class, 'deleteBlock'])->name('blocks.delete');
    Route::get('/get-blocks/{district_id}', [BlockController::class, 'getBlocksByDistrict'])->name('get.blocks');


    Route::get('cluster/get-data', [ClusterController::class, 'getAll'])->name('cluster.getAll');
    Route::post('cluster/create', [ClusterController::class, 'createCluster'])->name('cluster.create');
    Route::post('cluster/{id}/update', [ClusterController::class, 'updateCluster'])->name('cluster.update');
    Route::delete('cluster/{id}/delete', [ClusterController::class, 'deleteCluster'])->name('cluster.delete');
    Route::get('/get-clusters/{block_id}', [ClusterController::class, 'getClustersByBlock'])->name('get.clusters');

    Route::get('schools/add', [SchoolController::class, 'showSchoolCreateForm'])->name('schools.create');
    Route::post('schools/add', [SchoolController::class, 'addSchool'])->name('schools.add');
    Route::get('schools/{school}/authority', [SchoolController::class, 'showSetAuthorityForm'])->name('schools.setAuthorityForm');
    Route::post('schools/{school}/authority', [SchoolController::class, 'saveAuthority'])->name('schools.saveAuthority');
    Route::get('schools/get-data', [SchoolController::class, 'getAll'])->name('schools.getAll');
    Route::get('schools/{id}/edit', [ClusterController::class, 'editSchool'])->name('schools.edit');
    Route::delete('schools/{id}/delete', [ClusterController::class, 'deleteSchool'])->name('schools.delete');


});

