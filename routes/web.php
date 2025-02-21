<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\CliantController;
use App\Http\Controllers\ManagementController;
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
});

//管理画面
Route::get('/management', [ManagementController::class, 'index'])->name('management');
Route::post('/management/confirm', [ManagementController::class, 'confirm'])->name('management.confirm');
Route::post('/management/complete', [ManagementController::class, 'complete'])->name('management.complete');
Route::get('/cliant', [CliantController::class, 'list'])->name('cliant.list');
Route::DELETE('/cliant/destroy{id}', [CliantController::class, 'destroy'])->name('cliant.destroy');

// 出勤情報画面
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance/confirm', [AttendanceController::class, 'confirm'])->name('attendance.confirm');
Route::post('/attendance/complete', [AttendanceController::class, 'complete'])->name('attendance.complete');

// 現場登録画面
Route::get('/works', [WorkController::class, 'index'])->name('works.index');
Route::post('/works/confirm', [WorkController::class, 'confirm'])->name('works.confirm');
Route::post('/works/complete', [WorkController::class, 'complete'])->name('works.complete');
Route::post('/works/{id}/toggle-status', [WorkController::class, 'toggleStatus'])
    ->name('works.toggleStatus');
Route::DELETE('/works/destroy{id}', [WorkController::class, 'destroy'])->name('works.destroy');


require __DIR__.'/auth.php';
