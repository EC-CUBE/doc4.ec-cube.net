---
title: 環境構築
permalink: /penetration-testing/testing/settings
---
## システム要件

- Docker 1.13.0 or higher
- docker-compose 1.10.0 or higher

- Chrome(最新版) - OWASP ZAP の管理画面操作に使用します
- Firefox(最新版) - Local Proxes 経由の手動探索で使用します

## インストール

[Quick Start](/penetration-testing/quick_start) を参照

**Note:** 後ほど詳細含めて追記します
{: .notice}

### docker-compose-owaspzap.yml で自動設定される内容

#### VOLUME マウントディレクトリ

```shell
<ec-cubeインストールディレクトリ>/ec-cube/zap:/zap/wrk
```

セッションやレポートの保存先など、 `/zap/wrk` に設定すると、ローカル環境にすぐに取り出すことができて便利です。

#### インストールされるアドオン

- [help_ja_JP](https://www.zaproxy.org/addons/#addon-help_ja_JP) - 日本語ヘルプ
- [wappalyzer](https://www.zaproxy.org/docs/desktop/addons/technology-detection/) - 利用技術検出
- [sequence](https://www.zaproxy.org/docs/desktop/addons/sequence-scanner/) - 複数画面遷移用アドオン

#### アンインストールされるアドオン

- [hud](https://www.zaproxy.org/docs/desktop/addons/hud/) - ヘッドアップディスプレイ。 Selenium などのブラウザ操作ツールと干渉するため使用しない

#### 自動設定されるオプション

- 日本語ロケール
- APIキーの無効化
  - クローズドな環境での利便性向上のため
- アンチCSRFトークン(試験的)
- HttpSessionトークン
- グローバルアラートフィルター(試験的)
  - 常に誤検知されるものを登録

#### その他

- Local Proxes 用 SSL ルート CA 証明書の自動出力
  - ローカル環境の `<ec-cubeインストールディレクトリ>/zap/owasp_zap_root_ca.cer` に出力されます
  - Firefox の証明書マネージャーに読み込んで使用します
