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
        // ログインしているユーザーのデータを取得する
        // ログインしていることが前提
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
            // 1個目のデータ、これがないと $dataがあるか判別できない
            ->first();

        if ($data) {
            // すでに出勤ボタンを押しているためエラーを表示させて打刻ページに戻す（エラーメッセージをセットする）
            $request->session()->flash('error_message', '既に勤務を開始しているため勤務開始出来ません');
            // ルーティングのname('index');に飛んで、ルーティングでコントローラーのindexメソッドの処理が行われる、つまり、打刻ページに戻る
            return redirect(route('index'));
        }

        // 出勤データの登録処理　ifに該当しなかった場合はこの処理が行われる
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
        // 勤怠開始のデータがない場合の表示は打刻データがありませんと表示
        $first_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->whereNOTNull('punch_in')
            ->get()
            ->first();
        
        if ($first_data === null) {
            $request->session()->flash('error_message', '打刻データがありません');
        }

        // $punch_in = DB::table('times')
        //     ->leftJoin('users', 'users.id', '=', 'times.user_id')
        //     ->select('times.*', 'users.name')
        //     ->where('punch_in');
        $score1 = '値1';
        $score2 = '値2';

        $punch_in = ['値1', '値2'];

        $latest_punch_in_data = max('$punch_in');


        // 勤怠開始のデータがあった場合、最初に表示される日付は最後に打刻した日が表示される
        $first_data = DB::table('times')
        ->leftJoin('users', 'users.id', '=', 'times.user_id')
        ->select('times.*', 'users.name')
        ->whereNull('punch_in')
        ->max('punch_in') 
        ->get()
        ->first();

        if ($first_data === null) {
        }

        // 出勤データの取得処理　1日分にするため？
        $times_data = DB::table('times')
            //timesテーブルのuser_idとusersテーブルのusers.idをくっつける
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            // ->whereDate('times.date', '2021-11-22') // 日付を指定する場合
            //timesテーブルの全データとusersテーブルのnameを取得
            //.は、〜の〜の中のという意味
            ->select('times.*', 'users.name')
            // ->selectRaw("TIMEDIFF('CONCAT(times.date, ' ', times.punch_out))','CONCAT(times.date, ' ', times.punch_in)') as date_diff")
            // ->selectRaw("CONCAT(times.date, ' ', times.punch_out) as timespunch_out")
            // ->selectRaw("CONCAT(times.date, ' ', times.punch_in) as timespunch_in")
            // ->selectRaw("TIMEDIFF('2021-11-13 10:22:57','2021-11-13 09:26:17') as date_diff")
            // ->groupBy('times.date')
            // ->get();
            ->paginate(5);

        // dd($times_data);
        //勤務時間の計算処理
        // $times_data = json_decode(json_encode($times_data), true);
        // $times_data = array_column($times_data, null, 'id');
        // foreach ($times_data as $data) {
        //     if ($data['punch_out']) {
        //         $from = strtotime($data['punch_in']);
        //         $to   = strtotime($data['punch_out']);
        //         $times_data[$data['id']]['work_time'] = $this->time_diff($from, $to);
        //     }
        //     //初期値0
        //     $times_data[$data['id']]['rest_time'] = '00:00:00';
        // }

        // 休憩開始データの取得処理
        $rests_data = DB::table('rests')
            ->join('times', 'rests.time_id', '=', 'times.id')
            ->get();
        // $rests_data配列の要素$key => $restの繰り返し次の処理をする 全データの中の1つのtimesテーブルのid　つまり、一日分
        // $restとは？　一日に何度もある休憩を足すための繰り返し計算
        //foreach (配列 as 要素 => 値、中身)
        //配列として指定した時点でその要素は0から順に増えていくものと決まっている
        // foreach ($rests_data as $key => $rest) {
        //     // もし休憩テーブルのtime_idがあるならば(勤務開始に値があるならば)
        //     // 休憩テーブルがあるということは、勤怠テーブルが有るということなのでifにしてあるが、ほぼtrueでfalseになることはない
        //     if (array_key_exists($rest->time_id, $times_data)) {
        //         // もし休憩開始と休憩終了が空ではないならば
        //         if (!empty($rest->break_start) && !empty($rest->break_end)) {
        //             $from = strtotime($rest->break_start);
        //             $to   = strtotime($rest->break_end);
        //             //timesテーブルの1つを選んだそれのrest_time　1日分の休憩時間　一時保存tmp　一周目は初期値
        //             $rest_time_tmp = $times_data[$rest->time_id]['rest_time'];
        //             //計算した休憩時間
        //             $rest_time = $this->time_diff($from, $to);
        //             //時間を秒に直して、一時保存したものと現在足したものの2つを足す
        //             $times_data[$rest->time_id]['rest_time'] = $this->time_plus($this->hour_to_sec($rest_time_tmp), $this->hour_to_sec($rest_time));
        //         }
        //     }
        // }
        return view('auth.attendance', compact('times_data'));
        // return view('auth.attendance')->with(['users' => $users]);
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


