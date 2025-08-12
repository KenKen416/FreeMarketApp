@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/items_index.css')}}">
@endsection
@section('title', '商品一覧')
@section('content')
@if (session('failed'))
<div class="alert alert-warning">
  {{ session('failed') }}
</div>
@endif
@if (session('success'))
<div class="alert alert-warning">
  {{ session('success') }}
</div>
@endif

@php
$tab = request('tab');
$keyword = request('keyword');
@endphp
<div class="tab-nav">
  <ul class="tab-nav__list">
    <li class="{{is_null($tab) ? 'active' : ''}}">
      <a href="{{route('items.index', ['tab' => null, 'keyword' => $keyword])}}">おすすめ</a>
    </li>
    <li class="{{($tab === 'mylist') ? 'active' : ''}}">
      <a href="{{route('items.index', ['tab' => 'mylist', 'keyword' => $keyword])}}">マイリスト</a>
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