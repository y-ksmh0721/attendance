<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\CliantController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\CraftController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //管理画面
    Route::get('/management', [ManagementController::class, 'index'])->name('management.management');

    //客先管理画面
    Route::get('/cliant', [CliantController::class, 'list'])->name('cliant.list');
    Route::post('/cliant/confirm', [CliantController::class, 'confirm'])->name('cliant.confirm');
    Route::post('/cliant/complete', [CliantController::class, 'complete'])->name('cliant.complete');
    Route::DELETE('/cliant/destroy{id}', [CliantController::class, 'destroy'])->name('cliant.destroy');

    // 出勤情報画面
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');
    Route::post('/attendance/confirm', [AttendanceController::class, 'confirm'])->name('attendance.confirm');
    Route::post('/attendance/complete', [AttendanceController::class, 'complete'])->name('attendance.complete');
    Route::get('/attendance/edit{id}', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::post('/attendance/update/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::post('/attendance/{id}/toggle-status', [AttendanceController::class, 'toggleStatus'])
    ->name('attendance.toggleStatus');
    Route::post('/attendance/{id}/toggleOvertime', [AttendanceController::class, 'toggleOvertime'])
    ->name('attendance.toggleOvertime');

    // 現場登録画面
    Route::get('/works', [WorkController::class, 'index'])->name('works.index');
    Route::post('/works/confirm', [WorkController::class, 'confirm'])->name('works.confirm');
    Route::post('/works/complete', [WorkController::class, 'complete'])->name('works.complete');
    Route::post('/works/{id}/toggle-status', [WorkController::class, 'toggleStatus'])
    ->name('works.toggleStatus');
    Route::DELETE('/works/destroy{id}', [WorkController::class, 'destroy'])->name('works.destroy');

    //職人管理画面
    Route::get('/craft', [CraftController::class, 'index'])->name('craft.index');
    Route::post('/craft/confirm', [CraftController::class, 'confirm'])->name('craft.confirm');
    Route::post('/craft/complete', [CraftController::class, 'complete'])->name('craft.complete');
    Route::post('/craft/{id}/toggle-status', [CraftController::class, 'toggleStatus'])
    ->name('craft.toggleStatus');
    Route::DELETE('/craft/destroy{id}', [CraftController::class, 'destroy'])->name('craft.destroy');

    //所属管理画面
    Route::get('/company', [CompanyController::class, 'list'])->name('company.list');
    Route::post('/company/confirm', [CompanyController::class, 'confirm'])->name('company.confirm');
    Route::post('/company/complete', [CompanyController::class, 'complete'])->name('company.complete');
    Route::DELETE('/company/destroy{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
});


require __DIR__.'/auth.php';
