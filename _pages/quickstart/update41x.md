---
layout: single
title: 4.1 本体バージョンアップ
keywords: howto update
tags: [quickstart, getting_started]
permalink: update41x
summary : 4.1.x の本体バージョンアップ手順について記載します。
---

本番環境でバージョンアップを行う前に、テスト環境で事前検証を必ず行ってください。
{: .notice--danger}
この手順では、ec-cube.netからダウンロードしたEC-CUBEのパッケージを利用していることを想定しています。
{: .notice--danger}
EC-CUBE本体のコード(app/config/eccube, app/DoctrineMigrations, bin, src, htmlディレクトリ)をカスタマイズしている場合、ファイルが上書きされてしまうため、この手順ではバージョンアップできません。[各バージョンでの変更差分](#各バージョンでの変更差分)を確認して必要な差分を取り込んでください。
{: .notice--danger}
2022年2月21日に公開された「HTTP Hostヘッダの処理に脆弱性」は、EC-CUBEのバージョンアップを行っても修正されません。[脆弱性詳細ページ](https://www.ec-cube.net/info/weakness/20220221/)を参考に、適切な設定を行ってください。
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
| 4.1.0 → 4.1.1        | composer.json<br>composer.lock<br>.htaccess<br>index.php<br>symfony.lock<br>package.json<br>package-lock.json|
| 4.1.1 → 4.1.2        | composer.json<br>composer.lock<br>.htaccess<br>index.php<br>symfony.lock<br>package.json<br>package-lock.json|
| 4.1.2 → 4.1.2-p1        | -|

※ FTP等でファイルをアップロードするとパーミッションが変更される可能性があります。[パーミッションの設定について](/quickstart/permission)を参考にパーミッションの確認をお願いします。

上書き後、以下のコマンドでキャッシュの削除を行ってください。

```
bin/console cache:clear --no-warmup
```

### 6. composer.json/composer.lockの更新

以下のコマンドを実行してください。

```
bin/console eccube:composer:require-already-installed
```

packagist等の外部ライブラリを独自にインストールしている場合は、再度requireしてください。

例えば、psr/http-messageをインストールしている場合は、以下のコマンドを実行してください。

```
composer require psr/http-message
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

#### 4.1.0 → 4.1.1

<a href="https://github.com/EC-CUBE/ec-cube/pulls?q=is%3Apr+label%3Aaffected%3Atemplate+is%3Aclosed+milestone%3A4.1.1" target = "_blank">フロントテンプレートファイルの差分</a>

#### 4.1.1 → 4.1.2

<a href="https://github.com/EC-CUBE/ec-cube/pulls?q=is%3Apr+label%3Aaffected%3Atemplate+is%3Aclosed+milestone%3A4.1.2" target = "_blank">フロントテンプレートファイルの差分</a>

### 9.メンテナンスモードを無効にする

EC-CUBEの管理画面へアクセスし、「コンテンツ管理」の「メンテナンス管理」から、メンテナンスモードを無効にしてください。

または、EC-CUBEのルートディレクトリに「.maintenance」ファイルを削除することでメンテナンスモードを無効にすることもできます。

---

EC-CUBEのバージョンアップ手順は以上です。

## 各バージョンでの変更差分

バージョンごとの詳細な変更差分は、以下のリンク先で確認することができます。

| バージョン      | 差分ページ                                                                                                             |
|-----------------|------------------------------------------------------------------------------------------------------------------------|
| 4.1.0 → 4.1.1   | [https://github.com/EC-CUBE/ec-cube/compare/4.1.0...4.1.1](https://github.com/EC-CUBE/ec-cube/compare/4.1.0...4.1.1?w=1#files_bucket){:target="_blank"}   |
| 4.1.1 → 4.1.2   | [https://github.com/EC-CUBE/ec-cube/compare/4.1.1...4.1.2](https://github.com/EC-CUBE/ec-cube/compare/4.1.1...4.1.2?w=1#files_bucket){:target="_blank"}   |
| 4.1.2 → 4.1.2-p1   | [https://github.com/EC-CUBE/ec-cube/compare/4.1.2...4.1.2-p1](https://github.com/EC-CUBE/ec-cube/compare/4.1.2...4.1.2-p1?w=1#files_bucket){:target="_blank"}   |

