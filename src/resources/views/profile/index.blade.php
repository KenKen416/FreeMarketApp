@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/profile_index.css')}}">
@endsection
@section('title', 'マイページ')
@section('content')
@if (session('success'))
<div class="alert alert-warning">
  {{ session('success') }}
</div>
@endif
<div class="profile-header">
  <div class="profile-header__img-name">

    @if($profile->image)
    <img
      src="{{ asset('storage/'.$profile->image) }}"
      alt="プロフィール画像" class="profile-header__image">
    @else
    <img src="{{ asset('storage/images/default.png') }}" alt="デフォルト画像" class="profile-header__image">
    @endif

    <div class="profile-header__name">
      {{ $profile->name }}
    </div>
  </div>
  <a href="{{ route('profile.edit') }}" class="btn btn-profile-edit">プロフィールを編集</a>
</div>

@php
$page = request('page');
@endphp
<div class="tab-nav">
  <ul class="tab-nav__list">
    <li class="{{is_null($page) || $page==='sell' ? 'active' : ''}}">
      <a href="{{route('mypage.index', ['page' => 'sell'])}}">出品した商品</a>
    </li>
    <li class="{{($page === 'buy') ? 'active' : ''}}">
      <a href="{{route('mypage.index', ['page' => 'buy'])}}">購入した商品</a>
    </li>
  </ul>
</div>
<div class="items-list">
  @forelse ($items as $item)
  <div class="item-card">
    @if($item->purchase_count === 1)
    <span class="badge badge--sold">sold</span>
    @endif
    <a href="{{route('items.show', ['item_id' => $item->id])}}" class="item-card__link">
      <img src="{{asset('storage/'.$item->image)}}" alt="{{$item->name}}" class="item-card__image">
      <h3 class="item-card__title">{{$item->name}}</h3>
    </a>
  </div>
  @empty
  <p>商品が見つかりませんでした。</p>
  @endforelse
</div>


@endsection