---
title: ec-cube.co カスタマイズディレクトリ利用方法
keywords: co ec-cube.co クラウド版 カスタマイズディレクトリ利用方法
tags: [co, ec-cube.co]
permalink: co/co_customize_dir_usage
folder: co
---


---

.coで動作しているEC-CUBE本体のソースコードは、EC-CUBEリポジトリの 4.0の場合 co/master、 4.1の場合 co/4.1 ブランチのソースです。 
各ブランチを利用してEC-CUBEを起動し、 app/Customize ディレクトリでのカスタマイズを行います。

## ローカル環境のセットアップ手順

### 公開鍵の設定

[SSH認証鍵](https://source.cloud.google.com/user/ssh_keys?register=true){:target="_blank"} へアクセスし、登録してください。  
その際、スタンダードプラン契約時もしくはアップグレード時にイーシーキューブへ共有いただいたGoogleアカウントでログインしていることをご確認ください。  

※詳しい設定方法については、上記ページの「詳細」リンク、もしくは[こちら](https://cloud.google.com/source-repositories/docs/authentication#ssh){:target="_blank"}よりご確認をお願いします。

#### Googleグループアカウントご利用の場合

以下のメールアドレスを例に説明します。
- google groupメールアドレス：example@googlegroups.com
- グループ内ユーザのメールアドレス：user1@example.com

1. [SSH認証鍵](https://source.cloud.google.com/user/ssh_keys?register=true){:target="_blank"} へアクセスし、公開鍵を登録します。  
  その際グループ内ユーザのメールアドレス「user1@example.com」でログインし、公開鍵を登録します。
1. git cloneでは、「user1@example.com」を指定して実行します。

### ec-cube.coのレポジトリをclone

```
$ git clone <リポジトリURL> eccube-co-customize
$ cd eccube-co-customize
```

### 追従用のec-cubeレポジトリを追加

```
$ git remote add ec-cube https://github.com/EC-CUBE/ec-cube.git
$ git fetch ec-cube
```

### 開発用ローカルブランチ作成

```
$ git checkout -b develop origin/develop
```

### co/(master もしくは 4.1) に追従する

```
$ git merge --allow-unrelated-histories ec-cube/co/(master もしくは 4.1)
```

## テスト環境・本番環境への反映方法

ブランチを.coのリポジトリにpushすることでサイトへ反映が行われます。  
反映されるまで1分程度かかります。  

※ app/Customize以外のファイルをpushすることは可能ですが、サイトへ反映される対象は、Git管理機能の仕様を参照してください。

### テスト環境に反映

```
$ git push origin develop -u
```

### 本番環境に反映

```
$ git checkout master
$ git merge develop
$ git push origin master -u
```

## ローカル環境の立ち上げ

### ローカル環境の立ち上げ

```
$ docker-compose -f docker-compose.yml -f docker-compose.pgsql.yml -f docker-compose.dev.yml up
$ docker-compose exec -u www-data ec-cube bin/console eccube:install --no-interaction
$ docker-compose exec -u www-data ec-cube bin/console eccube:generate:proxies
$ docker-compose exec -u www-data ec-cube bin/console doctrine:schema:update --force --dump-sql
$ docker-compose exec -u www-data ec-cube bin/console doctrine:migrations:migrate
```

### ブラウザで表示

```
$ open http://127.0.0.1:8080/
```

## プラグインが適用されている環境の、ローカル環境への反映方法

プラグインが適用されている環境の場合、ローカル環境へは別途プラグインをインストールする必要があります。

### 認証キーの追加

```
$ docker exec <postgres container name> psql -U dbuser eccubedb -c "update dtb_base_info set authentication_key='<認証キー>' "
```

### プラグインのインストール・有効化

```
$ docker-compose exec -u www-data ec-cube bin/console eccube:composer:require ec-cube/<プラグインコード>
$ docker-compose exec -u www-data ec-cube bin/console eccube:plugin:enable --code=<プラグインコード>
```
