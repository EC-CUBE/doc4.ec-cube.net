---
layout: single
title: インストール方法
keywords: install
tags: [quickstart, install]
permalink: quickstart_install
folder: quickstart/quickstart_install
description: EC-CUBE 4系のインストールについての説明です。
---

## 初心者向けインストール方法

初心者の方はこのまま[Webインストーラでインストール](/quickstart_install/web-installer)に進んでください。  
  
開発者以外の方で、ローカル環境構築したい方は、以下をご覧ください。  
[Windows環境でのインストール方法](/quickstart_install/gui_win_install)  
[Mac環境でのインストール方法](/quickstart_install/gui_mac_install)

## 開発者向けインストール方法

1. [コマンドラインからインストールする](#1コマンドラインからインストールする)
1. [ComposerからWebインストーラでインストールする](#2composerからwebインストーラでインストールする)
1. [Dockerを使用してインストールする](#3dockerを使用してインストールする)
1. [Docker Composeを使用してインストールする](#4docker-composeを使用してインストールする)

#### 実行環境の設定について（共通事項）

インストールの際は[環境設定](/environmental_setting)と[デバッグモード](debug_mode)も合わせてお読みください。
環境変数の設定で開発モード/デバッグモードを有効にすると開発に便利な機能を利用できますが、ブラウザでアクセスをすると内部の情報が確認できる状態となります。
開発モード/デバッグモードの利用はローカルでの開発のみに限定し、サイトをインターネット上で公開される際は必ずプロダクションモードを利用してください。

#### パーミッションの設定について（共通事項）

インストールした際の[パーミッションの設定について](/permission)も合わせてお読みください。  
セキュリティの観点から、パーミッションは可能な限り制限して特定のディレクトリ・ファイルのみに書き込み権限を与えることをおすすめいたします。

---

### 1.コマンドラインからインストールする

**開発環境としておすすめの方法です。**  
こちらはGitHub上にある最新のEC-CUBE4をインストールする方法になります。  
※ 公式サイトのパッケージ版とは違いますので、ご注意ください。

1.EC-CUBEをインストールしたいディレクトリへ移動

```shell
cd ディレクトリのアドレス指定（もしくはディレクトリをドラック＆ドロップ）
```

※ 上記ディレクトリ内に ec-cube ディレクトリが生成されます。

2.ディレクトリ移動後にcomposer.pharファイルを生成  
リンク先の [Composer のインストール](https://getcomposer.org/download/){:target="_blank"} の最初に登場するpreタグで囲まれているコマンドを実行しcomposer.pharファイルを生成します。  


3.composer.pharが入っているディレクトリにコマンドラインで移動していることを確認し、以下のコマンドを実行します。

```shell
php composer.phar create-project ec-cube/ec-cube ec-cube "4.0.x-dev" --keep-vcs
```

※ 初期状態では SQLite3 を使用します。   
※ MacのOSによってはphp-intlが入っておらず、エラーとなるケースがあります。homebrewなどで新しいPHPをインストールし、再度お試しください。


4.ec-cube ディレクトリが生成されますので、`cd ec-cube`で移動し、  
`bin/console server:run --env=dev` コマンドを実行すると、ビルトインウェブサーバが起動します。

```shell
cd ec-cube
bin/console server:run --env=dev
```

5.[http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin) にアクセスし、 EC-CUBE の管理ログイン画面が表示されればインストール成功です。  
以下の ID/Password にてログインしてください。

`ID: admin PW: password`

*ビルトインウェブサーバを終了する場合は `Ctrl+C` を押してください*



#### データベースの種類を変更したい場合

インストール後、 `bin/console eccube:install` コマンドを実行し、 `Database Url` を以下のように設定してください。

```shell
## for MySQL
mysql://<user>:<password>@<host>/<database name>

## for PostgreSQl
postgres://<user>:<password>@<host>/<database name>
```

#### Windows 環境を使用する場合

`bin/console eccube:install` コマンドは使用できません。
代替として、以下のコマンド使用して下さい。

```shell
# (optional) データベース削除
bin/console doctrine:database:drop --force
# データベース作成
bin/console doctrine:database:create
# (optional) スキーマ削除
bin/console doctrine:schema:drop --force
# スキーマ生成
bin/console doctrine:schema:create
# 初期データ生成
bin/console eccube:fixtures:load
```

- *`bin/console eccube:install` コマンドでは、これらのコマンドを内部的に実行しています。*
- Symfony と Windows 環境の相性があまり良くないため、動作が大変遅くなる可能性があります。 [Dockerを使用したインストール](https://doc4.ec-cube.net/quickstart_install#docker%E3%82%92%E4%BD%BF%E7%94%A8%E3%81%97%E3%81%9F%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB) をおすすめします。

---

### 2.ComposerからWebインストーラでインストールする

こちらは、[コマンドラインからインストールする](#コマンドラインからインストールする)とほぼ同じやり方になります。   
違いとしては、実行するとEC-CUBEのインストール画面が立ち上がります。EC-CUBEのインストール画面から始めたい方におすすめです。

[コマンドラインからインストールする](#コマンドラインからインストールする)にて、3.の工程の部分を以下のコマンドで実行してください。

```shell
php composer.phar create-project --no-scripts ec-cube/ec-cube ec-cube "4.0.x-dev" --keep-vcs
```

ec-cube ディレクトリが生成されますので、`cd ec-cube`で移動し、  
`bin/console server:run` コマンドを実行すると、ビルトインウェブサーバが起動します。

```shell
cd ec-cube
bin/console server:run
```

[http://127.0.0.1:8000/](http://127.0.0.1:8000/){:target="_blank"} にアクセスすると、 EC-CUBEのインストール画面が立ち上がりますので、指示にしたがってインストールしてください。

*ビルトインウェブサーバを終了する場合は `Ctrl+C` を押してください*

---

### 3.Dockerを使用してインストールする

前提として、 [Docker Desktop のインストール](https://hub.docker.com){:target="_blank"} が必要です。

+ 初期状態では SQLite3 を使用します
+ コンテナ上のファイルを使用しても、VOLUME をマウントしても使用可能です

```shell
cd path/to/ec-cube
docker build -t eccube4-php-apache .

## コンテナ上のファイルを使用する場合
docker run --name ec-cube -p "8080:80" -p "4430:443" eccube4-php-apache

## ローカルディレクトリをマウントする場合
# var 以下をマウントすると強烈に遅くなるため、 src, html, app 以下のみをマウントする
docker run --name ec-cube -p "8080:80" -p "4430:443"  -v "$PWD/html:/var/www/html/html:cached" -v "$PWD/src:/var/www/html/src:cached" -v "$PWD/app:/var/www/html/app:cached" eccube4-php-apache
```

**2回目以降の起動時は、以下のコマンドを実行します。**

```shell
docker start --attach ec-cube
```

#### 設定ファイルを編集する場合

.env など、インストールディレクトリ直下のファイルを編集する場合は、コンテナ上のファイルを直接編集するか、個別にマウントする必要があります

```shell
docker exec -it ec-cube /bin/bash
root@de5372ce7139:/var/www/html# vi .env
```

#### メール送信を使用する場合

mailcatcher を使用します

```shell
## .env にて MAILER_URL=smtp://mailcatcher:1025 としておく
docker run -d -p 1080:1080 -p 1025:1025 --name mailcatcher schickling/mailcatcher
docker run --name ec-cube -p "8080:80" -p "4430:443"  --link mailcatcher:mailcatcher eccube4-php-apache
```

[mailcatcherについての詳細ページ](https://doc4.ec-cube.net/development-tools/mail-catcher)

#### PostgreSQL を使用する場合

```shell
## .env にて DATABASE_URL=pgsql://postgres:password@db/cube4_dev としておく
docker run --name container_postgres -e POSTGRES_PASSWORD=password  -p 5432:5432 -d postgres
docker run --name ec-cube -p "8080:80" -p "4430:443" --link container_postgres:db eccube4-php-apache
```

#### MySQL を使用する場合

```shell
## .env にて DATABASE_URL=mysql://root:password@db/cube4_dev としておく
docker run --name container_mysql -e MYSQL_ROOT_PASSWORD=password  -d -p 3306:3306 mysql:5.7
docker run --name ec-cube -p "8080:80" -p "4430:443" --link container_mysql:db eccube4-php-apache
```

---

### 4.Docker Composeを使用してインストールする
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
