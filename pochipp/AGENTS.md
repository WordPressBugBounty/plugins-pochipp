# リポジトリガイドライン

## プロジェクト構成
このリポジトリは `pochipp.php` をエントリーポイントとする WordPress プラグインです。
- `inc/`: 実行時PHPロジック（AJAX、管理画面、ショートコード、API連携）
- `class/`: 共通クラス/トレイト（設定、ヘルパー、データ処理）
- `src/`: エディタ側ソース（`blocks`/`js`/`scss`/`toolbar`）
- `assets/`: 画像や同梱ライブラリなどの静的アセット
- `dist/`: ビルド成果物
- `.github/workflows/`: CI/CD と自動化ワークフロー

## ビルド・検証コマンド
- `npm install`: フロントエンド依存のインストール
- `npm run build`: ブロック/JS/SCSS の一括ビルド
- `npm run watch`: 開発中の監視ビルド
- `npm run fix`: JS lint の自動修正
- `composer install`: PHPツール依存のインストール
- `./vendor/bin/phpcs --standard=phpcs.xml`: PHP コーディング規約チェック

推奨のローカル確認手順:
```bash
npm run build
npm run fix
./vendor/bin/phpcs --standard=phpcs.xml
```

## コーディング規約と命名
- PHP は `phpcs.xml`（WPCS）に従う。
- JS は `@wordpress/scripts` と既存スタイルに合わせる。
- 命名は既存パターンを優先:
- WordPress連携関数は `snake_case`
- React コンポーネントは `PascalCase`、関数は `camelCase`
- 新規ブロック実装は `src/blocks/<feature>/` 配下に配置する。

## テスト方針
現状、専用ユニットテストは限定的です。以下を最低限実施します。
- ビルド成功（エラーなし）
- 変更対象PHPの PHPCS 通過
- 管理画面、ブロックエディタ、フロント表示の手動確認
PR には実施内容と結果を明記してください。

## コミット・PRルール
- 既存履歴のプレフィックスに合わせる（`feat:` `fix:` `remove:` `update:`）。
- 1コミット1論点を基本とする。
- PR には次を含める:
- 関連 Issue（例: `#123`）
- 変更概要と影響範囲
- 実行コマンドと手動確認結果
- UI変更時のスクリーンショット
- API/出力仕様変更時のリスクとロールバック方針

## 言語運用ルール
- 仕様検討や下書きは英語ベースで整理してよい。
- ドキュメント読解・レビューコメント・最終アウトプットは日本語で統一する。
