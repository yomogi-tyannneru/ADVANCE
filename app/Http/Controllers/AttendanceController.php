<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Time;
use App\Common\validation;

class AttendanceController extends Controller
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
        $hasError = validation::cannotpunchInforalreadypunchIn($request);
        if ($hasError) {
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
        $user = Auth::user();
        $today = new Carbon('today');
        $punch_in_data = User::find($user->id)->times
            ->whereNotNull('punch_in')
            ->whereNull('punch_out')
            ->where('date', '<=', $today)
            ->first();
        $hasError = validation::cannotpunchOutfornotpunchIn($request);
        if ($hasError) {
            return redirect(route('index'));
        }

        $hasError = validation::cannotpunchOutfornotbreakEnd($request);
        if ($hasError) {
            return redirect(route('index'));
        }

        $punch_out_time = Carbon::now();
        $work_time = $this->time_diff(strtotime($punch_in_data->punch_in), strtotime($punch_out_time));

        DB::table('times')
            ->where('id', $punch_in_data->id)
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
        $user = Auth::user();
        $today = new Carbon('today');
        $punch_in_data = User::find($user->id)->times
            ->whereNotNull('punch_in')
            ->whereNull('punch_out')
            ->where('date', '<=', $today)
            ->first();

        $hasError = validation::cannotbreakStartfornotpunchIn($request);
        if ($hasError) {
            return redirect(route('index'));
        }

        $hasError = validation::cannotbreakStartforalreadybreakStart($request);
        if ($hasError) {
            return redirect(route('index'));
        }

        DB::table('rests')->insert(
            [
                'user_id' => Auth::user()['id'],
                'date' => Carbon::now(),
                'break_start' => Carbon::now(),
                'time_id' => $punch_in_data->id
            ]
        );

        $request->session()->flash('success_message', '休憩開始しました');
        return redirect(route('index'));
    }

    // 休憩終了処理
    public function breakEnd(Request $request)
    {
        $user = Auth::user();
        $today = new Carbon('today');
        $punch_in_data = User::find($user->id)->times
            ->whereNotNull('punch_in')
            ->whereNull('punch_out')
            ->where('date', '<=', $today)
            ->first();

        $hasError = validation::cannotbreakfornotpunchIn($request);
        if ($hasError) {
            return redirect(route('index'));
        }

        $break_start_data = Time::find($punch_in_data->id)->rests
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->first();

        $hasError = validation::cannotbreakEndfornotbreakStart($request);
        if ($hasError) {
            return redirect(route('index'));
        }

        DB::table('rests')
            ->where('id', $break_start_data->id)
            ->update(['break_end' => Carbon::now()]);
        $request->session()->flash('success_message', '休憩終了しました');
        return redirect(route('index'));
    }

    //日付別勤怠ページ
    public function attendance(Request $request)
    {
        $all_punch_in_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->whereNOTNull('punch_in')
            ->get();

        if ($all_punch_in_data === null) {
            $request->session()->flash('error_message', '打刻データがありません');
        }

        $all_date = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->select('date')
            ->get()
            ->all();

        $today_kari
            = [
                'today' => '日付',
                'times_data' => [],
                'rest_data' => []
            ];

        if (is_array($all_date) && empty($all_date)) {
            $request->session()->flash('error_message', '打刻データがありません');

            return view('auth.attendance', $today_kari);
        }

        $latest_punch_in_date = max($all_date);

        $latest_time_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->whereDate('times.date', $latest_punch_in_date->date)
            ->select('times.*', 'users.name')
            ->paginate(5);

        $break_start_data = DB::table('rests')
            ->join('times', 'rests.time_id', '=', 'times.id')
            ->get();

        $calclate_rest_data = [];
        foreach ($break_start_data as $key => $rest) {
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
            'today' => $latest_punch_in_date->date,
            'times_data' => $latest_time_data,
            'rest_data' => $calclate_rest_data
        ];

        return view('auth.attendance', $param);
    }

    //日付別勤怠ページ次の日
    public function attendanceNextdate(Request $request)
    {
        $all_punch_in_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->whereNOTNull('punch_in')
            ->get();

        if ($all_punch_in_data === null) {
            $request->session()->flash('error_message', '打刻データがありません');
        }

        $tommorow = date('Y-m-d', strtotime($request->date . ' +1 day'));

        $latest_time_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->whereDate('times.date', $tommorow)
            ->select('times.*', 'users.name')
            ->paginate(5);

        $break_start_data = DB::table('rests')
            ->join('times', 'rests.time_id', '=', 'times.id')
            ->get();
        $calclate_rest_data = [];
        foreach ($break_start_data as $key => $rest) {
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
            'times_data' => $latest_time_data,
            'rest_data' => $calclate_rest_data
        ];

        return view('auth.attendance', $param);
    }

    //日付別勤怠ページ前の日
    public function attendanceBeforedate(Request $request)
    {
        $all_punch_in_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->select('times.*', 'users.name')
            ->whereNOTNull('punch_in')
            ->get();

        if ($all_punch_in_data === null) {
            $request->session()->flash('error_message', '打刻データがありません');
        }

        $yesterday = date('Y-m-d', strtotime($request->date . ' -1 day'));
        $latest_time_data = DB::table('times')
            ->leftJoin('users', 'users.id', '=', 'times.user_id')
            ->whereDate('times.date', $yesterday)
            ->select('times.*', 'users.name')
            ->paginate(5);

        $break_start_data = DB::table('rests')
            ->join('times', 'rests.time_id', '=', 'times.id')
            ->get();
        $calclate_rest_data = [];
        foreach ($break_start_data as $key => $rest) {
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
            'today' => $yesterday,
            'times_data' => $latest_time_data,
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

