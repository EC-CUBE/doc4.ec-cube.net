---
layout: single
title: 4.0から4.1への本体バージョンアップ
keywords: howto update
tags: [quickstart, getting_started]
permalink: update41
summary : EC-CUBE4.0から4.1への本体バージョンアップ手順について記載します。
---

本番環境でバージョンアップを行う前に、テスト環境で事前検証を必ず行ってください。
{: .notice--danger}
この手順では、ec-cube.netからダウンロードしたEC-CUBEのパッケージを利用していることを想定しています。
{: .notice--danger}
この手順では、EC-CUBE4.0.6-p1から4.1.0へのバージョンアップを想定しています。
{: .notice--danger}
EC-CUBE本体のコード(app/config/eccube, app/DoctrineMigrations, bin, src, htmlディレクトリ)をカスタマイズしている場合、ファイルが上書きされてしまうため、この手順ではバージョンアップできません。[各バージョンでの変更差分](#各バージョンでの変更差分)を確認して必要な差分を取り込んでください。
{: .notice--danger}

## 事前準備

### プラグインの対象バージョンの確認

- プラグインをインストールしている場合、EC-CUBE4.1に対応しているかどうかをご確認ください。
- 4.1へのバージョンアップを実施する前に、使用されているプラグインを 4.1対応バージョンにアップデートしてください。

### カスタマイズや独自プラグインを利用している場合のマイグレーション

- Customize領域を使ったカスタマイズや、独自プラグインを利用している場合は、[EC-CUBE4.0から4.1へのマイグレーション](/update-40-41)を参考に、4.1対応を行ってください。

### 本体に取り込まれたプラグインの無効化

- 4.1.0より、以下のプラグインはEC-CUBE本体へ取り込まれました。
  - [taba secure 2段階認証 プラグイン for EC-CUBE 4](https://www.ec-cube.net/products/detail.php?product_id=1750)
- 該当のプラグインを利用している場合は、プラグインを削除してください。

## アップデートプラグインを利用したバージョンアップ方法

- 4系のEC-CUBEのバージョンアップには、アップデートプラグインをご利用いただけます。
- アップデートプラグインは、お使いのEC-CUBEの管理画面の「オーナーズストア/プラグインを探す」から「EC-CUBEアップデートプラグイン」で検索を行い、ご利用ください。

アップデートプラグインでバージョンアップを行なった場合は、以下の手順は必要ありません。
{: .notice--info}

## 作業の流れ

1. サイトのバックアップ
1. メンテナンスモードを有効にする
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

### 2.メンテナンスモードを有効にする（バージョン4.0.1以降）

EC-CUBEの管理画面へアクセスし、「コンテンツ管理」の「メンテナンス管理」から、メンテナンスモードを有効にしてください。

または、EC-CUBEのルートディレクトリに「.maintenance」ファイルを設置することでメンテナンスモードを有効にすることもできます。

```
[root]
  │
  ├──.maintenance
  │
```

※ メンテナンスモード使用時は、管理画面以外のページにアクセスするとメンテナンス画面が表示されます。

### 3. EC-CUBEのソースファイルをバージョンアップしたものに置き換え

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

### 4. 個別ファイル差し替え

下記の差し替え対象ファイルを確認して最新のファイルで上書きしてください。

- composer.json
- composer.lock
- .htaccess
- symfony.lock
- index.php

### 5. composer.json/composer.lockの更新

packagist等の外部ライブラリを独自にインストールしている場合は、再度requireしてください。

例えば、psr/http-messageをインストールしている場合は、以下のようにインストールしてください。

```
composer require psr/http-message --no-plugins --no-scripts
```

Symfony Bundleを使ったプラグインを利用している場合、プラグインのcomposer.jsonを確認し、依存しているライブラリをインストールしてください。

例えば、APIプラグインの場合は、以下のようにcomposer.jsonを確認し、依存ライブラリをインストールしてください。

```
$ cat app/Plugin/Api/composer.json
...
  "require": {
    "ec-cube/plugin-installer": "~0.0.6 || ^2.0",
    "trikoder/oauth2-bundle": "^2.1",
    "nyholm/psr7": "^1.2",
    "webonyx/graphql-php": "^14.0"

$ composer require trikoder/oauth2-bundle:^2.1 --no-plugins --no-scripts
$ composer require nyholm/psr7:^1.2 --no-plugins --no-scripts
$ composer require webonyx/graphql-php:^14.0 --no-plugins --no-scripts
```

以下のコマンドでキャッシュの削除を行ってください。

```
bin/console cache:clear --no-warmup
```

以下のコマンドを実行してください。

```
bin/console eccube:composer:require-already-installed
```

### 6. スキーマ更新/マイグレーション

スキーマ更新およびマイグレーション機能を利用して、データベースのバージョンアップを行います。

以下のコマンドを実行してください。

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

4.0.6-p1から4.1.0への変更ファイル一覧は以下のとおりです。
変更対象の差分は、[変更差分](#変更差分)からご確認いただけます。

- src/Eccube/Resource/template/admin/default_frame.twig
- src/Eccube/Resource/template/default/Contact/confirm.twig
- src/Eccube/Resource/template/default/Contact/index.twig
- src/Eccube/Resource/template/default/Entry/confirm.twig
- src/Eccube/Resource/template/default/Entry/index.twig
- src/Eccube/Resource/template/default/Mail/contact_mail.twig
- src/Eccube/Resource/template/default/Mail/customer_withdraw_mail.twig
- src/Eccube/Resource/template/default/Mail/entry_complete.twig
- src/Eccube/Resource/template/default/Mail/entry_confirm.twig
- src/Eccube/Resource/template/default/Mail/forgot_mail.twig
- src/Eccube/Resource/template/default/Mail/order.html.twig
- src/Eccube/Resource/template/default/Mail/order.twig
- src/Eccube/Resource/template/default/Mail/reset_complete_mail.twig
- src/Eccube/Resource/template/default/Mail/shipping_notify.twig
- src/Eccube/Resource/template/default/Mypage/change.twig
- src/Eccube/Resource/template/default/Mypage/delivery_edit.twig
- src/Eccube/Resource/template/default/Mypage/index.twig
- src/Eccube/Resource/template/default/Product/detail.twig
- src/Eccube/Resource/template/default/Product/list.twig
- src/Eccube/Resource/template/default/Shopping/shipping_edit.twig
- src/Eccube/Resource/template/default/Shopping/shipping_multiple_edit.twig
- src/Eccube/Resource/template/default/default_frame.twig
- src/Eccube/Resource/template/default/meta.twig
- src/Eccube/Resource/template/default/sitemap.xml.twig
- src/Eccube/Resource/template/default/sitemap_index.xml.twig

### 9.メンテナンスモードを無効にする

EC-CUBEの管理画面へアクセスし、「コンテンツ管理」の「メンテナンス管理」から、メンテナンスモードを無効にしてください。

または、EC-CUBEのルートディレクトリに「.maintenance」ファイルを削除することでメンテナンスモードを無効にすることもできます。

## 変更差分

4.0.6-p1から4.1.0への詳細な変更差分は、以下のリンク先で確認することができます。

[https://github.com/EC-CUBE/ec-cube/compare/4.0.6-p1...4.1.0](https://github.com/EC-CUBE/ec-cube/compare/4.0.6-p1...4.1.0?w=1#files_bucket){:target="_blank"}
