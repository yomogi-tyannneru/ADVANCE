<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use WithoutMiddleWare;


class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        // ログイン画面に来たとき
        $response = $this->get('/login');

        // 返えされたレスポンスに指定するHTTPステータスコードが200である
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        // テストの際フェイクのデータを作成
        $user = User::factory()->create();
        // デバッグ「de+bug」とは、コンピュータプログラムや電気機器中のバグ・欠陥を特定して取り除き、動作を仕様通りのものとするための作業である。
        Log::debug($user);

        // ミドルウェアなしでemailとpasswordでログインされたとき
        $response= $this->withoutMiddleware()->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // $this->assertAuthenticated();
        // homeにリダイレクトする
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();
        // 間違ったパスワードでログインした際は、
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
        // ユーザーが認証されない
        $this->assertGuest();
    }
}
