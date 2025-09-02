<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function registrationData(array $overrides = []): array
    {
        $base = [
            'name' => 'テストユーザー',
            'email' => 'verify_test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        return array_merge($base, $overrides);
    }

    // 会員登録後、認証メールが送信される
    public function test_email_verification_sends_verification_after_registration(): void
    {
        Notification::fake();

        $response = $this->post(route('register'), $this->registrationData());
        $response->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'verify_test@example.com')->firstOrFail();
        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo(
            $user,
            VerifyEmail::class,
            function (VerifyEmail $notification, array $channels) use ($user) {
                $mail = $notification->toMail($user);


                $this->assertContains('mail', $channels);

                $this->assertNotNull($mail->actionUrl);
                $this->assertStringContainsString('/email/verify/', (string) $mail->actionUrl);

                return true;
            }
        );
    }
    //メール認証誘導画面で 「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する
    public function test_email_verification_link_opens_email_verification_page(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $response = $this->get(route('verification.notice'));
        $response->assertStatus(200);

        $response->assertSeeText('認証はこちらから');
        $response->assertSee('http://localhost:8025/');// メールhogのリンク＝＞メール認証サイトを表示
    }
    //メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する
    public function test_email_verification_completes_and_redirects_to_profile_settings(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'last_login_at' => null,
        ]);
        /** @var \App\Models\User $user */
        $this->actingAs($user);
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verificationUrl);
        $response->assertRedirect(route('profile.edit'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}