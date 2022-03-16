---
title: Dockerを使用してインストールする
keywords: install Docker
tags: [quickstart, install, docker]
permalink: quickstart/docker_install
folder: quickstart
---


---

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

[mailcatcherについての詳細ページ](/development-tools/mail-catcher)

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
