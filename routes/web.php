<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidationController;

//打刻ページ
Route::get('/', [AttendanceController::class, 'index'])->middleware('auth')->middleware('verified')->name('index');

Route::post('/punch_in', [AttendanceController::class, 'punchIn'])->name('punch_in');
Route::post('/punch_out', [AttendanceController::class, 'punchOut'])->name('punch_out');
Route::post('/break_start', [AttendanceController::class, 'breakStart'])->name('break_start');
Route::post('/break_end', [AttendanceController::class, 'breakEnd'])->name('break_end');

//日付別勤怠ページ
Route::get('/attendance', [AttendanceController::class, 'attendance'])->middleware('auth')->name('attendance');
Route::get('/attendance_nextdate', [AttendanceController::class, 'attendanceNextdate'])->name('attendance_nextdate');
Route::get('/attendance_beforedate', [AttendanceController::class, 'attendanceBeforedate'])->name('attendance_beforedate');


Route::group(['middleware' => 'auth'], function () {
  //ユーザー一覧ページ
  Route::get('/user', [UserController::class, 'index'])->name('user.index');
  //ユーザーごとの勤怠表が見れるページ
  Route::get('/user/show/{id}', [UserController::class, 'show'])->name('user.show');
});


require __DIR__ . '/auth.php';

