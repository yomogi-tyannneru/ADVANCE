<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvanceController;

//打刻ページ
Route::get('/', [AdvanceController::class, 'index'])->middleware('auth')->name('index');
Route::post('/punch_in', [AdvanceController::class, 'punchIn'])->name('punch_in');
Route::post('/punch_out', [AdvanceController::class, 'punchOut'])->name('punch_out');
Route::post('/break_start', [AdvanceController::class, 'breakStart'])->name('break_start');
Route::post('/break_end', [AdvanceController::class, 'breakEnd'])->name('break_end');

//日付別勤怠ページ
Route::get('/attendance', [AdvanceController::class, 'attendance'])->middleware('auth')->name('attendance');

//ユーザー一覧ページ
Route::get('/user_list', [AdvanceController::class, 'userlist'])->middleware('auth')->name('userlist');

//ユーザーごとの勤怠表が見れるページ
Route::get('/attendance_each_user', [AdvanceController::class, 'attendanceeachuser'])->middleware('auth')->name('attendanceeachuser');

require __DIR__.'/auth.php';

// Route::get('/auth', [AuthorController::class,'check']);
// Route::post('/auth', [AuthorController::class,'checkUser']);
