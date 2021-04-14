---
layout: single
title: インストール方法
keywords: install
tags: [quickstart, install, docker, docker-compose]
permalink: quickstart/install_docker-compose_405orlower
folder: quickstart
description: Docker Composeを使用してインストールする場合(EC-CUBE4.0.5以下)の説明です。
---
### Docker Composeを使用してインストールする(EC-CUBE4.0.5以下の場合)

**開発環境として関連サービス(DB、メールデバッグ環境等)も含め手軽に一括構築したい場合におすすめの方法です**

前提として、 [Docker Desktop のインストール](https://hub.docker.com){:target="_blank"} が必要です。

+ 初期状態では SQLite3 を使用します
+ ローカルディレクトリをマウントします


```shell
cd path/to/ec-cube

# コンテナの起動 (初回のみビルド処理あり)
docker-compose up -d

# 初回はインストールスクリプトを実行( **`www-data` ユーザで実行する点に注意！** )
docker-compose exec -u www-data ec-cube bin/console eccube:install
```

2回目以降の起動時も同様のコマンドを使用します。
```shell
# コンテナの起動
docker-compose up -d

# コンテナの停止
docker-compose down
```

#### 各種コンテナの使用
EC-CUBE 4が動作するWebサーバを含め、以下のコンテナが簡単に起動できます。

| コンテナ名  | 概要                             | ブラウザアクセス例 |
| ----------- | -------------------------------- | -------------------------- |
| ec-cube     | EC-CUBE 向けPHP Webサーバ        | [http://localhost:8080](http://localhost:8080){:target="_blank"}      |
| postgres    | PostgreSQLデータベースサーバ     |                            |
| mysql       | MySQLデータベースサーバ          |                            |
| mailcatcher | MailCatcher デバッグ用SMTPサーバ | [http://localhost:1080](http://localhost:1080){:target="_blank"}      |

起動時にコンテナ名を列挙することで、各種コンテナを起動します。
```shell
# 例：EC-CUBEとMySQLとphpMyAdminとMailCatcherを起動する
docker-compose up -d ec-cube mysql mailcatcher

# 省略した場合はすべてのサービスが起動します
docker-compose up -d
```
各種コンテナと連携させる場合は、以下の通り設定が必要です。
##### メール送信を使用する場合
`.env` にて `MAILER_URL=smtp://mailcatcher:1025` としておきます。

##### PostgreSQL を使用する場合
`.env` にて `DATABASE_URL=postgres://dbuser:secret@postgres/eccubedb` としておきます。

データベーススキーマを初期化していない場合は、以下の実行が必要です。
```
# スキーマ作成+初期データ投入
docker-compose exec ec-cube composer run-script compile
```

##### MySQL を使用する場合
`.env` にて `DATABASE_URL=mysql://dbuser:secret@mysql/eccubedb` としておきます。

データベーススキーマを初期化していない場合は、以下の実行が必要です。
```
# スキーマ作成+初期データ投入
docker-compose exec ec-cube composer run-script compile
```


#### ファイルの同期

docker-composeを用いてインストールした場合、ホストのローカルディレクトリとコンテナ上のファイルは同期します。`.env`等の設定ファイルについても、ホスト上のファイルを直接編集します。

なお、一部環境において著しいパフォーマンスの劣化が発生する場合があるため、以下のフォルダは同期の対象から除外しています。
 - /var
 - /vendor  

上記除外対象のフォルダについてはDocker Volumeを用いて別途永続化を行っています。
