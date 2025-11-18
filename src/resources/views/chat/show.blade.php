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
          }
          else{
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
        {{-- 取引完了ボタン（購入者のみ表示） --}}
        @if($purchase->user_id === auth()->id())
        {{-- 自分が購入者の場合のみ表示 --}}
        <form action="#" method="POST">@csrf
          <button type="button" class="btn-complete">取引を完了する</button>
        </form>
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
      @php $isMe = $message->sender_id === auth()->id();
      @endphp
      <div class={{$isMe ? "message--me" : "message--other"}}>
        @if($isMe)
        {{-- 自分のメッセージの場合 --}}

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
        {{-- 相手のメッセージの場合 --}}
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
            <a href="#" class="link-edit">編集</a>
            <a href="#" class="link-delete">削除</a>
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

@section('scripts')
<script src="{{ asset('js/chat-draft.js') }}"></script>
<script>
  // 画像プレビュー機能
  document.addEventListener('DOMContentLoaded', function() {
    console.log('スクリプト開始'); // デバッグ用

    const fileInput = document.getElementById('image');
    const preview = document.getElementById('image_preview');

    console.log('fileInput:', fileInput);
    console.log('preview:', preview);

    if (!fileInput || !preview) {
      console.log('要素が見つかりません');
      return;
    } else {
      console.log('要素が見つかりました');
    }

    fileInput.addEventListener('change', function(e) {
      console.log('ファイルが選択されました:', e.target.files);

      // 既存のプレビューをクリア
      preview.innerHTML = '';

      const file = e.target.files[0];
      if (!file) {
        console.log('ファイルがありません');
        return;
      }

      console.log('ファイル名:', file.name);
      console.log('ファイルタイプ:', file.type);

      // ファイルタイプをチェック
      if (!file.type.startsWith('image/')) {
        console.log('画像ファイルではありません');
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        console.log('ファイル読み込み完了');

        const img = document.createElement('img');
        img.src = e.target.result;
        img.style.maxWidth = '120px';
        img.style.maxHeight = '120px';
        img.style.borderRadius = '6px';
        img.style.display = 'block';
        img.style.marginTop = '8px';
        img.style.border = '1px solid #ccc'; // デバッグ用

        preview.appendChild(img);
        console.log('画像をプレビューに追加しました');
      };

      reader.onerror = function() {
        console.log('ファイル読み込みエラー');
      };

      reader.readAsDataURL(file);
    });
  });
</script>
@endsection