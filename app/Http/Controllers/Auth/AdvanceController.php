<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class AdvanceController extends Controller
{
    //打刻ページ
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('auth.index', compact('user'));
    }

    // 出勤処理
    public function punchIn(Request $request)
    {
        $today = new Carbon('today');
        $data = DB::table('times')
            ->where('user_id', Auth::user()['id'])
            ->whereDate('date', $today)
            ->get()
            ->first();

        if ($data) {
            // すでに出勤ボタンを押しているためエラーを表示させて打刻ページに戻す（エラーメッセージをセットする）
            $request->session()->flash('error_message', '既に勤務を開始しているため勤務開始出来ません');
            return redirect(route('index'));
        }

        // 出勤データの登録処理
        DB::table('times')->insert(
            [
                'user_id' => Auth::user()['id'],
                'date' => Carbon::now(),
                'punch_in' => Carbon::now()
            ]
        );
        // 処理成功のメッセージをセット
        $request->session()->flash('success_message', '勤務開始しました');
        return redirect(route('index'));
    }

    // 退勤処理
    public function punchOut(Request $request)
    {
        $today = new Carbon('today');

        // 当日以前の出勤のみのデータを取得
        $times_data = DB::table('times')
            ->where('user_id', Auth::user()['id'])
            ->whereNotNull('punch_in')
            ->whereNull('punch_out')
            ->whereDate('date', '<=', $today)
            ->get()
            ->first();
        //当日以前の出勤のみのデータの値がないならば、エラーを表示させて打刻ページに戻す
        if ($times_data === null) {
            $request->session()->flash('error_message', '今日またはそれ以前の勤務開始打刻がないため勤務終了出来ません');
            return redirect(route('index'));
        }

        // 一回以上休憩開始、休憩終了しているか
        // $rests_data = DB::table('rests')
        //     ->where('time_id', $times_data->id)
        //     ->whereNotNull('break_start')
        //     ->whereNotNull('break_end')
        //     ->get()
        //     ->first();
        // if ($rests_data === null) {
        //     // 一回も休憩していないためエラーを表示
        //     return redirect(route('index'));
        // }

        // 休憩開始だけしている場合
        $rests_data = DB::table('rests')
            ->where('time_id', $times_data->id)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->get()
            ->first();
        //休憩開始だけしている場合、エラーを表示させて打刻ページに戻す
        if ($rests_data) {
            $request->session()->flash('error_message', '休憩終了打刻をしていないため勤務終了出来ません');
            return redirect(route('index'));
        }

        // $yesterday = new Carbon('yesterday');
        // // $data2 = DB::table('times')
        // //     ->where('user_id', Auth::user()['id'])
        // //     ->whereDate('date', $yesterday)
        // //     ->where('punch_in')
        // //     ->get()
        // //     ->first();
        // // $today = new Carbon('today');
        // $data3 = DB::table('rests')
        //     ->where('user_id', Auth::user()['id'])
        //     ->whereDate('date', '<=', $today)
        //     ->where('break_end')
        //     ->get()
        //     ->first();
        //     // 休憩開始後、休憩終了前に退勤が出来ないように実装つまり退勤ボタンも変更する
        //     // 日付が変わると退勤できない　昨日の出勤も感知するように　あり退勤されていない場合
        // if (!$data) {
        //     // 退勤処理をする条件が整っていないためエラーを表示させて打刻ページに戻す（エラーメッセージをセットする）
        //     return redirect(route('index'));
        // }

        // 退勤データの更新処理
        DB::table('times')
            ->where('id', $times_data->id)
            ->update(['punch_out' => Carbon::now()]);
        // 処理成功のメッセージをセット
        // ...dash~で確認
        $request->session()->flash('success_message', '勤務終了しました');
        return redirect(route('index'));
    }

    // 休憩開始処理
    public function breakStart(Request $request)
    {
        $today = new Carbon('today');

        $now = new Carbon('now'); //今日の休憩開始、勤務終了を取得

        // 当日以前の出勤のみのデータを取得
        $data = DB::table('times')
            ->where('user_id', Auth::user()['id'])
            ->whereNotNull('punch_in')
            ->whereNull('punch_out')
            ->whereDate('date', '<=', $today)
            ->get()
            ->first();

        // 勤務開始打刻の値がないならば、エラーを表示させて打刻ページに戻す
        if ($data === null) {
            $request->session()->flash('error_message', '勤務開始打刻をしていないため休憩開始出来ません');
            return redirect(route('index'));
        }

        // 休憩開始ボタンのみの場合を取得
        $data4 = DB::table('rests')
            ->where('time_id', $data->id)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->get()
            ->first();

        // 休憩開始ボタンのみを取得したならば、エラーを表示させて打刻ページに戻す
        if ($data4) {
            $request->session()->flash('error_message', '既に休憩開始ボタンを押しているため休憩開始出来ません');
            return redirect(route('index'));
        }
        // 休憩開始ボタンを押せる条件
        // 勤務開始のみがある場合と休憩が終了している場合

        // 休憩開始ボタンを押せない条件
        // 今日の勤務開始がないまたは勤務終了がある場合、リダイレクト

        // 実装する場合の処理
        // //今日の休憩開始、勤務終了を取得した場合where(カラムを指定?）と勤務開始を取得できなかった場合リダイレクトする　それ以外の場合何度も打てるように

        // 1日で何度も休憩が可能　todayを変える？→結果　だめ

        // 休憩開始データの登録処理
        DB::table('rests')->insert(
            [
                'user_id' => Auth::user()['id'], // 現在認証されているユーザーIDの取得
                'date' => Carbon::now(),
                'break_start' => Carbon::now(),
                'time_id' => $data->id
            ]
        );
        // 処理成功のメッセージをセット
        $request->session()->flash('success_message', '休憩開始しました');
        return redirect(route('index'));
    }

    // 休憩終了処理
    public function breakEnd(Request $request)
    {
        $today = new Carbon('today');

        // 出勤中のデータが存在するか
        $times_data = DB::table('times')
            ->where('user_id', Auth::user()['id'])
            ->whereNotNull('punch_in') // 出勤打刻済み
            ->whereNull('punch_out') // 退勤打刻前
            ->whereDate('date', '<=', $today)
            ->get()
            ->first();
        // 出勤中でなければ休憩終了できない
        if ($times_data === null) {
            $request->session()->flash('error_message', '出勤開始打刻をしていないため休憩終了出来ません');
            return redirect(route('index'));
        }

        // 休憩開始ボタンのみの場合を取得
        $rests_data = DB::table('rests')
            ->where('time_id', $times_data->id)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->get()
            ->first();
        // 休憩開始ボタンの値がないならば、エラーを表示させて打刻ページに戻す
        if ($rests_data === null) {
            $request->session()->flash('error_message', '休憩開始打刻をしていないため休憩終了出来ません');
            return redirect(route('index'));
        }

        // 休憩終了データの更新処理
        DB::table('rests')
            ->where('id', $rests_data->id)
            ->update(['break_end' => Carbon::now()]);
        // 処理成功のメッセージをセット
        $request->session()->flash('success_message', '休憩終了しました');
        return redirect(route('index'));
    }

    //日付別勤怠ページ
    public function attendance(Request $request)
    {
        $user = User::select([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 出勤データの取得処理
        $times_data = DB::table('times')
            //timesテーブルのtimes.user_idとusersテーブルのusers.idをくっつける
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->get();
        $times_data = json_decode(json_encode($times_data), true);
        $times_data = array_column($times_data, null, 'id');
        foreach ($times_data as $data) {
            if ($data['punch_out']) {
                $from = strtotime($data['punch_in']);
                $to   = strtotime($data['punch_out']);
                $times_data[$data['id']]['work_time'] = $this->time_diff($from, $to);
            }
            $times_data[$data['id']]['rest_time'] = '00:00:00';
        }
        // dd($times_data);
        // 休憩開始データの取得処理
        $rests_data = DB::table('rests')
            ->join('times', 'rests.time_id', '=', 'times.id')
            ->get();

        foreach ($rests_data as $key => $rest) {
            if (array_key_exists($rest->time_id, $times_data)) {
                if (!empty($rest->break_start) && !empty($rest->break_end)) {
                    $from = strtotime($rest->break_start);
                    $to   = strtotime($rest->break_end);
                    $rest_time_tmp = $times_data[$rest->time_id]['rest_time'];
                    $rest_time = $this->time_diff($from, $to);
                    $times_data[$rest->time_id]['rest_time'] = $this->time_plus($this->hour_to_sec($rest_time_tmp), $this->hour_to_sec($rest_time));
                }
            }
        }
        // すべてのユーザー情報を取得
        // $users = User::get();
        User::get()->toArray();
        // $date1 = DB::table('rests')->paginate(1);
        // $date = Rests::paginate(1);

        // 出勤データの取得処理
        $times_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->paginate(5);


        return view('auth.attendance', compact('times_data', 'users'));



        // return view('auth.attendance')->with(['users' => $users]);
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

    //ユーザーごとの勤怠表が見れるページ
    public function attendanceeachuser(Request $request)
    {
        $user = User::select([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 出勤データの取得処理
        $times_data = DB::table('times')
            ->where('user_id', Auth::user()['id'])
            ->get();
        $times_data = json_decode(json_encode($times_data), true);
        foreach ($times_data as $key => $data) {
            if ($data['punch_out']) {
                $from = strtotime($data['punch_in']);
                $to   = strtotime($data['punch_out']);
                $times_data[$key]['work_time'] = $this->time_diff($from, $to);
            }
        }

        // 休憩開始データの取得処理
        DB::table('rests')->select(
            [
                'user_id' => Auth::user()['id'], // 現在認証されているユーザーIDの取得
                'date' => Carbon::now(),
                'break_start' => Carbon::now()
            ]
        );

        return view('auth.attendance', compact('times_data'));
    }


    private function time_diff2($time_from, $time_to)
    {
        $time = $time_to - $time_from;
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


// public function check(Request $request)
    // {
    // $text = ['text' => 'ログインして下さい。'];
    // return view('auth', $text);
    // }

    // public function checkUser(Request $request)
    // {
    // $email = $request->email;
    // $password = $request->password;
    // if (Auth::attempt(['email' => $email,
    //         'password' => $password])) {
    //     $text =   Auth::user()->name . 'さんがログインしました';
    // } else {
    //     $text = 'ログインに失敗しました';
    // }
    // return view('auth', ['text' => $text]);
    // }