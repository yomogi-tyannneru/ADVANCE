<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Advance;
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
            // すでに出勤ボタンを押しているためエラーを表示させて打刻ページに戻す（エラーメッセージをセットする）
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
        // ...

        return redirect(route('index'));
    }

    // 退勤処理
    public function punchOut(Request $request)
    {
        $today = new Carbon('today');
        $data = DB::table('times')
            ->where('user_id', Auth::user()['id'])
            ->whereDate('date', $today)
            ->whereNull('punch_out')
            ->get()
            ->first();
            // 休憩開始後、休憩終了前に退勤が出来ないように実装つまり退勤ボタンも変更する
            // 日付が変わると退勤できない　昨日の出勤も感知するように　あり退勤されていない場合
        if (!$data) {
            // 退勤処理をする条件が整っていないためエラーを表示させて打刻ページに戻す（エラーメッセージをセットする）
            return redirect(route('index'));
        }

        // 退勤データの更新処理
        DB::table('times')
            ->where('id', $data->id)
            ->update(['punch_out' => Carbon::now()]);
        // 処理成功のメッセージをセット
        // ...dash~で確認

        return redirect(route('index'));
    }

    // 休憩開始処理
    public function breakStart(Request $request)
    {
        $now = new Carbon('now');//今日の休憩開始、勤務終了を取得
            // ->where('break_start')
            // ->where('punch_out');
        $data2 = DB::table('rests')
            ->where('user_id', Auth::user()['id'])
            ->whereDate('date', $now)
            ->get()
            ->first();
        

            // 休憩開始ボタンを押せる条件
            // 勤務開始のみがある場合と休憩が終了している場合

            // 休憩開始ボタンを押せない条件
            // 今日の勤務開始がないまたは勤務終了がある場合、リダイレクト

            // 実装する場合の処理
            // //今日の休憩開始、勤務終了を取得した場合where(カラムを指定?）と勤務開始を取得できなかった場合リダイレクトする　それ以外の場合何度も打てるように
        

            // 1日で何度も休憩が可能　todayを変える？→結果　だめ


        if ($data2) {
            // すでに休憩開始ボタンを押しているためエラーを表示させて打刻ページに戻す（エラーメッセージをセットする）
            
            return redirect(route('index'));
        }

        // 休憩開始データの登録処理
        DB::table('rests')->insert(
            [
                'user_id' => Auth::user()['id'],// 現在認証されているユーザーIDの取得
                'date' => Carbon::now(),
                'break_start' => Carbon::now()
            ]
        );
        // 処理成功のメッセージをセット
        // ...

        return redirect(route('index'));
    }

    // 休憩終了処理
    public function breakEnd(Request $request)
    {
        $today = new Carbon('today');
        $data = DB::table('rests')
            ->where('user_id', Auth::user()['id'])
            ->whereDate('date', $today)
            ->whereNull('break_end')
            ->get()
            ->first();
        if (!$data) {
            // 休憩終了処理をする条件が整っていないためエラーを表示させて打刻ページに戻す（エラーメッセージをセットする）
            return redirect(route('index'));
        }

        // 休憩終了データの更新処理
        DB::table('rests')
            ->where('id', $data->id)
            ->update(['break_end' => Carbon::now()]);
        // 処理成功のメッセージをセット
        // ...

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
        DB::table('times')->select(
            [
                'user_id' => Auth::user()['id'],
                'date' => Carbon::now(),
                'punch_in' => Carbon::now()
            ]
        );

        // 退勤データの取得処理
        DB::table('times')
            ->where('id', $data->id)
            ->select(['punch_out' => Carbon::now()]);

        // 休憩開始データの取得処理
        DB::table('rests')->select(
            [
                'user_id' => Auth::user()['id'],// 現在認証されているユーザーIDの取得
                'date' => Carbon::now(),
                'break_start' => Carbon::now()
            ]
        );

        // 休憩終了データの取得処理
        DB::table('rests')
            ->where('id', $data->id)
            ->select(['break_end' => Carbon::now()]);


        // $this->validate($request, Advance::$rules);
        // $param = [
        //     'name' => $request->name,
        //     'email' => $request->mail,
        //     'password' => $request->password,
        //     'punch_in' => $request->punch_in,
        //     'punch_out' => $request->punch_out,
        //     'break_start' => $request->break_start,
        //     'break_end' => $request->break_end,
        // ];
        // DB::select('insert into users (name, mail, password) values (:name, :email, :password)', $param);
        // return redirect('/');
        // DB::select('insert into times (name, mail, password, punch_in, punch_out, break_start, break_end) values (:name, :mail, :password, :punch_in, :punch_out, :break_start, :break_end)', $param);
        // DB::select('insert into times (name, mail, password, punch_in, punch_out, break_start, break_end) values (:name, :mail, :password, :punch_in, :punch_out, :break_start, :break_end)', $param);
        // return redirect(route('attendance'));
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

}