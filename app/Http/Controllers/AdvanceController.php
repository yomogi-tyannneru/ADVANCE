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
    public function timesget(Request $request)
    {
        $this->validate($request, Advance::$rules);
        $param = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'punch_in' => $request->punch_in,
            'punch_out' => $request->punch_out,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
        ];
        
        DB::table(‘times’)->insert(
        [‘punch_in’ => Carbon::now(), ‘punch_out’ => Carbon::now()]
        );
        return redirect('/attendance');
    }
    public function restsget(Request $request)
    {
        $this->validate($request, Advance::$rules);
        $param = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'punch_in' => $request->punch_in,
            'punch_out' => $request->punch_out,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
        ];
        DB::insert('insert into rests (break_start, break_end) values (:break_start, :break_end)', $param);
        return views(‘index’);
    }

    //会員登録ページ
    public function register(Request $request)
    {
        $this->validate($request, Advance::$rules);
        $param = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'punch_in' => $request->punch_in,
            'punch_out' => $request->punch_out,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
        ];
        DB::insert('insert into workers (name, email, password) values (:name, :email, :password)', $param);
        return view(‘index’);
    }
    //ログインページ
    public function sending(Request $request)
    {
        $this->validate($request, Advance::$rules);
        $param = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'punch_in' => $request->punch_in,
            'punch_out' => $request->punch_out,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
        ];
        DB::insert('insert into workers (name, email, password) values (:name, :email, :password)', $param);
        return redirect('/');
        
    }
    //日付別勤怠ページ
    public function management(Request $request)
    {
        $this->validate($request, Advance::$rules);
        $param = [
            'name' => $request->name,
            'mail' => $request->mail,
            'password' => $request->password,
            'punch_in' => $request->punch_in,
            'punch_out' => $request->punch_out,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
        ];
        DB::insert('insert into workers (name, mail, password, punch_in, punch_out, break_start, break_end) values (:name, :mail, :password, :punch_in, :punch_out, :break_start, :break_end)', $param);
        return redirect('/');
        DB::insert('insert into times (name, mail, password, punch_in, punch_out, break_start, break_end) values (:name, :mail, :password, :punch_in, :punch_out, :break_start, :break_end)', $param);
        return redirect('/');
    }
    public function check(Request $request)
    {
    $text = ['text' => 'ログインして下さい。'];
    return view('auth', $text);
    }

    public function checkUser(Request $request)
    {
    $email = $request->email;
    $password = $request->password;
    if (Auth::attempt(['email' => $email,
            'password' => $password])) {
        $text =   Auth::user()->name . 'さんがログインしました';
    } else {
        $text = 'ログインに失敗しました';
    }
    return view('auth', ['text' => $text]);
    }

}
