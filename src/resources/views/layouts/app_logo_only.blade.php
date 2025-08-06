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

    </div>
  </header>
  <main>
    @yield('content')
  </main>
</body>

</html>