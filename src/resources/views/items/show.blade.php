@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/items_show.css')}}">
@endsection
@section('title', '商品詳細')
@section('content')
<div class="item-show">
  <div class="item-show__image">
    <img src="{{ asset('storage/images/' . $item->image) }}" alt="{{ $item->name }}" class="item-image">
  </div>
  <div class="item-show__info">
    <h1 class="item-show__title">{{ $item->name }}</h1>
    <div class="item-show__brand">{{ $item->brand_name ?? '' }}</div>
    <div class="item-show__price">
      <span class="item-show__yen-symbol">¥</span>
      <span class="item-show__price-value">{{ number_format($item->price) }}</span>
      <span class="item-show__tax">(税込)</span>
    </div>
    <div class="item-show__likes-comments">
      <div class="likes">
        <a href="" class="likes-button">☆</a>
        <span class="counts">1
          <!-- likeの数を持ってくる -->
        </span>
      </div>
      <div class="comments-link">
        <a href="" class="comments-button">⚪︎</a>
        <span class="counts">1
          <!-- コメントの数を持ってくる -->
        </span>
      </div>
    </div>
    <a href="{{route('purchases.index',['item_id'=>$item->id])}}" class="btn purchase-button">購入手続きへ</a>
    <div class="item-show__description">
      <h2 class="sub-title">商品説明</h2>
      <p class="item-show__description-value">
        {{$item->description }}
        <!-- 改行対応が多分必要 -->
      </p>
    </div>
    <div class="item-show__detail">
      <h2 class="sub-title">商品の情報</h2>
      <div class="item-show__category">
        <p class="detail--title">カテゴリー</p>
        <div class="category-tag">
          @foreach($item->categories ?? [] as $category)
          <span class="tag">{{ $category }}</span>
          @endforeach
        </div>
      </div>
      <div class="item-show__condition">
        <p class="detail--title">商品の状態</p>
        {{ $item->item_condition ?? '' }}
      </div>
    </div>
    <div class="item-show__comments">
      <h2 class="sub-title">コメント(コメント数)
        <!-- コメント数を持ってくる -->
      </h2>
      <div class="item-show__comments-value">
        <!-- foreach -->

        <div class="comment-user">
          <div class="comment-user__image">
            <img src=" " alt="" class="user-image">
          </div>
          <div class="comment-user__name">
            ユーザー名
          </div>
        </div>
        <p class="comment-text">
          コメント内容
          <!-- コメント内容を持ってくる -->
        </p>

        <!-- endforeach -->
      </div>
      <form action="" class="comment-create">
        <p class="comment--title">商品へのコメント</p>
        <textarea name="" id="" class="input input-comment"></textarea>
        <button class="btn btn--comment" type="submit">コメントを送信する</button>
      </form>
    </div>
  </div>
</div>
</div>
@endsection