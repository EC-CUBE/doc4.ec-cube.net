---
title: Docker Composeを使用してインストールする
keywords: install Docker docker-composer
tags: [quickstart, install, docker, docker-compose]
permalink: quickstart/docker_compose_install
folder: quickstart
---


---

[EC-CUBE4.0.5以下のバージョンをお使いの場合はこちらをご覧ください。](/quickstart/install_docker-compose_405orlower)

**開発環境として関連サービス(DB、メールデバッグ環境等)も含め手軽に一括構築したい場合におすすめの方法です**

前提として、 [Docker Desktop のインストール](https://hub.docker.com){:target="_blank"} が必要です。

+ 初期状態では SQLite3 を使用します

```shell
cd path/to/ec-cube

# コンテナの起動 (初回のみビルド処理あり)
docker-compose up -d

# 初回はインストールスクリプトを実行( **`www-data` ユーザで実行する点、非対話モードを使用する点に注意！** )
docker-compose exec -u www-data ec-cube bin/console eccube:install -n
```

2回目以降の起動時も同様のコマンドを使用します。

```shell
# コンテナの起動
docker-compose up -d

# コンテナの停止
docker-compose down
```

docker-compose を使用したインストールでは、基本的な設定は `.env` ではなく、 `docker-compose.yml` の `environment` の項目で設定します。(詳細は[.env の使用について](#env-の使用について)の項目をご覧ください。)
**また、 `eccube:install` コマンドの対話モードは使用できません。必ず非対話モード(`-n` オプション)を付与してください。**

各種 `docker-compose.*.yml` を指定することで、ローカルディレクトリをマウントしたり、データベースを変更することができます。

#### PostgreSQL を使用する場合

`docker-compose.pgsql.yml` を指定します。

``` shell
docker-compose -f docker-compose.yml -f docker-compose.pgsql.yml up -d
```

データベーススキーマを初期化していない場合は、以下の実行が必要です。

```
# スキーマ作成+初期データ投入
docker-compose -f docker-compose.yml -f docker-compose.pgsql.yml exec ec-cube composer run-script compile
```

#### MySQL を使用する場合

`docker-compose.mysql.yml` を指定します。

``` shell
docker-compose -f docker-compose.yml -f docker-compose.mysql.yml up -d
```

データベーススキーマを初期化していない場合は、以下の実行が必要です。

```
# スキーマ作成+初期データ投入
docker-compose -f docker-compose.yml -f docker-compose.mysql.yml exec ec-cube composer run-script compile
```

#### ローカルディレクトリをマウントする場合

`docker-compose.dev.yml` を指定します。

```
## MySQL を使用する例
docker-compose -f docker-compose.yml -f docker-compose.mysql.yml -f docker-compose.dev.yml up -d
```

#### .env の使用について

docker-compose を使用したインストールでは、 `DATABASE_URL` などの各種環境変数は `docker-compose.*.yml` の `environment` の項目で設定します。

`.env` を使用したい場合は、以下のように設定し ec-cube コンテナを up することで利用できます。

1. `docker-compose*.yml` で `APP_ENV: ~` とする
1. ローカルディレクトリの `.env` の `APP_ENV` をコメントアウトする

各種環境変数の設定される優先順位は以下の通りです。

1. `docker-compose*.yml` の `environment`
1. ローカルディレクトリの `.env` (phpdotenv ではなく docker-compose 経由で設定される)
1. ec-cube コンテナの `.env` (phpdotenv で設定される)

#### ファイルの同期

docker-composeを用いてインストールした場合、ホストのローカルディレクトリとコンテナ上のファイルは同期します。`.env`等の設定ファイルについても、ホスト上のファイルを直接編集します。

なお、一部環境において著しいパフォーマンスの劣化が発生する場合があるため、以下のフォルダは同期の対象から除外しています。
 - /var
 - /vendor
 - /node_modules

上記除外対象のフォルダについてはDocker Volumeを用いて別途永続化を行っています。
