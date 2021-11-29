<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class UserController extends Controller
{
  //打刻ページ
  public function index(Request $request)
  {
    $users = User::paginate(2);
    return view('user.index', compact('users'));
  }

  public function show(Request $request, $id)
  {
    
    // 勤怠開始のデータがない場合の表示は打刻データがありませんと表示
    $first_data = DB::table('times')
    ->leftJoin('users', 'users.id', '=', 'times.user_id')
    ->select('times.*', 'users.name')
    ->whereNOTNull('punch_in')
    ->get();

    if ($first_data === null) {
      $request->session()->flash('error_message', '打刻データがありません');
    }
    
    // 出勤データの取得処理　1日分にするため？
    $times_data = DB::table('times')
      //timesテーブルのtimes、user_idとusersテーブルのusers.idをくっつける
      ->leftJoin('users', 'users.id', '=', 'times.user_id')
      ->where('user_id', $id) // 選択したユーザーのデータのみ取得
      //timesテーブルの全データとusersテーブルのnameを取得
      //.は、〜の〜の中のという意味
      ->select('times.*', 'times.date', 'users.name')
      // ->groupBy('times.date')
      ->get();

    //？
    $times_data = json_decode(json_encode($times_data), true);
    $times_data = array_column($times_data, null, 'id');
    foreach ($times_data as $data) {
      if ($data['punch_out']) {
        $from = strtotime($data['punch_in']);
        $to   = strtotime($data['punch_out']);
        $times_data[$data['id']]['work_time'] = $this->time_diff($from, $to);
      }
      //初期値0
      $times_data[$data['id']]['rest_time'] = '00:00:00';
    }
    // dd($times_data);
    // 休憩開始データの取得処理
    $rests_data = DB::table('rests')
      ->join('times', 'rests.time_id', '=', 'times.id')
      ->whereIn('time_id', array_keys($times_data))
      ->get();
    // $rests_data配列の要素$key => $restの繰り返し次の処理をする 全データの中の1つのtimesテーブルのid　つまり、一日分
    // $restとは？　一日に何度もある休憩を足すための繰り返し計算
    //foreach (配列 as 要素 => 値、中身)
    //配列として指定した時点でその要素は0から順に増えていくものと決まっている
    foreach ($rests_data as $key => $rest) {
      // もし休憩テーブルのtime_idがあるならば(勤務開始に値があるならば)
      // 休憩テーブルがあるということは、勤怠テーブルが有るということなのでifにしてあるが、ほぼtrueでfalseになることはない
      if (array_key_exists($rest->time_id, $times_data)) {
        // もし休憩開始と休憩終了が空ではないならば
        if (!empty($rest->break_start) && !empty($rest->break_end)) {
          $from = strtotime($rest->break_start);
          $to   = strtotime($rest->break_end);
          //timesテーブルの1つを選んだそれのrest_time　1日分の休憩時間　一時保存tmp　一周目は初期値
          $rest_time_tmp = $times_data[$rest->time_id]['rest_time'];
          //計算した休憩時間
          $rest_time = $this->time_diff($from, $to);
          //時間を秒に直して、一時保存したものと現在足したものの2つを足す
          $times_data[$rest->time_id]['rest_time'] = $this->time_plus($this->hour_to_sec($rest_time_tmp), $this->hour_to_sec($rest_time));
        }
      }
    }
    // なぜ反映されない
    $user = Auth::user();

    return view('user.show', compact('times_data'));
  }

  private function time_diff($time_from, $time_to)
  {
    $time = $time_to - $time_from;
    //時：分：秒
    return gmdate("H:i:s", $time);
  }

  private function time_plus($time_from, $time_to)
  {
    $time = $time_to + $time_from;
    return gmdate("H:i:s", $time);
  }

  private function hour_to_sec(string $str): int
  {
    $t = explode(":", $str); //配列（$t[0]（時）、$t[1]（分）、$t[2]（秒））にする
    $h = (int)$t[0];
    if (isset($t[1])) { //分の部分に値が入っているか確認
      $m = (int)$t[1];
    } else {
      $m = 0;
    }
    if (isset($t[2])) { //秒の部分に値が入っているか確認
      $s = (int)$t[2];
    } else {
      $s = 0;
    }
    return ($h * 60 * 60) + ($m * 60) + $s;
  }
}
