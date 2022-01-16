<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            $request->session()->flash('error_message', '既に勤務を開始しているため勤務開始出来ません');
            return redirect(route('index'));
        }

        DB::table('times')->insert(
            [
                'user_id' => Auth::user()['id'],
                'date' => Carbon::now(),
                'punch_in' => Carbon::now()
            ]
        );
        $request->session()->flash('success_message', '勤務開始しました');
        return redirect(route('index'));
    }

    // 退勤処理
    public function punchOut(Request $request)
    {
        $today = new Carbon('today');
        $times_data = DB::table('times')
            ->where('user_id', Auth::user()['id'])
            ->whereNotNull('punch_in')
            ->whereNull('punch_out')
            ->whereDate('date', '<=', $today)
            ->get()
            ->first();
        if ($times_data === null) {
            $request->session()->flash('error_message', '今日またはそれ以前の勤務開始打刻がないため勤務終了出来ません');
            return redirect(route('index'));
        }

        $rests_data = DB::table('rests')
            ->where('time_id', $times_data->id)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->get()
            ->first();
        if ($rests_data) {
            $request->session()->flash('error_message', '休憩終了打刻をしていないため勤務終了出来ません');
            return redirect(route('index'));
        }

        $punch_out_time = Carbon::now();
        $work_time = $this->time_diff(strtotime($times_data->punch_in), strtotime($punch_out_time));

        DB::table('times')
            ->where('id', $times_data->id)
            ->update([
                'punch_out' => $punch_out_time,
                'work_time' => $work_time,
            ]);
        $request->session()->flash('success_message', '勤務終了しました');
        return redirect(route('index'));
    }

    // 休憩開始処理
    public function breakStart(Request $request)
    {
        $today = new Carbon('today');
        $data = DB::table('times')
            ->where('user_id', Auth::user()['id'])
            ->whereNotNull('punch_in')
            ->whereNull('punch_out')
            ->whereDate('date', '<=', $today)
            ->get()
            ->first();

        if ($data === null) {
            $request->session()->flash('error_message', '勤務開始打刻をしていないため休憩開始出来ません');
            return redirect(route('index'));
        }

        $data4 = DB::table('rests')
            ->where('time_id', $data->id)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->get()
            ->first();

        if ($data4) {
            $request->session()->flash('error_message', '既に休憩開始ボタンを押しているため休憩開始出来ません');
            return redirect(route('index'));
        }

        DB::table('rests')->insert(
            [
                'user_id' => Auth::user()['id'], 
                'date' => Carbon::now(),
                'break_start' => Carbon::now(),
                'time_id' => $data->id
            ]
        );

        $request->session()->flash('success_message', '休憩開始しました');
        return redirect(route('index'));
    }

    // 休憩終了処理
    public function breakEnd(Request $request)
    {
        $today = new Carbon('today');
        $times_data = DB::table('times')
            ->where('user_id', Auth::user()['id'])
            ->whereNotNull('punch_in')
            ->whereNull('punch_out')
            ->whereDate('date', '<=', $today)
            ->get()
            ->first();

        if ($times_data === null) {
            $request->session()->flash('error_message', '出勤開始打刻をしていないため休憩終了出来ません');
            return redirect(route('index'));
        }

        $rests_data = DB::table('rests')
            ->where('time_id', $times_data->id)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->get()
            ->first();

        if ($rests_data === null) {
            $request->session()->flash('error_message', '休憩開始打刻をしていないため休憩終了出来ません');
            return redirect(route('index'));
        }

        DB::table('rests')
            ->where('id', $rests_data->id)
            ->update(['break_end' => Carbon::now()]);
        $request->session()->flash('success_message', '休憩終了しました');
        return redirect(route('index'));
    }

    //日付別勤怠ページ
    public function attendance(Request $request)
    {
        $first_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->whereNOTNull('punch_in')
            ->get();

        if ($first_data === null) {
            $request->session()->flash('error_message', '打刻データがありません');
        }

        $all_date = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->select('date')
            ->get()
            ->all();

        if ($all_date=== null) {
            $request->session()->flash('error_message', '打刻データがありません');
        }

        $latest_punch_in_date = max($all_date);

        $times_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->whereDate('times.date', $latest_punch_in_date->date)
            ->select('times.*', 'users.name')
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
                }
                else {
                    $rest_time_tmp = '';
                }
                $rest_time = $this->time_diff($from, $to);
                $calclate_rest_data[$rest->time_id] = $this->time_plus($this->hour_to_sec($rest_time_tmp), $this->hour_to_sec($rest_time));
            }
        }
        $param = [
            'today' => $latest_punch_in_date->date,
            'times_data' => $times_data,
            'rest_data' => $calclate_rest_data
        ];

        return view('auth.attendance', $param);
    }

    //日付別勤怠ページ次の日
    public function attendanceNextdate(Request $request)
    {
        $first_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->whereNOTNull('punch_in')
            ->get();

        if ($first_data === null) {
            $request->session()->flash('error_message', '打刻データがありません');
        }

        $tommorow = date('Y-m-d', strtotime($request->date . ' +1 day'));

        $times_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->whereDate('times.date', $tommorow)
            ->select('times.*', 'users.name')
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
        $param = [
            'today' => $tommorow,
            'times_data' => $times_data,
            'rest_data' => $calclate_rest_data
        ];

        return view('auth.attendance', $param);
    }

    //日付別勤怠ページ前の日
    public function attendanceBeforedate(Request $request)
    {
        $first_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->whereNOTNull('punch_in')
            ->get();

        if ($first_data === null) {
            $request->session()->flash('error_message', '打刻データがありません');
        }

        $yesterday = date('Y-m-d', strtotime($request->date . ' -1 day'));
        $times_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->whereDate('times.date', $yesterday)
            ->select('times.*', 'users.name')
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
                }
                else {
                    $rest_time_tmp = '';
                }
                $rest_time = $this->time_diff($from, $to);
                $calclate_rest_data[$rest->time_id] = $this->time_plus($this->hour_to_sec($rest_time_tmp), $this->hour_to_sec($rest_time));
            }
        }
        $param = [
            'today' => $yesterday,
            'times_data' => $times_data,
            'rest_data' => $calclate_rest_data
        ];

        return view('auth.attendance', $param);
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
