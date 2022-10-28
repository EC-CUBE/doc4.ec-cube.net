---
title: プラグインを開発する
keywords: plugin プラグイン 開発
tags: [plugin, development]
permalink: plugin_development

---

プラグインの基本的な開発手順について解説します。

## 前提条件

- EC-CUBEを[ローカル環境にインストール](/quickstart/install#cui%E3%81%A7%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB%E3%81%99%E3%82%8B)していること
- PHPがコマンドラインで実行できること

## プラグインの雛形を生成

`bin/console eccube:plugin:generate` コマンドでプラグインの雛形を生成できます。

```shell
bin/console eccube:plugin:generate


EC-CUBE Plugin Generator Interactive Wizard
===========================================

 name [EC-CUBE Sample Plugin]:
 > <プラグイン名を入力して Enter>

 code [Sample]:
 > <プラグインコードを入力してEnter>

 ver [1.0.0]:
 > <バージョン番号を入力してEnter>

 [OK] Plugin was successfully created: EC-CUBE Sample Plugin Sample 1.0.0
```

上記で `app/Plugin/Sample` というプラグインの雛形が生成されます。

## プラグインをインストール

`bin/console eccube:plugin:install --code=<プラグインコード>` でインストールできます。

```shell
bin/console eccube:plugin:install --code Sample

 Run bin/console cache:clear --no-warmup...

 // Clearing the cache for the dev environment with debug
 // true

 [OK] Cache for the "dev" environment (debug=true) was successfully cleared.



 [OK] Installed.
```

インストールが完了すると、EC-CUBE管理画面→オーナーズストア→プラグイン→プラグイン一覧にて確認できます。

その他、開発時に利用可能なコマンドは[こちらをご確認ください](/quickstart/cli#ec-cube%E3%81%8C%E6%8F%90%E4%BE%9B%E3%81%97%E3%81%A6%E3%81%84%E3%82%8B%E3%82%B3%E3%83%9E%E3%83%B3%E3%83%89)。

## プラグインを開発する

`app/Plugin/<プラグインコード>` のファイルを修正し、開発してください。


プラグインを削除すると、`app/Plugin/<プラグインコード>` 配下のファイルも物理的に削除されますのでご注意ください。
{: .notice--danger}

## その他

プラグインのプログラムを Git でバージョン管理したい場合は、以下の記事を参考にしてください。

[Composerを使いこなしてEC-CUBE4系プラグインの開発効率を爆上げする](https://zenn.dev/nanasess/articles/ec-cube4-plugin-development){:target="_blank"}

プラグインの E2E テストをしたい場合は、以下の記事を参考にしてください。

[10分でEC-CUBEプラグインのE2Eテストを書いてみる](https://zenn.dev/nanasess/articles/ec-cube-plugin-e2etesting-in-10mins){:target="_blank"}

オーナーズストアへ登録するプラグインのテストをしたい場合は、[次のページをご確認ください](/plugin_mock_package_api)。
