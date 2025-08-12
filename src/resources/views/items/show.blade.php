@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/items_show.css')}}">
@endsection
@section('title', '商品詳細')
@section('content')
<div class="item-show">
  <div class="item-show__image">
    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-image">
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
        @if($likes_user_count == 1)
        <form action="{{route('likes.destroy', ['item_id' => $item->id])}}" method="POST" class="likes-form">
          @csrf
          @method('DELETE')
          <button type="submit" class="likes-button">
            <img src="{{asset('storage/images/star_color.png')}}" alt="いいねのアイコン" class="likes-icon icon">
          </button>
        </form>
        @else
        <form action="{{route('likes.store', ['item_id' => $item->id])}}" method="POST" class="likes-form">
          @csrf
          <button type="submit" class="likes-button">
            <img src="{{asset('storage/images/star.png')}}" alt="いいねのアイコン" class="likes-icon icon">
          </button>
        </form>
        @endif
        <span class="counts">
          {{ $likes_count ?? 0 }}
        </span>
      </div>
      <div class="comments-link">
        <a href="#comments" class="comments-button">
          <img src="{{asset('storage/images/comment.png')}}" alt="コメントのアイコン" class="comment-icon icon">
        </a>
        <span class="counts">
          {{ $comments_count ?? 0 }}
        </span>
      </div>
    </div>
    <a href="{{route('purchases.index',['item_id'=>$item->id])}}" class="btn purchase-button">購入手続きへ</a>
    <div class="item-show__description">
      <h2 class="sub-title">商品説明</h2>
      <p class="item-show__description-value">
        {!! nl2br(e($item->description)) !!}
      </p>
    </div>
    <div class="item-show__detail">
      <h2 class="sub-title">商品の情報</h2>
      <div class="item-show__category">
        <p class="detail--title">カテゴリー</p>
        <div class="category-tag">
          @foreach($categories as $category)
          <span class="tag">{{ $category->name }}</span>
          @endforeach
        </div>
      </div>
      <div class="item-show__condition">
        <p class="detail--title">商品の状態</p>
        {{ $item_condition ->name }}
      </div>
    </div>
    <div class="item-show__comments">
      <h2 class="sub-title">コメント({{ $comments_count ?? 0 }})</h2>
      <div class="item-show__comments-value">
        @foreach($comments as $comment)

        <div class="comment-user">
          @if($comment->user->profile)
          <div class="comment-user__image">
            <img
              src="{{ $comment->user->profile->image ?
              asset('storage/' . $comment->user->profile->image) :
              asset('storage/images/default.png') }}"
              alt="ユーザー画像"
              class="user-image">
          </div>
          <div class="comment-user__name">
            {{ $comment->user->profile->name }}さん
          </div>
          @else
          <div class="comment-user__image">
            <img src="{{ asset('storage/images/default.png') }}" alt="ユーザー画像" class="user-image">
          </div>
          <div class="comment-user__name">
            名前未設定ユーザーさん
          </div>
          @endif
        </div>
        <p class="comment-text">
          {{ $comment->comment }}
        </p>
        @endforeach
        <form action="{{ route('comments.store', ['item_id' => $item->id]) }}" method="POST" class="comment-create">
          @csrf
          <p class="comment--title" id="comments">商品へのコメント</p>
          <textarea name="comment" id="" class="input input-comment"></textarea>
          @error('comment')
          <span class="error-message">{{ $message }}</span>
          @enderror
          <button class="btn btn--comment" type="submit">コメントを送信する</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection