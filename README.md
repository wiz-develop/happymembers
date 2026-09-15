# Happy Members

ハッピーファミリー会員サイトで稼働中の WordPress テーマ `happy-members` のソースです。

## Branches

- `main`: 本番FTPから取得した稼働中ソース
- `test`: テストFTPから取得したテスト環境ソース
- `investigate/empty-order-mobile`: 空注文・重複注文の修正元ブランチ

`main` と `test` には環境固有の差分があります。ブランチ全体やテーマ全体を相互環境へ上書きせず、変更対象ファイルだけを確認して反映してください。詳細は [`docs/environment-safety.md`](docs/environment-safety.md) を参照してください。

## Environment configuration

`assets/api/v1/common/config.php` は本番固有のパスと認証キーを含むため、Git管理対象外です。環境へ配置するときは、各環境で管理している設定ファイルを使用してください。

設定ファイルでは、既存の `$KEY` に加えて `$GOOGLE_CALENDAR_API_KEY` を定義する必要があります。初回取り込み時に管理画面および祝日取得処理へ直接記述されていたキーは、GitHubへ保存しないよう設定ファイル参照へ変更しています。

## Initial import

初回の `main` は 2026-09-15 に本番FTPの次のディレクトリから取得しました。

`/htdocs/ikou_www/happymembers/cms/wp-content/themes/happy-members`

`test` は 2026-09-15 にテストFTPの次のディレクトリから取得しました。

`/html/cms/wp-content/themes/happy-members`

## E2E tests

空注文・重複注文の再発防止テストは `e2e/` にあります。テスト環境に実際の注文を1件作成するため、実行条件と注意事項を [`e2e/README.md`](e2e/README.md) で確認してください。
