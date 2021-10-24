<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvanceController;

//打刻ページ
Route::get('/', [AdvanceController::class, 'index']);
Route::post('/', [AdvanceController::class, 'index']);

//会員登録ページ
Route::get('/register', [AdvanceController::class, 'register']);

//ログインページ
Route::get('/login', [AdvanceController::class, 'login']);

//日付別勤怠ページ
Route::get('/attendance', [AdvanceController::class, 'attendance']);
