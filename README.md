# MyPage

## 概要

このプロジェクトの目的はphpを使ったログイン機能やログアウト機能、セッション、ポストなどの練習です。ローカルサーバーでMySQLを設定し、起動してブラウザで各種機能のチェックできます。

## 機能一覧

- ユーザー登録
- ログイン・ログアウト
- プロフィール情報の表示・編集
- 登録内容の確認
- エラーメッセージ表示

## ディレクトリ構成

```text
login_mypage/
    ├── register.php           # ユーザー登録画面
    ├── register_confirm.php   # 登録内容確認画面
    ├── register_insert.php    # 登録処理
    ├── login.php              # ログイン画面
    ├── login_error.php        # ログインエラー画面
    ├── mypage.php             # マイページ表示
    ├── mypage_hensyu.php      # プロフィール編集画面
    ├── mypage_update.php      # プロフィール更新処理
    ├── log_out.php            # ログアウト処理
    ├── config.php             # DB接続設定
    ├── image/                 # プロフィール画像
    └── *.css                  # 各画面用スタイル
```

## セットアップ

1. リポジトリをクローン
2. MySQLにデータベースを作成し設定。その内容に`config.php`を編集
3. `php -S localhost:8000`でローカルサーバーを起動後、ブラウザで`localhost:8000/login.php`にアクセス
