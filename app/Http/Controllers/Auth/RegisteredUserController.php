<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            // required　リクエストされたか？　必須
            // string　文字か？
            // max:255　最大255文字
            // email　メールアドレスの形かどうか？
            // unique:users　usersテーブルの中に同じemailがあったらダメ
            // confirmed　確認用のフォームがはいっているかどうか？
            // Rules\Password::defaults()　文字数制限
            // VSCODEの機能で記述先に飛べるというのがある
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        // ユーザーの新規登録の処理
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // データーベースに入れてる
        event(new Registered($user));
        // $userをログイン
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
