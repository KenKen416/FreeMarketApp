@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/profile_edit.css')}}">
@endsection
@section('title', 'プロフィール設定')
@section('content')
<div class="profile-edit">
  <h1 class="profile-edit__title">プロフィール設定</h1>
  <form action="" method="POST" class="profile-edit__form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group profile-image">
      <img src="{{$user->image ? asset('storage/images/'.$user->image) : asset('storage/images/default.png')}}" alt="プロフィール画像"
        class="profile-edit__image" width="150" height="150" id="preview">
      <label for="imageInput" class="btn btn--file">画像を選択する</label>
      <input type="file" name="image" id="imageInput" class="input input--file">
      <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
          const file = e.target.files[0];
          if (file) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(file);
          }
        });
      </script>

      @error('image')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="name">ユーザー名</label>
      <input type="text" name="name" id="name" class="input" value="{{old('name',$user->name ?? '')}}">
      @error('name')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="post_code">郵便番号</label>
      <input type="text" name="post_code" id="post_code" class="input" value="{{old('post_code',$user->post_code ?? '')}}">
      @error('post_code')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="address">住所</label>
      <input type="text" name="address" id="address" class="input" value="{{old('address',$user->address ?? '')}}">
      @error('address')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="building">建物</label>
      <input type="text" name="building" id="building" class="input" value="{{old('building',$user->building ?? '')}}">
      @error('building')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <button type="submit" class="btn btn--update">更新する</button>
  </form>
  @endsection