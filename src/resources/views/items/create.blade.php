@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/items_create.css')}}">
@endsection
@section('title', '商品の出品')
@section('content')
<div class="item-create">
  <h1 class="item-create__title">商品の出品</h1>
  <form action="{{ route('items.store') }}" method="POST" class="item-create__form" enctype="multipart/form-data">
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
          @foreach($categories as $category)
          <label>
            <input type="checkbox"
              name="category_id[]"
              value="{{$category->id}}"
              class="category-checkbox"
              {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>
            <div class="category-label">{{$category->name}}</div>
          </label>
          @endforeach
        </div>
        @error('category_id')
        <span class="error-message">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="condition_id">商品の状態</label>
        <select name="condition_id" id="condition_id" class="input">
          <option value="" disabled selected>選択してください</option>
          @foreach($conditions as $condition)
          <option
            value="{{$condition->id}}"
            {{old('condition_id') == $condition->id ? 'selected' : ''}}>
            {{$condition->name}}
          </option>
          @endforeach
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