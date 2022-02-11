<?php

namespace app\Common;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Time;

class validation
{
  public static function punchInvalidation(Request $request)
  {
    $isError = false;

    $user = Auth::user();
    $today = new Carbon('today');
    $punch_in_data = User::find($user->id)->times()
      ->where('date', $today)
      ->first();

    if ($punch_in_data) {
      $request->session()->flash('error_message', '既に勤務を開始しているため勤務開始出来ません');
      $isError = true;
    }
    return $isError;
  }
  public static function punchOutvalidation(Request $request)
  {
    $isError = false;

    $user = Auth::user();
    $today = new Carbon('today');
    $punch_in_data = User::find($user->id)->times()
      ->whereNotNull('punch_in')
      ->whereNull('punch_out')
      ->where('date', '<=', $today)
      ->first();
    if ($punch_in_data === null) {
      $request->session()->flash('error_message', '今日またはそれ以前の勤務開始打刻がないため勤務終了出来ません');
      $isError = true;
    }
    return $isError;

    $isError2 = false;

    $break_start_data = Time::find($punch_in_data->id)->rests()
      ->whereNotNull('break_start')
      ->whereNull('break_end')
      ->first();
    if ($break_start_data) {
      $request->session()->flash('error_message', '休憩終了打刻をしていないため勤務終了出来ません');
      $isError2 = true;
    }
    return $isError2;
  }

  public static function breakStartvalidation(Request $request)
  {
    $isError = false;

    $user = Auth::user();
    $today = new Carbon('today');
    $punch_in_data = User::find($user->id)->times()
      ->whereNotNull('punch_in')
      ->whereNull('punch_out')
      ->where('date', '<=', $today)
      ->first();

    if ($punch_in_data === null) {
      $request->session()->flash('error_message', '勤務開始打刻をしていないため休憩開始出来ません');
      $isError = true;
    }
    return $isError;

    $break_start_data = Time::find($punch_in_data->id)->rests()
      ->whereNotNull('break_start')
      ->whereNull('break_end')
      ->first();

    if ($break_start_data) {
      $request->session()->flash('error_message', '既に休憩開始ボタンを押しているため休憩開始出来ません');
      $isError = true;
    }
    return $isError;
  }

  public static function breakEndvalidation(Request $request)
  {
    $isError = false;

    $user = Auth::user();
    $today = new Carbon('today');
    $punch_in_data = User::find($user->id)->times()
      ->whereNotNull('punch_in')
      ->whereNull('punch_out')
      ->where('date', '<=', $today)
      ->first();

    if ($punch_in_data === null) {
      $request->session()->flash('error_message', '出勤開始打刻をしていないため休憩終了出来ません');
      $isError = true;
    }
    return $isError;

    $break_start_data = Time::find($punch_in_data->id)->rests()
      ->whereNotNull('break_start')
      ->whereNull('break_end')
      ->first();

    if ($break_start_data === null) {
      $request->session()->flash('error_message', '休憩開始打刻をしていないため休憩終了出来ません');
      $isError = true;
    }
    return $isError;
  }
}
