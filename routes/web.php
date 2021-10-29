<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvanceController;

//打刻ページ
Route::get('/', [AdvanceController::class, 'timesget','restsget']);


//会員登録ページ

//ログインページ

//日付別勤怠ページ
Route::get('/attendance', [AdvanceController::class, 'attendance']);

require __DIR__.'/auth.php';

Route::get('/auth', [AuthorController::class,'check']);
Route::post('/auth', [AuthorController::class,'checkUser']);
