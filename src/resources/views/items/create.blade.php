@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/items_create.css')}}">
@endsection
@section('title', '商品の出品')
@section('content')
<div class="item-create">
  <h1 class="item-create__title">商品の出品</h1>
  <form action="" method="POST" class="item-create__form" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label>商品画像</label>
      <div class="item-image">
        <img
          class="preview-image" id="preview">
        <label class="btn btn--file" for="imageInput">画像を選択する</label>
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
      </div>
      @error('image')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-section">
      <h2 class="section-title">商品の詳細</h2>
      <div class="form-group">
        <label>カテゴリー</label>
        <div class="category-group">
          <input type="checkbox"
            name="category_id[]"
            id="category_id_変数"
            value="変数"
            class="category-checkbox"
            {{ in_array('変数', old('category_id', [])) ? 'checked' : '' }}>
          <label for="category_id_変数" class="category-label">おもちゃ</label>
          <!-- 変数で入れるのは後でやります １のところとか忘れずに-->
          <!-- ダミー -->

          <input type="checkbox" name="category_id[]" id="category_id"
            value="2" class="category-checkbox">
          <label for="category_id" class="category-label">とても長いカテゴリー名です</label>
          <input type="checkbox" name="category_id[]" id="category_id-2"
            value="1" class="category-checkbox">
          <label for="category_id-2" class="category-label">おもちゃ</label>
          <input type="checkbox" name="category_id[]" id="category_id"
            value="1" class="category-checkbox">
          <label for="category_id" class="category-label">おもちゃ</label>
          <input type="checkbox" name="category_id[]" id="category_id"
            value="1" class="category-checkbox">
          <label for="category_id" class="category-label">おもちゃ</label>
          <input type="checkbox" name="category_id[]" id="category_id"
            value="1" class="category-checkbox">
          <label for="category_id" class="category-label">おもちゃ</label>
          <input type="checkbox" name="category_id[]" id="category_id"
            value="1" class="category-checkbox">
          <label for="category_id" class="category-label">おもちゃ</label>
          <input type="checkbox" name="category_id[]" id="category_id"
            value="1" class="category-checkbox">
          <label for="category_id" class="category-label">おもちゃ</label>
          <input type="checkbox" name="category_id[]" id="category_id"
            value="1" class="category-checkbox">
          <label for="category_id" class="category-label">おもちゃ</label>
          <input type="checkbox" name="category_id[]" id="category_id"
            value="1" class="category-checkbox">
          <label for="category_id" class="category-label">おもちゃ</label>
          <input type="checkbox" name="category_id[]" id="category_id"
            value="1" class="category-checkbox">
          <label for="category_id" class="category-label">おもちゃ</label>
          <input type="checkbox" name="category_id[]" id="category_id"
            value="1" class="category-checkbox">
          <label for="category_id" class="category-label">おもちゃ</label>
          <!-- ダミー -->
        </div>
        @error('category_id')
        <span class="error-message">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="condition_id">商品の状態</label>
        <select name="condition_id" id="condition_id" class="input">
          <option value="">選択してください</option>
          <option value="1"
            {{old('condition_id') == "1" ? 'checked' : ''}}>新品</option>
          <option value="2" {{old('condition_id') == "2" ? 'checked' : ''}}>目立った傷や汚れなし</option>
          <option value="3"
            {{old('condition_id') == "3" ? 'checked' : ''}}>やや傷や汚れあり</option>
          <option value="4"
            {{old('condition_id') == "4" ? 'checked' : ''}}>傷や汚れあり</option>
          <option value="5"
            {{old('condition_id') == "5" ? 'checked' : ''}}>全体的に状態が悪い</option>
        </select>
        @error('condition_id')
        <span class="error-message">{{ $message }}</span>
        @enderror

      </div>
      <div class="form-section">
        <h2 class="section-title">商品名と説明</h2>
        <div class="form-group">
          <label for="name">商品名</label>
          <input type="text" name="name" id="name" class="input"
            value="{{old('name')}}">
          @error('name')
          <span class="error-message">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="brand_name">ブランド名</label>
          <input type="text" name="brand_name" id="brand_name" class="input"
            value="{{old('brand_name')}}">
          @error('brand_name')
          <span class="error-message">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description">商品説明</label>
          <textarea name="description" id="description" class="input"
            rows="5">{{old('description')}}</textarea>
          @error('description')
          <span class="error-message">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="price">販売価格</label>
          <div class="price-input-wrapper">
            <span class="yen-symbol">¥</span>
            <input type="number" name="price" id="price" class="input input--price"
              value="{{old('price')}}">
          </div>
          @error('price')
          <span class="error-message">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <button type="submit" class="btn btn--create">出品する</button>
  </form>
  @endsection