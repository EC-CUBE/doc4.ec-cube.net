---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/customize/allow_list

---
## 取得可能なデータの追加

取得可能なデータは許可リスト方式で設定されています。

デフォルトの許可リストは `Resource/config/services.yaml` に定義されています。

```yaml

# 省略

services:

    # 省略

    core.api.allow_list:
        class: ArrayObject
        tags: ['eccube.api.allow_list']
        arguments:
            - #
                Eccube\Entity\AuthorityRole: ['id', 'deny_url', 'create_date', 'update_date', 'Authority', 'Creator']
                Eccube\Entity\BaseInfo: ['id', 'company_name', 'company_kana', 'postal_code', 'addr01', 'addr02', ... ]
                # 以降省略
```

許可リスト方式のため、カスタマイズで追加された Entity はデフォルトで取得できません。
カスタマイズで追加された Entity の取得を許可する場合は `eccube.api.allow_list` タグを付けたコンポーネントを定義します。
サービスIDは `[プラグインコード].api.allow_list` の形を推奨します。

例えばメーカー管理プラグインで利用する場合は以下のような `ArrayObject` の定義をプラグイン内の `services.yaml` に追加します。

```yaml
services:

    maker4.api.allow_list:
        class: ArrayObject
        tags: ['eccube.api.allow_list']
        arguments:
            - #
                Eccube\Entity\Product: ['maker_url', 'Maker']
                Plugin\Maker4\Entity\Maker: ['id', 'name', 'sort_no', 'create_date', 'update_date']
```

プラグインに許可リストが含まれない場合は、 `Customize` ディレクトリ以下の `services.yaml` でも定義できます。
