<!doctype html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>取引完了のお知らせ</title>
</head>

<body>

  <p>商品「{{ $item->name }}」の取引が購入者によって完了されました。</p>

  <p>取引ID：{{ $purchase->id }}</p>

  <p>詳細はマイページの取引履歴よりご確認ください。</p>

  <p>※このメールは自動送信されています。</p>
</body>

</html>