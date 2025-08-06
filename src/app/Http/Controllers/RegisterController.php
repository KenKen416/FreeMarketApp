<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;



class RegisterController extends Controller
{
    public function create()
    {
        // ユーザー登録画面を表示
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        // ユーザー登録処理
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        event(new Registered($user));

        Auth::login($user);


        // リダイレクト
        return redirect()->route('verification.notice');
    }
}
