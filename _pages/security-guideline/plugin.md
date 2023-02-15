---
layout: single
title: プラグイン開発におけるセキュリティ強化
keywords: plugin プラグイン セキュリティ
tags: [security-guideline, plugin, security]
permalink: security-guideline/plugin
folder: security-guideline
---

## プラグイン開発におけるセキュリティ強化について

本ドキュメントは、プラグインの開発のセキュリティを向上させる機能について解説を行っています。

プラグイン開発時には、セキュリティ向上のため以下の機能を組み込むことを推奨します。

- 重要操作に対するスロットリング

## 重要操作に対するスロットリング

EC-CUBE 4.2.1から、EC-CUBE本体に[スロットリング機能](https://doc4.ec-cube.net/customize_throttling){:target="_blank"}が実装されました。

この機能は、プラグインやCustomizeからも拡張できるように実装されています。

プラグインで重要操作を実装する際は、スロットリングの機能を組み込むことを推奨します。

### 実装方法

ここでは[サンプル決済プラグイン](https://github.com/EC-CUBE/sample-payment-plugin){:target="_blank"}に機能追加を行う実装を例として解説します。

以下は、サンプル決済プラグインのカード情報変更時時に、スロットリングを実行させる際の記述です。

プラグインのservices.yamlに、設定を記載します。

app/Plugin/SamplePayment42/Resource/config/services.yaml

```yaml
# スロットリングの定義
eccube:
  rate_limiter:
    sample_payment_mypage_card_info:
      # 実行するルーティングを指定します。
      route: sample_payment_mypage_card_info
      # 実行するmethodを指定します。デフォルトはPOSTです。
      method: ['POST']
      # スロットリングの制御方法を設定します。ip・customerを指定できます。
      type: ['ip', 'customer']
      # 試行回数を設定します。
      limit: 5
      # インターバルを設定します。
      interval: '60 minutes'
```

以上で実装は完了です。

試行回数以上アクセスを行い、アクセス拒否されることを確認してください。

その他詳細な仕様については、[スロットリング機能](https://doc4.ec-cube.net/customize_throttling){:target="_blank"} を参照してください。

