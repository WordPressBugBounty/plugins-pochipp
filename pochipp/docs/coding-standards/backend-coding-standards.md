# バックエンドコーディング規約（WordPress プラグイン）

## 対象
`pochipp.php`、`inc/`、`class/` 配下の PHP コードに適用します。

## 基本ルール
1. `phpcs.xml` に基づく WordPress Coding Standards に従う。
2. `namespace POCHIPP;` と `ABSPATH` ガードを維持する。
3. 1ファイル/1関数の責務を明確にする。
4. hook/action/filter 名は `pochipp_` 接頭辞を付ける。

## セキュリティとデータ処理
1. 更新系処理の前に nonce と権限を検証する。
2. `$_POST` / `$_GET` は必ずサニタイズしてから使用する。
3. 出力時に適切なエスケープ（`esc_html` `esc_attr` `esc_url` など）を行う。
4. 外部APIレスポンスは信頼せず、保存前に整形・検証する。

## API/AJAX 方針
1. 成功/失敗レスポンスは構造を統一する。
2. 障害時は明示的なエラーコードとメッセージを返す。
3. 公開済み action 名やレスポンス形式は原則互換維持とする。

## 命名と構成
1. WordPress連携関数は説明的な `snake_case` を使う。
2. 共通処理は `class/` に寄せ、`inc/` で重複実装しない。
3. option/meta キーのマジック文字列乱立を避ける。

## 互換性と変更管理
1. 既存の post meta / option スキーマを破壊しない。
2. 保存データ、hook 挙動、出力仕様の変更は PR に明記する。

## 必須チェック
```bash
./vendor/bin/phpcs --standard=phpcs.xml
npm run build
```
