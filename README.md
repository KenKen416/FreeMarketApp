# フリマアプリケーション「FreeMarketApp」

このアプリケーションは Laravel を用いたフリーマーケットサービスです。ユーザー登録・ログイン、商品出品・購入、プロフィール管理などの機能を提供します。

---

## 主な機能

- 商品一覧表示（マイリスト切替可能）
- 商品の出品登録／詳細閲覧
- いいね機能（ユニーク制約）
- コメント機能
- 商品購入・配送先変更
- プロフィール編集（画像・住所・建物名含む）
- カテゴリーによる商品分類（多対多）
- バリデーションによる入力制御
- 認証機能（新規登録・ログイン）

---

## 環境構築（Docker 使用）

### 1. リポジトリをクローン

- git clone git@github.com:KenKen416/FreeMarketApp.git
- cd FreeMarketApp

### 2. Docker コンテナをビルド・起動

- docker compose up -d --build

### 3. Composer をインストール

- docker compose exec php composer install

### 4. .env ファイルを作成

- cp src/.env.example src/.env

#### .env 設定についての注意

- .env の中身は docker-compose.yml の設定に合わせて、以下のように編集してください：

  - DB_CONNECTION=mysql
  - DB_HOST=mysql
  - DB_PORT=3306
  - DB_DATABASE=laravel_db
  - DB_USERNAME=laravel_user
  - DB_PASSWORD=laravel_pass

- MAIL_MAILER=smtp
- MAIL_HOST=mailhog
- MAIL_PORT=1025
- MAIL_USERNAME=null
- MAIL_PASSWORD=null
- MAIL_ENCRYPTION=null
- MAIL_FROM_ADDRESS=no-reply@example.com
- MAIL_FROM_NAME="${APP_NAME}"

臼井メモ
stripe のテスト環境はこちら key 提供すべき？それとも、採点者に用意してもらうべき？

STRIPE_KEY=pk_test_51S0ENY5sTB06138YMHarnXVaR6QhYVEQPIhVmq6QxFH7P771KpQLKm2bO5GxuTKKht03lDTVON6r3NbdLj5Ilggt00FXEQd7ix
STRIPE_SECRET=sk_test_51S0ENY5sTB06138YhTArK8hbgSvX7pvSBvCu92tJpvp2nUQ3fPIUn9IDx8707jJUdOTYYJIWaVQWR8mFKVy7Ys4O00laSR2tn3

### 5. アプリケーションキーを生成

- docker compose exec php php artisan key:generate

### 6. ストレージのシンボリックリンクを作成

- docker compose exec php php artisan storage:link

### 7. マイグレーション＆初期データ投入

- docker compose exec php php artisan migrate --seed

---

## 主な使用技術・サービス

- docker : 環境構築
- Nginx：ポート 80 で公開
- PHP 7.4：Laravel アプリケーション実行
- MySQL 8.0.29：データベース
- phpMyAdmin：ポート 8080 でアクセス可能
- Bootstrap4 :ページネーションに使用
- JavaScript : 画像プレビュー、支払い方法選択即時表示変更などに使用
- mailhog :メール送信の試験用
- stripe :決済実行

---

## ER 図

- https://github.com/KenKen416/FreeMarketApp/blob/main/ER.png もしくは、FreeMarketAppフォルダ直下のER.pngを参照ください。（.dioファイルも同じ場所に置いてあります）

---

## 主なURL

- 開発環境：http://localhost/
- phpMyAdmin:http://localhost:8080
- MailHog UI:http://localhost:8025
- 商品一覧：/
- 商品一覧（マイリスト）：/?tab=mylist
- 商品詳細：/item/{item_id}
- 商品出品：/sell
- 商品購入：/purchase/{item_id}
- 配送先変更：/purchase/address/{item_id}
- 会員登録：/register
- ログイン：/login
- プロフィール：/mypage
- プロフィール編集：/mypage/profile
- 購入履歴：/mypage?page=buy
- 出品履歴：/mypage?page=sell
- その他の画面等については、基本設計書を参照してください。
https://docs.google.com/spreadsheets/d/11_8Cg7bE7sEyg7BI1XoBiNtDQYSzN_YWwaQ1QJUA880/edit?gid=574125123#gid=574125123

---

## テストユーザーのログイン情報
- src/database/migrations/seeders/UsersTableSeeder.php に記載しています
---

## その他（注意事項、メモ）
- テストコードを用意しています。テストケースのIDごとにtestファイルを作成しています（テストケースの項目名と合わせるような形で、testファイル名を設定しています。例：会員登録機能->RegisterTest.php）。ファイルの中には、テスト内容ごとにテストを実施しており、テスト内容をコメントアウトで表記しています。php artisan testコマンドで全てのテストを一括で実行できます。

- ユーザー登録時の「ユーザー名」と、プロフィール登録時の「ユーザー名」は別物として、データベース上は取り扱う方針としています。前者は、auth 上のユーザー名であり、変更は頻繁に行わないもの。後者は、サービス利用時に使用されるもので、ニックネームのようなものであり、頻繁に更新を行うことができるもの。auth の情報と、サービス利用上の情報を切り分ける（今回の name ももちろん、住所情報や画像データなども）ことで、テーブルの役割を明確化することを意図しています。
- stripe 連携については、コーチより、カード決済のみstripe画面へ遷移し、コンビニ支払いの場合は stripe 遷移は行わず、支払い完了とみなす実装とするように指示があったためそのとおり実装。理由としては、コンビニ支払いの場合は、その後の動線（コンビニで支払い処理をしたことを確かめること）がテストでは追えないことと、後述のテストケースで stripe に接続しないパターンを残しておかないとテストケースができないためとのこと。
- テストケース NO1「会員登録機能」について、正常に会員情報が登録された後の遷移先について、応用実装の「メール認証」を今回は実施しているため、遷移先はプロフィール画面ではなく、メール認証誘導画面として、テストケースを作成するようにコーチから指示があったため、そのようにテストコードを作成しています。
- テストケース No10「商品購入機能」について、支払い方法は、コンビニ支払いを選択してのテストの実施をコーチから指示されたため、そのとおりテストを実装しています。理由は、クレカ支払いは stripe 画面への遷移を今回は実装しており、その場合のテストは今回のテストケースに当てはまらないため、今回のテストケースに則った動きとなるのはコンビニ支払いのため。同様にテストケースNo12「配送先変更機能」についても、商品購入はコンビニ支払いを選択してテストを実施。
- テストケース No11「支払い方法選択機能」について、担当コーチより機能実装を javascript で実装しているため、テストについては実施は不要と指示されていたため、実施していおりません。

- 要件 ID FN012「メールを用いた認証機能（応用）」 機能詳細４「画面遷移」について、cメール認証画面 →　d商品一覧画面となっているが、 FN006「初回ログイン時ユーザー設定」における機能詳細として、初回会員登録後はプロフィー設定画面へ遷移することが挙げられていたため、メール認証後の画面遷移は商品一覧画面ではなく、プロフィー設定画面への遷移とした。それに伴い、テストケース No16「メール認証機能」について、メール認証完了後のページ遷移先をプロフィール設定画面としてテストコードを作成。なお、本件について、実際の実務でもメール認証後にプロフィール設定行わせることがほとんどであるため、今回の変更で進めるように、コーチより指示をもらった上で進めています。
