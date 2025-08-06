@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/purchases_index.css')}}">
@endsection
@section('title', '購入確認')
@section('content')
<form action="" method="POST" class="payment-form">
  @csrf
  <div class="section-left">
    <div class="item-info">
      <div class="item-image">
        <img src="{{asset('storage/images/' . $item->image)}}" alt="" class="item-image__img">
      </div>
      <div class="item-details">
        <h1 class="item-name">{{$item->name}}</h1>
        <div class="item-price">
          <span class="yen-symbol">¥</span>
          <span class="price-value">{{number_format($item->price)}}</span>
        </div>
      </div>
    </div>
    <div class="payment-select">
      <h3 class="sub-title">支払い方法</h3>
      <select name="payment_method" id="payment_method" class="input input--select-payment">
        <option value="" disabled {{old('payment_method') ? '' : 'selected'}}>選択してください</option>
        <option value="konbini" {{old('payment_method')=='konbini' ? 'selected' : ''}}>コンビニ払い</option>
        <option value="card" {{old('payment_method')=='card' ? 'selected' : ''}}>カード支払い</option>
      </select>
      @error('payment_method')
      <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    <div class="delivery-address">
      <h3 class="sub-title">
        配送先住所
        <a href="{{route('purchases.edit_address',['item_id'=>$item->id])}}" class="address-change">変更する</a>
      </h3>
      <div class="address-info">
        <div class="post_code">
          〒 1231234
        </div>
        <div class="address-building">
          愛知県名古屋市１丁目
          名古屋ビル１1111111111111111111111111２３号室
        </div>

        <input type="hidden" name="post_code" class="" value="1231234">
        <input type="hidden" name="address" class="" value="愛知県名古屋市１丁目">
        <input type="hidden" name="building" class="" value="名古屋ビル１２３号室">
        <!-- 住所を取ってくること、住所変更を反映することの記述が必要 -->
        @error('post_code')
        <span class="error-message">{{ $message }}</span>
        @enderror
        @error('address')
        <span class="error-message">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>
  <div class="section-right">
    <div class="payment-summary">
      <table class="payment-table">
        <tr class="payment-table__tr">
          <td class="payment-table__td">商品代金</td>
          <td class="payment-table__td">
            <span class="yen-symbol">¥</span>
            <span class="price-value">{{number_format($item->price)}}</span>
          </td>
        </tr>
        <tr class="payment-table__tr">
          <td class="payment-table__td">支払い方法</td>
          <td class="payment-table__td" id="selected-method">選択してください</td>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const select = document.getElementById('payment_method');
              const display = document.getElementById('selected-method');

              // 初期表示対応
              const initialValue = select.value;
              if (initialValue === 'konbini') display.textContent = 'コンビニ払い';
              if (initialValue === 'card') display.textContent = 'カード支払い';

              // イベントリスナーで即時反映
              select.addEventListener('change', function() {
                if (this.value === 'konbini') {
                  display.textContent = 'コンビニ払い';
                } else if (this.value === 'card') {
                  display.textContent = 'カード支払い';
                } else {
                  display.textContent = '選択してください';
                }
              });
            });
          </script>
        </tr>
      </table>
    </div>
    <button type="submit" class="btn btn--purchase">購入する</button>
  </div>
</form>
@endsection