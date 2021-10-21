<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Standardlasttest;

class AdvanceController extends Controller
{
    //お問い合わせ
    public function index()
    {
        $items = DB::select('select * from times');
        return view('index');
    }
    //内容確認
    public function confirmation(Request $request)
    {
        $types = Contact::$types;
        $genders = Contact::$genders;
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
    //送信後
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
    //管理システム
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
}