# 空注文・重複注文のE2Eテスト

このテストは、商品を含む注文が成立した直後に別の注文確認画面から再送し、2件目の空注文と同一トークンの再送が拒否されることを確認する。

## 安全条件

- 実行先は `happyfamily-members.3d-showcase.net` に固定されており、本番ホストでは起動しない。
- テスト環境に有効な注文を1件作成し、注文確認メールも送信する。
- 専用テスト会員を使用する。既存カートが空でなければ削除せず停止する。
- ログインID・パスワードをGitへ保存しない。
- 会員情報が成果物に残らないよう、スクリーンショット・動画・トレースを保存しない。

## 準備

```sh
cd e2e
npm ci
npx playwright install chromium
```

次の環境変数を設定する。

```sh
export E2E_BASE_URL='http://happyfamily-members.3d-showcase.net'
export E2E_LOGIN_ID='専用テスト会員のログインID'
export E2E_PASSWORD='専用テスト会員のパスワード'
export E2E_ALLOW_ORDER_CREATION='yes-test-environment-only'
```

商品コードを指定する場合だけ `E2E_PRODUCT_CODE` を設定する。未指定の場合は、商品一覧の先頭にある購入可能な商品を1点使用する。

`e2e/.env` を使用する場合は、上記の `export` 行を保存し、実行前に読み込む。

```sh
set -a
source .env
set +a
```

## 実行

Android相当の画面サイズで実行する。

```sh
npm test
```

PC相当で実行する場合は、別の空カート状態の専用テスト会員を用意して実行する。

```sh
E2E_DEVICE=desktop npm test
```

同じ会員で続けて実行すると既存注文やカート状態の判断を誤るため、1実行ごとに注文履歴・メール・カート状態を確認する。
