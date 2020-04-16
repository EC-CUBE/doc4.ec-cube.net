---
title: プラグイン仕様
keywords: plugin spec プラグイン 仕様
tags: [quickstart, getting_started]
permalink: plugin_spec

---

## 概要

プラグイン仕様の概要を記載します。

## ディレクトリ構成

プラグインの一般的なディレクトリ構成を示します。

```
app/Plugin/SamplePlugin/
├── Command
├── Common
│   └── Nav.php
├── Controller
├── Doctrine
│   └── Query
├── Entity
├── EventListener
│   └── EventListener.php
├── Form
│   ├── Extension
│   └── Type
├── PluginManager.php
├── Repository
├── Resource
│   ├── assets
│   ├── config
│   ├── locale
│   └── template
└── composer.json
```

上記のすべてのディレクトリ、ファイルが必要なわけではありません。
必須となるのは、`composer.json`のみです。

## 設定ファイル

プラグインの情報を記述する`composer.json`と、コンテナの定義を行う`services.yaml`があります。

### composer.json

プラグインの情報を記述します。
`[プラグインディレクトリ]/composer.json`に設置します。

設定項目は以下のとおりです。

- name: パッケージ名
    - `"ec-cube/[プラグインコード]"` を記述します。
- version: バージョン
    - プラグインのバージョン番号です。
    - phpのバージョンフォーマットに合わせてください。
- description: プラグイン名称
- type: パッケージタイプ
    - `"eccube-plugin"` にします。
- require: 依存パッケージ
    - プラグインが利用するパッケージがあれば追記します。
    - `"ec-cube/plugin-installer": "~0.0.6"` は常に記述してください。
- extra: 付属情報
    - `"code": "[プラグインコード]"` を記述してください。

記載例は以下の通りです。

```yaml
{
    "name": "ec-cube/ProductReview",
    "version": "1.0.0",
    "description": "商品レビュープラグイン",
    "type": "eccube-plugin",
    "require": {
        "ec-cube/plugin-installer": "~0.0.6"
    },
    "extra": {
        "code": "ProductReview"
    }
}
```

### services.yaml

コンテナの定義を行います。
`[プラグインディレクトリ]/Resouce/services.yaml`に設置します。
yamlフォーマットの他に、phpやxmlでも記述可能です。

コンテナの定義については、Symfonyの公式ドキュメントを参照してください。
https://symfony.com/doc/current/service_container.html

### その他の設定ファイル
その他のプラグイン独自の設定ファイルをプラグイン内に含めることは可能ですが、プラグインインストール後に生成または変更される設定ファイルは `app/PluginData` ディレクトリ以下に設置するようにしてください。プラグインのディレクトリ内にこれらのファイルを配置した場合、**プラグインのアップデートや再インストールの操作によって設定ファイルが失われます。**
`app/PluginData` ディレクトリ以下を利用する場合は、他のプラグインとの衝突を避けるため、`app/PluginData/[プラグインコード]` ディレクトリを作成して利用することを推奨します。

## 静的コンテンツ

プラグインで利用する静的コンテンツ (html,css,js,画像など) は、`Resource/assets` 以下に配置します。プラグイン以下の `Resource/assets` ディレクトリはインストール/アップデート時に `[EC-CUBEホームディレクトリ]/html/plugin/[プラグインコード]/assets` 以下にコピーされます。 [#3821](https://github.com/EC-CUBE/ec-cube/pull/3821)


例えば、以下のように `sample.jpg` ファイルを配置しておくと、 `[EC-CUBEホームディレクトリ]/html/plugin/SamplePlugin/assets/sample.jpg` にコピーされます。

```
SamplePlugin
 └── Resource
      └── assets
         └── sample.jpg
```

Twigファイルからこの `sample.jpg` へのパスは以下の記述で取得できます。

```twig
{% raw %}{{ asset('SamplePlugin/assets/sample.jpg', 'plugin') }}{% endraw %}
```

展開されたパスは以下のようになります。

```
/html/plugin/SamplePlugin/assets/sample.jpg
```


## プラグインのパッケージング

開発したプラグインを配布したり、オーナーズストアに申請する際は、アーカイブする必要があります。
アーカイブの方式は、tar.gzで行ってください。
また、以下の点に注意してアーカイブを作成してください。
- フォルダごと圧縮しないようにする
- `.git` ディレクトリや `.DS_Store` ファイル等をアーカイブに含めないようにする

```bash
$ cd app/[PluginDir]
$ tar --exclude  ".git" --exclude ".DS_Store" -cvzf ../[PluginDir].tar.gz *
```

## 3.0.xからの変更点

3.0.xからの主な変更点を記載します。

- ServiceProviderの廃止
    - ServiceProviderで行っていたコンテナ定義は、Symfonyの機構を利用するようになりました。
- マイグレーション機構の変更
    - マイグレーションは、doctrine:schema:updateを利用するようになりました。
    - PluginManagerではマイグレーションは行わず、初期データの投入・更新・削除のみ行うようにしてください。
- フックポイントの非推奨化
    - `eccube.event.admin.request`など、リクエストの実行前後に動作するフックポイントは非推奨となりました。
    - twigファイルにパーツを差し込むために利用している場合は、スニペットを用意し、ユーザに貼り付けてもらう方式になります。
    - https://github.com/EC-CUBE/ec-cube/issues/2440
- ファイル設置のみのプラグインはロードされない
    - dtb_pluginにレコードが登録されている必要があります。
