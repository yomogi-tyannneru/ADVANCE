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
  public static function cannotpunchInforalreadypunchIn(Request $request)
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
  public static function cannotpunchOutfornotpunchIn(Request $request)
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
  }
  public static function cannotpunchOutfornotbreakEnd(Request $request)
  {
    $user = Auth::user();
    $today = new Carbon('today');
    $punch_in_data = User::find($user->id)->times()
      ->whereNotNull('punch_in')
      ->whereNull('punch_out')
      ->where('date', '<=', $today)
      ->first();

    $isError = false;

    $break_start_data = Time::find($punch_in_data->id)->rests()
      ->whereNotNull('break_start')
      ->whereNull('break_end')
      ->first();
    if ($break_start_data) {
      $request->session()->flash('error_message', '休憩終了打刻をしていないため勤務終了出来ません');
      $isError= true;
    }
    return $isError;
  }
  public static function cannotbreakStartfornotpunchIn(Request $request)
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
  }

  public static function cannotbreakStartforalreadybreakStart(Request $request)
  {
    $isError = false;

    $user = Auth::user();
    $today = new Carbon('today');
    $punch_in_data = User::find($user->id)->times()
      ->whereNotNull('punch_in')
      ->whereNull('punch_out')
      ->where('date', '<=', $today)
      ->first();
      
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

  public static function cannotbreakfornotpunchIn(Request $request)
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
      $request->session()->flash('error_message', '勤務開始打刻をしていないため休憩終了出来ません');
      $isError = true;
    }
    return $isError;
  }

  public static function cannotbreakEndfornotbreakStart(Request $request)
  {
    $isError = false;

    $user = Auth::user();
    $today = new Carbon('today');
    $punch_in_data = User::find($user->id)->times()
      ->whereNotNull('punch_in')
      ->whereNull('punch_out')
      ->where('date', '<=', $today)
      ->first();

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
