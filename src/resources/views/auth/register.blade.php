@extends('layouts.app_logo_only')
@section('css')
<link rel="stylesheet" href="{{asset('css/auth.css')}}">
@endsection
@section('title', '会員登録')
@section('content')
<div class="register">
  <h1 class="register__title">会員登録</h1>
  <form action="{{route('register')}}" method="POST" class="register__form">
    @csrf
    <div class="form-group">
      <label for="name">ユーザー名</label>
      <input type="text" name="name" id="name" class="input" value="{{ old('name') }}">
      @error('name')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="email">メールアドレス</label>
      <input type="text" name="email" id="email" class="input" value="{{old('email')}}">
      @error('email')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="password">パスワード</label>
      <input type="password" name="password" id="password" class="input">
      @error('password')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="password_confirmation">確認用パスワード</label>
      <input type="password" name="password_confirmation" id="password_confirmation" class="input">
      @error('password_confirmation')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <button type="submit" class="btn">登録する</button>
  </form>
  <div class="login-link">
    <a href="{{route('login')}}">ログインはこちら</a>
  </div>
  @endsection