<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <body>
        <header class="header flex__item">
            <a class="header__nav-list-link header-title">Atte</a>
        </header>
        <div class="service2">
            <p class="service-title">ログイン</p>
            <div class="service_png-position">
                <div class="service_png-positiondiv">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Email Address -->
                        <div class="form-item">
                            <x-input id="email" type="email" name="email" :value="old('email')" placeholder="メールアドレス" class="form-btn" required autofocus />
                        </div>

                        <!-- Password -->
                        <div class="form-item">

                            <x-input id="password" class="form-btn" type="password" name="password" required autocomplete="current-password" placeholder="パスワード" />
                        </div>

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />

                        <div class="form-item">
                            <x-button value="ログイン" placeholder="ログイン" class="form-btn1">
                                {{ __('ログイン') }}
                            </x-button>
                        </div>
                    </form>
                    <p class="text">アカウントをお持ちでない方はこちらから</p>
                    <a href="/register" class="login_btn" style="color:blue;">会員登録</a>
                </div>
            </div>
        </div>
        <p class="service-title2">Atte,inc.</p>
    </body>
</x-guest-layout>