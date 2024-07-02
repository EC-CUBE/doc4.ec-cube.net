---
layout: single
title: 4.2から4.3への本体バージョンアップ
keywords: howto update
tags: [quickstart, getting_started]
permalink: update43
summary : EC-CUBE4.2から4.3への本体バージョンアップ手順について記載します。
---

本番環境でバージョンアップを行う前に、テスト環境で事前検証を必ず行ってください。
{: .notice--danger}
この手順では、ec-cube.netからダウンロードしたEC-CUBEのパッケージを利用していることを想定しています。
{: .notice--danger}
この手順では、EC-CUBE4.2.3から4.3.0へのバージョンアップを想定しています。
{: .notice--danger}
EC-CUBE本体のコード(app/config/eccube, app/DoctrineMigrations, bin, src, htmlディレクトリ)をカスタマイズしている場合、ファイルが上書きされてしまうため、この手順ではバージョンアップできません。[各バージョンでの変更差分](#変更差分)を確認して必要な差分を取り込んでください。
{: .notice--danger}

## 事前準備

### プラグインの対象バージョンの確認

- プラグインをインストールしている場合、EC-CUBE4.3に対応しているかどうかをご確認ください。
- 4.3へのバージョンアップを実施する前に、使用されているプラグインを 4.3対応バージョンにアップデートしてください。

### カスタマイズや独自プラグインを利用している場合のマイグレーション

- Customize領域を使ったカスタマイズや、独自プラグインを利用している場合は、[EC-CUBE4.2から4.3へのマイグレーション](/update-42-43)を参考に、4.3対応を行ってください。

## 作業の流れ

1. サイトのバックアップ
1. メンテナンスモードを有効にする
1. プラグインのアップデート
1. EC-CUBEのソースファイルをバージョンアップしたものに置き換え
1. 個別ファイル差し替え
1. composer.json/composer.lockの更新
1. スキーマ更新/マイグレーション
1. キャッシュ等の再生成
1. フロントテンプレートファイルの更新
1. メンテナンスモードを無効にする

## 手順詳細

### 1. サイトのバックアップ

EC-CUBEのインストールディレクトリ以下をすべてバックアップしてください。

お使いのデータベースも全てバックアップしてください。

### 2. メンテナンスモードを有効にする

EC-CUBEの管理画面へアクセスし、「コンテンツ管理」の「メンテナンス管理」から、メンテナンスモードを有効にしてください。

または、EC-CUBEのルートディレクトリに「.maintenance」ファイルを設置することでメンテナンスモードを有効にすることもできます。

```
[root]
  │
  ├──.maintenance
  │
```

※ メンテナンスモード使用時は、管理画面以外のページにアクセスするとメンテナンス画面が表示されます。

### 3. プラグインのアップデート

インストール済みのプラグインのうち、アップデート可能なものがあれば、事前にアップデートを行ってください。

### 4. EC-CUBEのソースファイルをバージョンアップしたものに置き換え

EC-CUBEのソースファイルについて、ディレクトリごとにそれぞれバージョンアップしたソースファイルに置き換えていきます。置き換える対象のディレクトリとなるのは、今回のバージョンアップで変更となったものとなります。
（`app/config/eccube` `app/DoctrineMigrations` `bin` `src` `html` `vendor` など）

#### `vendor` ディレクトリ以外の置き換え

置き換える対象となるディレクトリを削除し、バージョンアップするEC-CUBEのバージョンのディレクトリにそれぞれ置き換えてください。

（対象ディレクトリの上書きではなくディレクトリの中のすべてのファイルを置き換える必要があります。古いファイルが残ってしまうと、予期せぬ動作となる恐れがありますので、必ず置き換える対象となるディレクトリごとにすべてのファイルを置き換えるようにしてください。）

```
[root]
  │
  ├──[app/config/eccube]
  ├──[app/DoctrineMigrations]
  ├──[bin]
  ├──[src]
  ├──[html]
  │
```

#### `vendor` ディレクトリの置き換え

`vendor` ディレクトリは削除せず、バージョンアップするEC-CUBEのバージョンの `vendor` ディレクトリで上書きしてください。

（ Web API プラグインなど Symfony bundle を利用の場合に `vendor` ディレクトリを完全に削除してしまうと以降の手順でエラーが発生します。ステップ(5) の `bin/console eccube:composer:require-already-installed` コマンドで `vendor` ディレクトリ内の不要なファイルは削除されます。）

```
[root]
  │
  ├──[vendor]
  │
```

### 5. 個別ファイル差し替え

下記の差し替え対象ファイルを確認して最新のファイルで上書きしてください。

- composer.json
- composer.lock
- package.json
- package-lock.json
- index.php

### 6. composer.json/composer.lockの更新

packagist等の外部ライブラリを独自にインストールしている場合は、再度requireしてください。

例えば、psr/http-messageをインストールしている場合は、以下のようにインストールしてください。

```
composer require psr/http-message --no-plugins --no-scripts
```

Symfony Bundleを使ったプラグインを利用している場合、プラグインのcomposer.jsonを確認し、依存しているライブラリをインストールしてください。

例えば、APIプラグインの場合は、以下のようにcomposer.jsonを確認し、依存ライブラリをインストールしてください。

```
$ cat app/Plugin/Api42/composer.json
...
  "require": {
    "ec-cube/plugin-installer": "^2.0",
    "league/oauth2-server-bundle": "^0.5",
    "nyholm/psr7": "^1.2",
    "php-http/message-factory": "*",
    "webonyx/graphql-php": "^14.0"

$ composer require league/oauth2-server-bundle:^0.5 --no-plugins --no-scripts
$ composer require nyholm/psr7:^1.2 --no-plugins --no-scripts
$ composer require php-http/message-factory --no-plugins --no-scripts
$ composer require webonyx/graphql-php:^14.0 --no-plugins --no-scripts
```

以下のコマンドでキャッシュの削除を行ってください。

```
rm -rf var
bin/console cache:clear --no-warmup
```

以下のコマンドを実行してください。

```
bin/console eccube:composer:require-already-installed
```

### 6. スキーマ更新/マイグレーション

スキーマ更新およびマイグレーション機能を利用して、データベースのバージョンアップを行います。

以下のコマンドを実行してください。

<span style="color:#ff0000;">
※スキーマ更新のコマンドを実行した際に以下のエラーが発生する場合があります。
</span>

`In Eccube_KernelProdContainer.php line 1936:
Attempted to call an undefined method named "registerUniqueLoader" of class
"Doctrine\Common\Annotations\AnnotationRegistry".`


このエラーは、Symfonyのデータの内容が変更されたが、Symfonyのキャッシュ構成が古いままであるために発生します。
問題を解決するためには、キャッシュやログファイルなどが存在するvarフォルダを削除する必要があります。
以下のコマンドでvarフォルダを削除し、スキーマ更新を再実行してください。

varフォルダの削除

```
rm -rf var
```


スキーマ更新

```
bin/console doctrine:schema:update --force --dump-sql
```

マイグレーション

```
bin/console doctrine:migrations:migrate
```

### 7. キャッシュ等の再生成

autoloadファイルの再生成
```
composer dump-autoload
```

プロキシファイルを再生成
```
bin/console eccube:generate:proxies
```

キャッシュファイルの再生成
```
bin/console cache:warmup --env=prod
```

セッションの削除
```
rm -rf var/sessions
```

### 8. フロントテンプレートファイルの更新

対象となるバージョンごとに、フロントテンプレートファイル(twig)の更新が必要です。

管理画面のコンテンツ管理もしくは店舗設定＞メール設定から、該当するページ/ブロック/メールテンプレートを編集してください。

4.2.3から4.3.0への変更ファイル一覧は以下のとおりです。
変更対象の差分は、[変更差分](#変更差分)からご確認いただけます。

- src/Eccube/Resource/template/admin/Content/news_edit.twig
- src/Eccube/Resource/template/admin/Order/index.twig
- src/Eccube/Resource/template/admin/Order/shipping.twig
- src/Eccube/Resource/template/admin/Product/category.twig
- src/Eccube/Resource/template/admin/Product/class_category.twig
- src/Eccube/Resource/template/admin/Product/class_name.twig
- src/Eccube/Resource/template/admin/Product/csv_class_category.twig
- src/Eccube/Resource/template/admin/Product/csv_class_name.twig
- src/Eccube/Resource/template/admin/Product/product_class.twig
- src/Eccube/Resource/template/admin/Setting/Shop/mail.twig
- src/Eccube/Resource/template/admin/Setting/Shop/payment_edit.twig
- src/Eccube/Resource/template/admin/Setting/Shop/shop_master.twig
- src/Eccube/Resource/template/admin/Setting/System/system.twig
- src/Eccube/Resource/template/admin/Store/plugin_table.twig
- src/Eccube/Resource/template/admin/change_password.twig
- src/Eccube/Resource/template/admin/default_frame.twig
- src/Eccube/Resource/template/admin/error.twig
- src/Eccube/Resource/template/admin/info.twig
- src/Eccube/Resource/template/admin/notice_debug_mode.twig
- src/Eccube/Resource/template/default/Block/auto_new_item.twig
- src/Eccube/Resource/template/default/Block/google_analytics.twig
- src/Eccube/Resource/template/default/Block/new_item.twig
- src/Eccube/Resource/template/default/Cart/index.twig
- src/Eccube/Resource/template/default/Shopping/alert.twig
- src/Eccube/Resource/template/default/Shopping/shipping.twig
- src/Eccube/Resource/template/default/default_frame.twig

### 9.メンテナンスモードを無効にする

EC-CUBEの管理画面へアクセスし、「コンテンツ管理」の「メンテナンス管理」から、メンテナンスモードを無効にしてください。

または、EC-CUBEのルートディレクトリに「.maintenance」ファイルを削除することでメンテナンスモードを無効にすることもできます。

## 変更差分

4.2.3から4.3.0への詳細な変更差分は、以下のリンク先で確認することができます。

[https://github.com/EC-CUBE/ec-cube/compare/4.2.3...4.3.0](https://github.com/EC-CUBE/ec-cube/compare/4.2.3...4.3.0?w=1#files_bucket){:target="_blank"}
