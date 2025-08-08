<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $e)
    {
        // ① 未ログイン時
        if ($e instanceof AuthenticationException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 401);
            }
            return redirect()
                ->guest($e->redirectTo() ?? route('login'))
                ->with(
                    'failed',
                    'ログインしてください。会員登録済みの方はメール認証をしてください'
                );
        }

        // ② メール未認証時（EnsureEmailIsVerified が投げる例外）
        if ($e instanceof AuthorizationException) {
            // JSONなら元のレスポンス（403）
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 403);
            }
            // Webならログインへリダイレクト＋フラッシュ
            return redirect()
                ->guest(route('login'))
                ->with(
                    'failed',
                    'ログインしてください。会員登録済みの方はメール認証をしてください'
                );
        }

        // それ以外は通常どおり
        return parent::render($request, $e);
    }
}
