@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/purchases_edit_address.css')}}">
@endsection
@section('title', '住所の変更')
@section('content')
<div class="edit-address">
  <h1 class="edit-address__title">住所の変更</h1>
  <form action="" method="POST" class="edit-address__form">
    @csrf

    <div class="form-group">
      <label for="post_code">郵便番号</label>
      <input type="text" name="post_code" id="post_code" class="input" value="{{ old('post_code') }}">
      @error('post_code')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="address">住所</label>
      <input type="text" name="address" id="address" class="input" value="{{ old('address')}}">
      @error('address')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="building">建物名</label>
      <input type="text" name="building" id="building" class="input" value="{{ old('building')}}">
      @error('building')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <button type="submit" class="btn btn--update">更新する</button>
  </form>
</div>
@endsection