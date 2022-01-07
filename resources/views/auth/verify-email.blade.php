<x-guest-layout>
    <x-auth-card>
        <style>
            .resend_email_message {
                padding-top: 20px;
            }

            .button_layout {
                display: flex;
                justify-content: space-between;
                padding-top: 20px;
            }

            .resend_email_button_layout {
                padding-bottom: 10px;
            }

            .resend_email_button {
                background: black;
                border-radius: 6px;
            }

            .resend_email_button:hover {
                filter: opacity(70%);
                cursor: pointer;
            }

            .logout_button {
                background-color: transparent;
                border-top: none;
                border-left: none;
                border-right: none;
                cursor: pointer;
                outline: none;
                padding: 0;
                appearance: none;
                border-color: #C0C0C0;
                border-width: 1px;
                padding-top: 10px;
            }

            .logout_button:hover {
                filter: opacity(70%);
                cursor: pointer;
            }
        </style>
        <div class="">
            {{ __('サインアップしていただきありがとうございます。始める前に、メールで送信したリンクをクリックして、メールアドレスを確認していただけますか？メールが届かない場合は、確認メールを再送をクリックしてください別のメールをお送りします。') }}
        </div>

        @if (session('status') == 'verification-link-sent')
        <div class="resend_email_message">
            {{ __('登録時に指定したメールアドレスに新しい確認リンクが送信されました。') }}
        </div>
        @endif

        <div class="button_layout">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div class="resend_email_button_layout">
                    <x-button class="resend_email_button ">
                        {{ __('確認メールを再送') }}
                    </x-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="logout_button">
                    {{ __('ログアウト') }}
                </button>
            </form>
        </div>
    </x-auth-card>
</x-guest-layout>