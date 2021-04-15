---
title: Tips
keywords: Tips tips つまづいた 困った
tags: [tips]
permalink: reverse-lookup/tips
folder: reverse-lookup
---


---

ここではちょっと開発につまづいた時に役に立ちそうなTipsを挙げていきます。

## テーブルにカラムを追加したい

Entityを編集後、以下のスキーマ更新のコマンドを実行してください。

```
$ cd {EC-CUBEディレクトリのパス}
$ bin/console doctrine:schema:update --force --dump-sql
```

## スキーマ更新のコマンドを実行したのだけれど、テーブルに反映されない

どうやらDoctolinのキャッシュが残ってしまっているようです。  
以下のコマンドでキャッシュを削除してください。

```
$ cd {EC-CUBEディレクトリのパス}
$ rm -rf var/cache
```

## テーブルに登録されたデータを確認してみたら、日時のデータがなんだかズレている？

EC−CUBE本体とDBのタイムゾーン設定のドキュメントを読んでみると謎がとけます。

[タイムゾーン](/i18n_timezone)

## 本体インストール時やアップグレード時に、テーブルへデータ登録しておきたい場合はどうすればいい？

### 本体アップグレード時

マイグレーションファイルの追加が必要になります。  
マイグレーションファイルはインストール時にも実行されてしまうらしいので、データ重複回避策が必要です。

![migration_versionsテーブル](/images/reverse-lookup/migration_versions.png)

マイグレーションファイルはmigration_versionsテーブルで管理されます。  
versionカラムにマイグレーションファイル名の日時部分が登録されます。

[マイグレーションファイルの例](https://github.com/EC-CUBE/ec-cube/blob/4.0/app/DoctrineMigrations/Version20201218044542.php){:target="_blank"}

### 本体インストール時

csvファイルへのレコード追加が必要になります。

[インポートCSV](https://github.com/EC-CUBE/ec-cube/tree/4.0/src/Eccube/Resource/doctrine/import_csv){:target="_blank"}
