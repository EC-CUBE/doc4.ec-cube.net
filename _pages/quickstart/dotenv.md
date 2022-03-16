---
layout: single
title: 本番環境での .env ファイル利用
keywords: install
tags: [quickstart, install, dotenv]
permalink: quickstart/dotenv
folder: quickstart
description: 本番環境での .env ファイルの利用についての説明です。
---

## 本番環境での .env ファイルの利用について

インストール完了後、インストールディレクトリにデータベースの接続情報等が設定された **.env** ファイルが生成されます。
**.env** ファイルは、開発用途での環境変数を設定するためのものであり、本番環境での使用は推奨されません。
本番環境では、環境変数をサーバ設定ファイルに設定することを推奨します。
サーバ設定ファイルに環境変数を設定することにより、環境変数が外部に暴露される危険性が減り、安全に運用できます。

### Apache での設定例

httpd.conf や、 .htaccess ファイルに設定します。

```
SetEnv APP_ENV prod
SetEnv APP_DEBUG 0
SetEnv DATABASE_URL pgsql://dbuser:password@127.0.0.1/cube4_dev
SetEnv DATABASE_SERVER_VERSION 10.5
SetEnv ECCUBE_AUTH_MAGIC 8PPlCHZVdH5vbMkIUKeuTeDHycQQMuaB
SetEnv ECCUBE_ADMIN_ALLOW_HOSTS []
SetEnv ECCUBE_FORCE_SSL false
SetEnv ECCUBE_ADMIN_ROUTE admin
SetEnv ECCUBE_COOKIE_PATH /
```

[参考: Apache HTTP サーバ バージョン 2.4 - SetEnv ディレクティブ](https://httpd.apache.org/docs/2.4/ja/mod/mod_env.html#setenv){:target="_blank"}

### サーバ設定ファイルに環境変数を設定した場合の注意事項

サーバ設定ファイルに環境変数を設定した場合、 以下の機能を管理画面から設定することができません。

**サーバ設定ファイルの環境変数を変更し、キャッシュクリアする必要がありますのでご注意ください。**

- 管理画面→オーナーズストア→テンプレート
- 管理画面→設定→システム設定→セキュリティ管理

### bin/console コマンドの使用について

`bin/console` コマンドを使用する場合、通常は .env ファイルから環境変数を取得します。
.env を使用しない場合は、 shell の環境変数に設定をします。
この場合、 `.bashrc` などの shell の設定ファイルのパーミッションは 600 など、オーナー以外は読み書きできない設定にしておくことを強く推奨します。

```
## .bashrc の設定例
export APP_ENV="prod"
export APP_DEBUG="0"
export DATABASE_URL="pgsql://dbuser:password@127.0.0.1/cube4_dev"
export DATABASE_SERVER_VERSION="10.5"
export ECCUBE_AUTH_MAGIC="8PPlCHZVdH5vbMkIUKeuTeDHycQQMuaB"
export ECCUBE_ADMIN_ALLOW_HOSTS="[]"
export ECCUBE_FORCE_SSL="false"
export ECCUBE_ADMIN_ROUTE="admin"
export ECCUBE_COOKIE_PATH="/"
```

