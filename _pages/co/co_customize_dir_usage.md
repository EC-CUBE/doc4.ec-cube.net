---
title: ec-cube.co カスタマイズディレクトリ利用方法
keywords: co ec-cube.co クラウド版 カスタマイズディレクトリ利用方法
tags: [co, ec-cube.co]
permalink: co/co_customize_dir_usage
folder: co
---


---

.coで動作しているEC-CUBE本体のソースコードは、EC-CUBEリポジトリの co/master ブランチのソースです。 
co/master ブランチを利用してEC-CUBEを起動し、 app/Customize ディレクトリでのカスタマイズを行います。

## ローカル環境のセットアップ手順

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

### co/master に追従する

```
$ git merge --allow-unrelated-histories ec-cube/co/master
```

## ステージング環境・本番環境への反映方法

ブランチを.coのリポジトリにpushすることでサイトへ反映が行われます。  
反映されるまで1分程度かかります。  
※ 「変更可能範囲外」のファイルをpushすることは可能ですが、サイトへは反映されません。

### ステージング環境に反映

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
