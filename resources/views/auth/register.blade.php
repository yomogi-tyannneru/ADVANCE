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
            padding: 20px 160px 130px;
            background-color: #f1eeee;
        }

        .service-title {
            font-size: 24px;
            text-align: center;
            margin-top: 90px;
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



    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <!-- <form method="POST" action="">
        @csrf

        Name
        <div>
            <x-label for="name" :value="__('Name')" />

            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
        </div>


        Email Address
        <div class="mt-4">
            <x-label for="email" :value="__('Email')" />

            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
        </div>

        Password
        <div class="mt-4">
            <x-label for="password" :value="__('Password')" />

            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
        </div>

        Confirm Password
        <div class="mt-4">
            <x-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <p>アカウントをお持ちの方はこちら</p>

            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('ログイン') }}
            </a>

            <x-button class="ml-4">
                {{ __('会員登録') }}
            </x-button>
        </div>
    </form> -->


    <body>
        <header class="header flex__item">
            <a class="header__nav-list-link header-title">Atte</a>
        </header>
        <div class="service">
            <p class="service-title">会員登録</p>
            <div class="service_png-position">
                <div class="service_png-positiondiv">
                    <form method="POST" action="">
                        @csrf

                        <!-- Name -->
                        <div class="form-item">
                            <x-input id="name" class="form-btn" type="text" name="name" :value="old('name')" required autofocus placeholder="名前" />
                        </div>

                        <!-- Email Address -->
                        <div class="form-item">
                            <x-input id="email" class="form-btn" type="email" name="email" :value="old('email')" required placeholder="メールアドレス" />
                        </div>

                        <!-- Password -->
                        <div class="form-item">
                            <x-input id="password" class="form-btn" type="password" name="password" required autocomplete="new-password" placeholder="パスワード" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-item">
                            <x-input id="password_confirmation" class="form-btn" type="password" name="password_confirmation" required placeholder="確認用パスワード" />
                        </div>

                        <div class="form-item">
                            <x-button class="form-btn1">
                                {{ __('会員登録') }}
                            </x-button>
                            <p class="text">アカウントをお持ちの方はこちら</p>
                        </div>

                        <a class="login_btn" style="color:blue;" href="{{ route('login') }}">
                            {{ __('ログイン') }}
                        </a>

                    </form>
                </div>
            </div>
        </div>
        <p class="service-title2">Atte,inc.</p>
    </body>

    </html>

</x-guest-layout>