@extends('layouts.app_logo_only')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat_show.css') }}">
@endsection

@section('title', '取引チャット')

@section('content')
<div class="chat-page">
  {{-- 左サイドバー --}}
  <aside class="chat-sidebar">
    <div class="sidebar-heading">その他の取引</div>
    <ul class="sidebar-list">
      @foreach($purchases as $p)
      <li class="sidebar-item {{ isset($purchase) && $purchase->id === $p->id ? 'active' : '' }}">
        <a href="{{ route('chats.show', $p->id) }}" class="sidebar-link">
          <div class="sidebar-thumb">
            @if(optional($p->item)->image)
            <img src="{{ asset('storage/' . $p->item->image) }}" alt="">
            @endif
          </div>
          <div class="sidebar-meta">
            <div class="sidebar-title">{{ optional($p->item)->name }}</div>
            @if($p->unread_count > 0)
            <div class="sidebar-unread">{{ $p->unread_count }}</div>
            @endif
          </div>
        </a>
      </li>
      @endforeach
    </ul>
  </aside>

  {{-- メインコンテンツ --}}
  <main class="chat-main">
    {{-- トップバー：取引相手名 --}}
    <div class="chat-topbar">
      <div class="topbar-left">
        <div class="topbar-avatar">
          @php
          $otherUser = null;
          if($purchase->user_id === auth()->id()) {
          $otherUser = $purchase->item->user;
          } else {
          $otherUser = $purchase->user;
          }
          @endphp
          @if($otherUser && optional($otherUser->profile)->image)
          <img src="{{ asset('storage/' . $otherUser->profile->image) }}" alt="avatar">
          @else
          <img src="{{ asset('storage/images/default.png') }}" alt="avatar">
          @endif
        </div>

        <div class="topbar-title">
          {{ '「' . e(optional($otherUser->profile)->name ?? '名称未設定ユーザー') . '」さんとの取引画面' }}
        </div>
      </div>

      <div class="topbar-actions">
        {{-- 取引完了ボタン（購入者のみ表示）。ルートは purchases.complete を想定 --}}
        @if($purchase->user_id === auth()->id())
        <form action="{{ route('purchases.complete', $purchase->id) }}" method="POST">
          @csrf
          <button type="submit" class="btn-complete">取引を完了する</button>
        </form>
        @endif
        @if (session('failed'))
        <div class="alert alert-warning">
          {{ session('failed') }}
        </div>
        @endif
      </div>
    </div>

    {{-- 商品ヘッダ --}}
    <section class="product-header">
      <div class="product-thumb">
        @if(optional($purchase->item)->image)
        <img src="{{ asset('storage/' . $purchase->item->image) }}" alt="">
        @endif
      </div>
      <div class="product-info">
        <h1 class="product-name">{{ optional($purchase->item)->name }}</h1>
        <div class="product-price">¥{{ number_format(optional($purchase->item)->price ?? 0) }}</div>
      </div>
    </section>

    {{-- メッセージエリア --}}
    <section class="messages-area">
      @foreach($messages as $message)
      @php $isMe = $message->sender_id === auth()->id(); @endphp

      <div class="{{ $isMe ? 'message--me' : 'message--other' }}">
        @if($isMe)
        {{-- 自分のメッセージ表示 --}}
        <div class="message-row">
          <div class="message-author">
            {{ optional(auth()->user()->profile)->name ?? 'あなた' }}
          </div>
          <div class="message-avatar">
            @if(optional(auth()->user()->profile)->image)
            <img src="{{ asset('storage/' . auth()->user()->profile->image) }}" alt="avatar">
            @else
            <img src="{{ asset('storage/images/default.png') }}" alt="avatar">
            @endif
          </div>
        </div>
        @else
        {{-- 相手のメッセージ表示 --}}
        <div class="message-row">
          <div class="message-avatar">
            @if(!empty($otherUser) && optional($otherUser->profile)->image)
            <img src="{{ asset('storage/' . $otherUser->profile->image) }}" alt="avatar">
            @else
            <img src="{{ asset('storage/images/default.png') }}" alt="avatar">
            @endif
          </div>
          <div class="message-author">
            {{ optional($message->sender->profile)->name ??  '名称未設定ユーザー' }}
          </div>
        </div>
        @endif

        <div class="message-body">
          <div class="message-text">{!! nl2br(e($message->body)) !!}</div>
          @if($message->image)
          <div class="message-image">
            <img src="{{ asset('storage/' . $message->image) }}" alt="attached image">
          </div>
          @endif
        </div>

        @if($isMe)
        <div class="message-meta">
          <span class="message-actions">
            {{-- 編集リンク：data 属性を設定（edit-message.js で利用） --}}
            <a href="#" class="link-edit"
              data-message-id="{{ $message->id }}"
              data-purchase-id="{{ $purchase->id }}"
              data-message-body="{{ e($message->body) }}">編集</a>

            {{-- 削除はフォームを送る方式（CSRF・DELETE） --}}
            <form action="{{ route('messages.destroy', ['purchase' => $purchase->id, 'message' => $message->id]) }}" method="POST" style="display:inline-block;margin-left:8px;" onsubmit="return confirm('メッセージを削除してもよろしいですか？');">
              @csrf
              @method('DELETE')
              <button type="submit" class="link-delete" style="background:none;border:none;color:#777;cursor:pointer;padding:0;">削除</button>
            </form>
          </span>
        </div>
        @endif
      </div>
      @endforeach
    </section>

    {{-- 投稿フォーム --}}
    <section class="chat-form-section">
      <form action="{{ route('messages.store', $purchase->id) }}" method="POST" enctype="multipart/form-data" class="chat-form">
        @csrf

        {{-- バリデーションエラー --}}
        @if ($errors->any())
        <div class="form-errors">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <div class="form-row">
          <textarea id="message_body" name="body" placeholder="取引メッセージを記入してください" rows="2" class="chat-textarea">{{ old('body') }}</textarea>

          <div class="form-controls">
            <div id="image_preview" class="image-preview"></div>
            <label class="btn-image" for="image">画像を追加</label>
            <input type="file" name="image" id="image" accept=".png,.jpeg,.jpg" class="input-file">
            <button type="submit" class="btn-send" aria-label="送信">
              <img src="{{ asset('storage/images/send.png') }}" alt="send">
            </button>
          </div>
        </div>
      </form>
    </section>
  </main>
</div>
@endsection

{{-- モーダル partial を読み込む（ファイルを作成済みの前提） --}}
@include('partials._rating_modal')
@include('partials._edit_message_modal')

@php
// PHP 側で単純な boolean にしておく（Blade 内での複雑な式を避ける）
$showRatingFlag = isset($showRating) ? (bool) $showRating : (bool) session('show_rating');
@endphp

@section('scripts')
@php
// PHP 側で単純 boolean にしておく（Blade 内の複雑式を避ける）
$showRatingFlag = isset($showRating) ? (bool) $showRating : (bool) session('show_rating');
@endphp

@section('scripts')
@php
// PHP 側で単純な boolean にしておく（複雑な式を JS 内に残さない）
$showRatingFlag = isset($showRating) ? (bool) $showRating : (bool) session('show_rating');
@endphp

@section('scripts')
@section('scripts')
<script>
  // サーバ -> クライアントのフラグをページ末で即時セット（DOMContentLoaded に依存させない）
  (function(){
    try {
      document.body.dataset.showRating = "{{ session('show_rating') ? 1 : 0 }}";
    } catch (e) {
      // 万が一 template で何か起きても安全にデフォルトを設定
      if (document && document.body) { document.body.dataset.showRating = "0"; }
    }
  })();
</script>

<script src="{{ asset('js/chat-draft.js') }}"></script>
<script src="{{ asset('js/rating-modal.js') }}"></script>
<script src="{{ asset('js/edit-message.js') }}"></script>

<script>
  // 画像プレビュー（簡潔版）
  document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('image_preview');
    if (!fileInput || !preview) return;

    fileInput.addEventListener('change', function(e) {
      preview.innerHTML = '';
      const file = e.target.files && e.target.files[0];
      if (!file) return;
      if (!file.type.startsWith('image/')) return;

      const reader = new FileReader();
      reader.onload = function(ev) {
        const img = document.createElement('img');
        img.src = ev.target.result;
        img.className = 'preview-img';
        img.style.maxWidth = '120px';
        img.style.maxHeight = '120px';
        img.style.borderRadius = '6px';
        preview.appendChild(img);
      };
      reader.readAsDataURL(file);
    });
  });
</script>
@endsection