---
layout: single
title: 各種インストール方法
keywords: install
tags: [quickstart, install]
permalink: quickstart/install
folder: quickstart
---


---

各種インストール手順をまとめました。

## ローカルへインストールする

### GUIでインストールする

パッケージ版をダウンロードしていただき、Webインストーラ(GUI)を使用してローカルへインストールする方法です。

+ [Windows環境でXAMPPを使用したインストール方法](/quickstart/gui_win_install)
+ [Mac環境でMAMPを使用したインストール方法](/quickstart/gui_mac_install)

### CUI & GUIでインストールする

コマンドを実行するとGitHub上にある最新のEC-CUBE4をダウンロード、その後はWebインストーラ(GUI)を使用してローカルへインストールする方法です。

+ [ComposerからWebインストーラでインストールする](/quickstart/composer_web_installer)

### CUIでインストールする

Githubから任意のブランチやタグをクローンいただき、コマンドを実行しインストールする方法です。

+ [Docker Composeを使用してインストールする](/quickstart/docker_compose_install)
+ [Dockerを使用してインストールする](/quickstart/docker_install)
+ [コマンドラインからインストールする](/quickstart/command_install)

## サーバーへインストールする

パッケージ版をダウンロードし、Webインストーラ(GUI)を使用してサーバーへインストールする方法です。

+ [サーバーへインストールする方法](/quickstart/web-installer)

## Tips

+ [コマンドラインインターフェイス](quickstart_cli)








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

#### 本番環境での .env ファイルの利用について

[本番環境にインストールする場合の .env ファイルの利用について](/quickstart_install/dotenv)も合わせてお読みください。
セキュリティの観点から、本番環境での .env ファイルの利用は推奨されません。
本番環境では、環境変数をサーバ設定ファイルに設定することを推奨します。

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

composer.phar のバージョンをEC-CUBE 4.0の対応しているバージョン（1.x）に変更します。

```shell
php composer.phar selfupdate --1
```


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
