<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use App\Http\Responses\VerifyEmailResponse;



class FortifyServiceProvider extends ServiceProvider


{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // bind() を使って、Fortify のコントローラを自作ものに差し替え
        $this->app->bind(
            RegisteredUserController::class,
            RegisterController::class
        );
        $this->app->bind(
            AuthenticatedSessionController::class,
            LoginController::class
        );
        $this->app->singleton(
            VerifyEmailResponseContract::class,
            VerifyEmailResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Fortify::registerView(fn() => view('auth.register'));
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::verifyEmailView(fn() => view('auth.verify-email'));

        $this->app->make(RateLimiter::class)->for('login', function (Request $request) {
            return Limit::perMinute(10)->by($request->email . $request->ip());
        });
    }
}
