<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{asset('css/sanitize.css')}}">
  <link rel="stylesheet" href="{{asset('css/components.css')}}">
  <link rel="stylesheet" href="{{asset('css/form.css')}}">
  <link rel="stylesheet" href="{{asset('css/app.css')}}">
  @yield('css')
  <title>@yield('title','FreeMarketApp')</title>
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <div class="logo">
        <a href="/" class="logo">
          <img src="{{asset('storage/images/logo.svg')}}" alt="ロゴ画像" class="logo__image">
        </a>
      </div>
      <form action="{{ route('items.index') }}" class="search-form" method="GET">

        <input type="text" name="keyword" class="input search-form__input" placeholder="なにをお探しですか？" value="{{request('keyword')}}">
        <button type="submit" style="display: none;">検索</button>
      </form>
      <nav class="nav">
        <ul class="nav__list">
          @guest
          <li><a href="{{route('login')}}" class="">ログイン</a></li>
          @endguest
          @auth
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                class="logout-button">
                ログアウト
              </button>
            </form>
          </li>
          @endauth
          <li><a href="">マイページ</a></li>
          <li><a href="{{route('items.create')}}" class="btn create-btn">出品</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    @yield('content')
  </main>
</body>

</html>