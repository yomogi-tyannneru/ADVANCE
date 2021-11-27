<x-guest-layout>
    <style>
        a {
            color: #000000;
            font-weight: bold;
            font-size: 100%;
            text-decoration: none;
        }

        body {
            color: #000000;
            line-height: 1.7;
        }

        .flex__item {
            display: flex;
            justify-content: space-between;
        }

        /*--------------------- header--------------------- */

        .header {
            position: sticky;
            top: 0px;
            z-index: 1;
            height: 70px;
            background-color: #fff;
            padding: 20px 0;
        }

        .header-title {
            font-size: 200%;
            padding: 0px 35px;
        }

        .header__nav {
            margin-right: 60px;
        }

        .header__nav-list-link:hover {
            filter: opacity(70%);
            cursor: pointer;
        }

        /*--------------------- stamp--------------------- */
        .service {
            padding: 20px 160px 300px;
            background-color: #f1eeee;
        }

        .service-title {
            font-size: 24px;
            text-align: center;
            margin-top: 60px;
            margin-bottom: 30px;
            font-weight: bolder;
        }

        .service_png-position {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            width: 100%;
        }

        .service_png-positiondiv {
            width: 40%;
        }

        .form {
            margin: 20px auto;
        }

        .form-item {
            padding: 5px 0;
            width: 100%;

            padding: 15px 0px;
        }

        .form-btn {
            border-radius: 6px;
            border: 2px solid #929292;
            background-color: #f1eeee;
            height: 50px;
            width: 100%;
            display: block;
            font-size: 22px;
            margin: 0px 10px;
            text-align: left;
            color: rgb(165, 164, 164);
            padding-left: 20px;
        }

        .form-btn1 {
            border-radius: 6px;
            border: 2px solid #929292;
            background-color: #043cf1;
            height: 50px;
            width: 100%;
            display: block;
            font-size: 16px;
            margin: 0px 10px;
            text-align: center;
            color: #fff;
            padding-left: 20px;
        }

        .form-btn1:hover {
            filter: opacity(70%);
            cursor: pointer;
        }

        .text {
            text-align: center;
            color: rgb(164, 164, 165);
        }

        .login_btn {
            display: flex;
            justify-content: center;
        }

        .service-title2 {
            font-size: 16px;
            text-align: center;
            font-weight: bolder;
            padding: 10px;
        }
    </style>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <!-- <form method="POST" action="{{ route('login') }}">
        @csrf-->

    <!-- Email Address -->
    <!-- <div>
            <x-label for="email" :value="__('Email')" />

            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        </div>-->

    <!-- Password -->
    <!-- <div class="mt-4">
            <x-label for="password" :value="__('Password')" />

            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
        </div> -->

    <!-- Remember Me -->
    <!-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div> -->

    <!-- <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-button class="ml-3">
                {{ __('Log in') }}
            </x-button>
        </div>
    </form> -->




    <body>
        <header class="header flex__item">
            <a class="header__nav-list-link header-title">Atte</a>
        </header>
        <div class="service">
            <p class="service-title">ログイン</p>
            <div class="service_png-position">
                <div class="service_png-positiondiv">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Email Address -->
                        <div class="form-item">
                            <x-input id="email" type="email" name="email" :value="old('email')" placeholder="メールアドレス" class="form-btn" required autofocus />
                            <!-- なぜvalueがフロントに表示されないのか？ -->
                        </div>

                        <!-- Password -->
                        <div class="form-item">

                            <x-input id="password" class="form-btn" type="password" name="password" required autocomplete="current-password" placeholder="パスワード" />
                        </div>

                        <!-- <div class="form-item">
                            <input type="submit" name="email" value="email" placeholder="メールアドレス" class="form-btn">

                        </div>
                        <div class="form-item">
                            <input type="submit" name="password" value="password" placeholder="パスワード" class="form-btn">
                        </div> -->
                        <div class="form-item">
                            <x-button value="ログイン" placeholder="ログイン" class="form-btn1">
                                {{ __('ログイン') }}
                                <!-- {{ __('Log in') }} なぜログインできる？ログイン機能はボタンについてる？-->
                            </x-button>
                        </div>
                    </form>
                    <p class=" text">アカウントをお持ちでない方はこちらから</p>
                    <a href="/register" class="login_btn" style="color:blue;">会員登録</a>
                </div>
            </div>
        </div>
        <p class="service-title2">Atte,inc.</p>
    </body>

    </html>
</x-guest-layout>