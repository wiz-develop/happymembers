# Happy Members

ハッピーファミリー会員サイトで稼働中の WordPress テーマ `happy-members` のソースです。

## Branches

- `main`: 本番FTPから取得した稼働中ソース
- `test`: テスト環境用（今後作成）

## Environment configuration

`assets/api/v1/common/config.php` は本番固有のパスと認証キーを含むため、Git管理対象外です。環境へ配置するときは、各環境で管理している設定ファイルを使用してください。

設定ファイルでは、既存の `$KEY` に加えて `$GOOGLE_CALENDAR_API_KEY` を定義する必要があります。初回取り込み時に管理画面および祝日取得処理へ直接記述されていたキーは、GitHubへ保存しないよう設定ファイル参照へ変更しています。

## Initial import

初回の `main` は 2026-09-15 に本番FTPの次のディレクトリから取得しました。

`/htdocs/ikou_www/happymembers/cms/wp-content/themes/happy-members`
