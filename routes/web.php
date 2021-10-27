<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvanceController;

//打刻ページ
Route::post('/', [AdvanceController::class, 'index']);
Route::get('/', [AdvanceController::class, 'index']);

//会員登録ページ

//ログインページ

//日付別勤怠ページ
Route::get('/attendance', [AdvanceController::class, 'attendance']);

require __DIR__.'/auth.php';

Route::get('/auth', [AuthorController::class,'check']);
Route::post('/auth', [AuthorController::class,'checkUser']);
