<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/register';
    private const NAME = 'テストユーザー';
    private const EMAIL = 'test@example.com';
    private const PASSWORD = 'password';

    private const MSG_NAME_REQUIRED = 'お名前を入力してください';
    private const MSG_EMAIL_REQUIRED = 'メールアドレスを入力してください';
    private const MSG_PASSWORD_REQUIRED = 'パスワードを入力してください';
    private const MSG_PASSWORD_MIN = 'パスワードは8文字以上で入力してください';
    private const MSG_PASSWORD_MISMATCH = 'パスワードと一致しません';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    private function base(array $overrides = []): array
    {
        $data = [
            'name' => self::NAME,
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ];
        return array_merge($data, $overrides);
    }

    // 名前が入力されていない場合、バリデーションメッセージが表示される
    public function test_register_shows_error_when_name_is_missing(): void
    {
        $register_data = $this->base(['name' => '']) ?? [];

        $response = $this->followingRedirects()->from(self::URL)
            ->post(self::URL, $register_data);

        $response->assertSee(self::MSG_NAME_REQUIRED);
    }
    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_register_shows_error_when_email_is_missing(): void
    {
        $register_data = $this->base(['email' => '']) ?? [];

        $response = $this->followingRedirects()->from(self::URL)
            ->post(self::URL, $register_data);

        $response->assertSee(self::MSG_EMAIL_REQUIRED);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_register_shows_error_when_password_is_missing(): void
    {
        $register_data = $this->base(['password' => '']) ?? [];

        $response = $this->followingRedirects()->from(self::URL)
            ->post(self::URL, $register_data);

        $response->assertSee(self::MSG_PASSWORD_REQUIRED);
    }

    // パスワードが7文字以下の場合、バリデーションメッセージが表示される
    public function test_register_shows_error_when_password_is_too_short(): void
    {
        $register_data = $this->base(['password' => '1234567']) ?? [];

        $response = $this->followingRedirects()->from(self::URL)
            ->post(self::URL, $register_data);

        $response->assertSee(self::MSG_PASSWORD_MIN);
    }
    // パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
    public function test_register_shows_error_when_password_confirmation_is_mismatch(): void
    {
        $register_data = $this->base(['password_confirmation' => 'different']) ?? [];

        $response = $this->followingRedirects()->from(self::URL)
            ->post(self::URL, $register_data);

        $response->assertSee(self::MSG_PASSWORD_MISMATCH);
    }
    // 全ての項目が入力されている場合、会員情報が登録され、メール認証誘導画面へ遷移する
    public function test_register_success(): void
    {
        $register_data = $this->base() ?? [];

        $response = $this->from(self::URL)
            ->post(self::URL, $register_data);

        $this->assertDatabaseHas('users', [
            'name' => self::NAME,
            'email' => self::EMAIL,
        ]);

        $response->assertRedirect('/email/verify');
    }
}
