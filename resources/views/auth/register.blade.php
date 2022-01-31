<x-guest-layout>
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

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />
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
</x-guest-layout>