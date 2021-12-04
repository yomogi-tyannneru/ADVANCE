<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvanceController;
use App\Http\Controllers\UserController;

//打刻ページ
Route::get('/', [AdvanceController::class, 'index'])->middleware('auth')->name('index');
Route::post('/punch_in', [AdvanceController::class, 'punchIn'])->name('punch_in');
Route::post('/punch_out', [AdvanceController::class, 'punchOut'])->name('punch_out');
Route::post('/break_start', [AdvanceController::class, 'breakStart'])->name('break_start');
Route::post('/break_end', [AdvanceController::class, 'breakEnd'])->name('break_end');

//日付別勤怠ページ
Route::get('/attendance', [AdvanceController::class, 'attendance'])->middleware('auth')->name('attendance');
Route::post('/attendance_nextdate', [AdvanceController::class, 'attendance'])->name('attendance_nextdate');
Route::get('/attendance', [AdvanceController::class, 'attendance'])->middleware('auth')->name('attendance');


//ユーザー一覧ページ
Route::get('/user', [UserController::class, 'index'])->middleware('auth')->name('user.index');
//ユーザーごとの勤怠表が見れるページ
Route::get('/user/show/{id}', [UserController::class, 'show'])->middleware('auth')->name('user.show');

require __DIR__ . '/auth.php';

