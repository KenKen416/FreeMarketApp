<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/login';
    private const EMAIL = 'test@example.com';
    private const PASSWORD = 'password';

    private const MSG_EMAIL_REQUIRED = 'メールアドレスを入力してください';
    private const MSG_PASSWORD_REQUIRED = 'パスワードを入力してください';
    private const MSG_INVALID_CREDENTIALS = 'ログイン情報が登録されていません';

    private function base(array $overrides = []): array
    {
        $data = [
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ];
        return array_merge($data, $overrides);
    }
    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_login_shows_error_when_email_is_missing(): void
    {
        $login_data = $this->base(['email' => '']) ?? [];

        $response = $this->followingRedirects()->from(self::URL)
            ->post(self::URL, $login_data);

        $response->assertSee(self::MSG_EMAIL_REQUIRED);
    }
    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_login_shows_error_when_password_is_missing(): void
    {
        $login_data = $this->base(['password' => '']) ?? [];

        $response = $this->followingRedirects()->from(self::URL)
            ->post(self::URL, $login_data);

        $response->assertSee(self::MSG_PASSWORD_REQUIRED);
    }
    // 入力情報が間違っている場合、バリデーションメッセージが表示される
    public function test_login_shows_error_when_credentials_are_invalid(): void
    {
        $login_data = $this->base([
            'email' => 'invalid@example.com',
            'password' => 'wrong-password',
        ]) ?? [];

        $response = $this->followingRedirects()->from(self::URL)
            ->post(self::URL, $login_data);

        $response->assertSee(self::MSG_INVALID_CREDENTIALS);
    }
    // 正しい情報が入力された場合、ログイン処理が実行される
    public function test_login_success(): void
    {
        $user = User::factory()->create([
            'email' => self::EMAIL,
            'password' => Hash::make(self::PASSWORD),
        ]);
        $login_data = $this->base() ?? [];

        $this->from(self::URL)
            ->post(self::URL, $login_data);

        $this->assertAuthenticatedAs($user);
    }
}
