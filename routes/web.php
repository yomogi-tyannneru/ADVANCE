<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvanceController;

//打刻ページ
Route::get('/', [AdvanceController::class, 'index'])->middleware('auth')->name('index');
Route::post('/punch_in', [AdvanceController::class, 'punchIn'])->name('punch_in');
Route::post('/punch_out', [AdvanceController::class, 'punchOut'])->name('punch_out');
Route::post('/break_start', [AdvanceController::class, 'breakStart'])->name('break_start');
Route::post('/break_end', [AdvanceController::class, 'breakEnd'])->name('break_end');


//会員登録ページ

//ログインページ

//日付別勤怠ページ
Route::get('/attendance', [AdvanceController::class, 'attendance']);

require __DIR__.'/auth.php';

Route::get('/auth', [AuthorController::class,'check']);
Route::post('/auth', [AuthorController::class,'checkUser']);
