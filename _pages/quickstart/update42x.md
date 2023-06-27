---
layout: single
title: 4.2 本体バージョンアップ
keywords: howto update
tags: [quickstart, getting_started]
permalink: update42x
summary : 4.2.x の本体バージョンアップ手順について記載します。
---

本番環境でバージョンアップを行う前に、テスト環境で事前検証を必ず行ってください。
{: .notice--danger}
この手順では、ec-cube.netからダウンロードしたEC-CUBEのパッケージを利用していることを想定しています。
{: .notice--danger}


## 作業の流れ
1. サイトのバックアップ
1. メンテナンスモードを有効にする
1. プラグインのアップデート
1. EC-CUBEのソースファイルをバージョンアップしたものに置き換え
1. 個別ファイル差し替え
1. composer.json/composer.lockの更新
1. スキーマ更新/マイグレーション
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

対象となるバージョンごとに、個別のファイル差し替えが必要です。

下記から差し替え対象ファイルを確認して最新のファイルで上書きしてください。

| バージョンアップ対象 | 差し替え対象ファイル                                                                              |
|----------------------|---------------------------------------------------------------------------------------------------|
| 4.2.0 → 4.2.1        | composer.json<br>composer.lock<br>package.json<br>package-lock.json|
---------------------------------------------------------------------------------------------------|
| 4.2.1 → 4.2.2        | package.json<br>package-lock.json|

※ FTP等でファイルをアップロードするとパーミッションが変更される可能性があります。[パーミッションの設定について](/quickstart/permission)を参考にパーミッションの確認をお願いします。

### 6. composer.json/composer.lockの更新
※ 4.2.2へのバージョンアップ時に、手順の入れ替えが必要となります。
「7. スキーマ更新/マイグレーション」の手順を先に実行して、「6. composer.json/composer.lockの更新」へと進んでください。
通常の手順だと「6. composer.json/composer.lockの更新」のステップでrequire-already-installedが実行されます。
この時、dtb_base_infoにアクセスするため、エラーが発生します。上記の手順の入れ替えることで、エラーの回避ができます。

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

### 7. スキーマ更新/マイグレーション

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

### 8. フロントテンプレートファイルの更新

対象となるバージョンごとに、フロントテンプレートファイル(twig)の更新が必要です。

管理画面のコンテンツ管理もしくは店舗設定＞メール設定から、該当するページ/ブロック/メールテンプレートを編集してください。

変更対象の差分は、以下リンクからご確認いただくが[各バージョンでの変更差分](#各バージョンでの変更差分)からご確認いただけます。

#### 4.2.0 → 4.2.1
<a href="https://github.com/EC-CUBE/ec-cube/pulls?q=is%3Apr+label%3Aaffected%3Atemplate+is%3Aclosed+milestone%3A4.2.1+" target = "_blank">フロントテンプレートファイルの差分</a>

#### 4.2.1 → 4.2.2
4.2.2ではフロントテンプレートの変更はありません。

### 9.メンテナンスモードを無効にする

EC-CUBEの管理画面へアクセスし、「コンテンツ管理」の「メンテナンス管理」から、メンテナンスモードを無効にしてください。

または、EC-CUBEのルートディレクトリに「.maintenance」ファイルを削除することでメンテナンスモードを無効にすることもできます。

---

EC-CUBEのバージョンアップ手順は以上です。

## 各バージョンでの変更差分

バージョンごとの詳細な変更差分は、以下のリンク先で確認することができます。

| バージョン      | 差分ページ                                                                                                             |
|-----------------|------------------------------------------------------------------------------------------------------------------------|
| 4.2.0 → 4.2.1   | [https://github.com/EC-CUBE/ec-cube/compare/4.2.0...4.2.1](https://github.com/EC-CUBE/ec-cube/compare/4.2.0...4.2.1?w=1#files_bucket){:target="_blank"}   |
| 4.2.1 → 4.2.2   | [https://github.com/EC-CUBE/ec-cube/compare/4.2.1...4.2.2](https://github.com/EC-CUBE/ec-cube/compare/4.2.1...4.2.2?w=1#files_bucket){:target="_blank"}   |
