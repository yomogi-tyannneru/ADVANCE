<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Advance;

class AdvanceController extends Controller
{
    //打刻ページ
    public function index(Request $request)
    {
        $user = Auth::user();
        $items = Author::paginate(5);
        $param = ['items' => $items, 'user' =>$user];
        return view('index', $param);

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
        DB::insert('insert into workers (name, mail, password, punch_in, punch_out, ) values (:name, :mail, :password, :punch_in, :punch_out,)', $param);
        return redirect('/');
        DB::insert('insert into times ( break_start, break_end) values (:break_start, :break_end)', $param);
        return redirect('/');
    }
    //会員登録ページ
    public function confirmation(Request $request)
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
    //ログインページ
    public function sending(Request $request)
    {
        $this->validate($request, Standardlasttest::$rules);
        $param = [
            'fullname' => $request->fullname,
            'gender' => $request->gender,
            'email' => $request->email,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'building_name' => $request->building_name,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
        ];
        DB::insert('insert into contacts (fullname, gender, email, postcode, address, building_name, created_at, updated_at) values (:fullname, :gender, :email, :postcode, :address, :building_name, :created_at, :updated_at)', $param);
        return redirect('/');
    }
    //日付別勤怠ページ
    public function management(Request $request)
    {
        $this->validate($request, Standardlasttest::$rules);
        $param = [
            'fullname' => $request->fullname,
            'gender' => $request->gender,
            'email' => $request->email,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'building_name' => $request->building_name,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
        ];
        DB::insert('insert into contacts (fullname, gender, email, postcode, address, building_name, created_at, updated_at) values (:fullname, :gender, :email, :postcode, :address, :building_name, :created_at, :updated_at)', $param);
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