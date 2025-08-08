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

div.

@endsection