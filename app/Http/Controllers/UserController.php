<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;


class UserController extends Controller
{
  //ユーザー一覧ページ
  public function index(Request $request)
  {
    $users = User::paginate(5);
    return view('user.index', compact('users'));
  }

  // ユーザーごとの勤怠表が見れるページ
  public function show(Request $request, $id)
  {
    $today = new Carbon('today');

    $times_data2 = DB::table('times')
      ->where('user_id', Auth::user()['id'])
      ->whereNotNull('punch_in')
      ->whereDate('date', '<=', $today)
      ->get()
      ->first();
    // $times_data2->isEmpty();
    
    if (is_null($times_data2) || isset($times_data2['punch_in'])) {
      $request->session()->flash('error_message', '打刻データがありません');
      return view('user.show');
    }


    $punch_out_time = Carbon::now();
    $work_time = $this->time_diff(strtotime($times_data2->punch_in), strtotime($punch_out_time));
    isset($times_data2['punch_in']);
  

    DB::table('times')
      ->where('id', $times_data2->id)
      ->update([
        'work_time' => $work_time,
      ]);
    $first_data = DB::table('times')
      ->leftJoin('users', 'users.id', '=', 'times.user_id')
      ->select('times.*', 'users.name')
      ->whereNOTNull('punch_in')
      ->get();

    if ($first_data === null) {
      $request->session()->flash('error_message', '打刻データがありません');
    }

    $times_data = DB::table('times')
      ->leftJoin('users', 'users.id', '=', 'times.user_id')
      ->where('user_id', $id)
      ->select('times.*', 'times.date', 'users.name')
      ->paginate(5);

    $rests_data = DB::table('rests')
      ->join('times', 'rests.time_id', '=', 'times.id')
      ->get();

    $calclate_rest_data = [];
    foreach ($rests_data as $key => $rest) {

      if (!empty($rest->break_start) && !empty($rest->break_end)) {
        $from = strtotime($rest->break_start);
        $to   = strtotime($rest->break_end);
        if (isset($calclate_rest_data[$rest->time_id])) {
          $rest_time_tmp = $calclate_rest_data[$rest->time_id];
        } else {
          $rest_time_tmp = '';
        }
        $rest_time = $this->time_diff($from, $to);
        $calclate_rest_data[$rest->time_id] = $this->time_plus($this->hour_to_sec($rest_time_tmp), $this->hour_to_sec($rest_time));
      }
    }
    $user = Auth::user();
    $param = [
      'times_data' => $times_data,
      'rest_data' => $calclate_rest_data,
      'user' => $user
    ];

    return view('user.show', $param);
  }

  private function time_diff($time_from, $time_to)
  {
    $time = $time_to - $time_from;
    return gmdate("H:i:s", $time);
  }

  private function time_plus($time_from, $time_to)
  {
    $time = $time_to + $time_from;
    return gmdate("H:i:s", $time);
  }

  private function hour_to_sec(string $str): int
  {
    $t = explode(":", $str);
    $h = (int)$t[0];
    if (isset($t[1])) {
      $m = (int)$t[1];
    } else {
      $m = 0;
    }
    if (isset($t[2])) {
      $s = (int)$t[2];
    } else {
      $s = 0;
    }
    return ($h * 60 * 60) + ($m * 60) + $s;
  }
}
