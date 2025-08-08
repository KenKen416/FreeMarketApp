<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
public function create()
    {
        return view('auth.login');
    }


    public function store(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (! Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']]
        )) {
            throw ValidationException::withMessages([
                'login_failed' => 'ログイン情報が登録されていません',
            ]);
        }

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (is_null($user->last_login_at)) {
            $user->update(['last_login_at' => now()]);
            return redirect('/mypage/profile')->with('success', '初回ログインありがとうございます！プロフィールを設定してください');
        }

        return redirect()->intended('/')
            ->with('success', 'ログインしました');
    }


    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'ログアウトしました');
    }

    public function loginFromEmailVerify(Request $request)
    {
        // ① 認証済みユーザーを確実にログイン
        $user = Auth::user();
        Auth::login($user);

        // ② セッション再生成
        $request->session()->regenerate();

        // ③ 初回ログインチェック
        /** @var \App\Models\User $user */
        if (is_null($user->last_login_at)) {
            $user->update(['last_login_at' => now()]);
            return redirect('/mypage/profile')
                ->with('success', '初回ログインありがとうございます！プロフィールを設定してください');
        }

        // ④ 通常フロー（store と同じリダイレクト先）
        return redirect()->intended('/')
            ->with('success', 'ログインしました');
    }
}
