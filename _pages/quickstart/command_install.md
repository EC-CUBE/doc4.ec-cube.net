---
title: コマンドラインからインストールする
keywords: install Docker
tags: [quickstart, install, command]
permalink: quickstart/command_install
folder: quickstart
description: Composerを使用してコマンドラインからEC-CUBE 4系をインストールする方法を解説します。
---


---

**開発環境としておすすめの方法です。**  
こちらはGitHub上にある最新のEC-CUBE4をインストールする方法になります。  
※ 公式サイトのパッケージ版とは違いますので、ご注意ください。

1.EC-CUBEをインストールしたいディレクトリへ移動

```shell
cd ディレクトリのアドレス指定（もしくはディレクトリをドラッグ＆ドロップ）
```

※ 上記ディレクトリ内に ec-cube ディレクトリが生成されます。

2.ディレクトリ移動後にcomposer.pharファイルを生成  
リンク先の [Composer のインストール](https://getcomposer.org/download/){:target="_blank"} の最初に登場するpreタグで囲まれているコマンドを実行しcomposer.pharファイルを生成します。  

EC-CUBE 4.0 をインストールする場合は、composer.phar のバージョンをEC-CUBE 4.0の対応しているバージョン（1.x）に変更します。  
EC-CUBE 4.1 以降は Composer 2.x に対応しているため、この手順は不要です。

```shell
php composer.phar selfupdate --1
```


3.composer.pharが入っているディレクトリにコマンドラインで移動していることを確認し、以下のコマンドを実行します。

```shell
php composer.phar create-project ec-cube/ec-cube ec-cube "4.3.x-dev" --keep-vcs
```

※ 上記は EC-CUBE 4.3 をインストールする例です。他のバージョンをインストールする場合は `"4.3.x-dev"` の部分を `"4.2.x-dev"` などに変更してください。

※ 初期状態では SQLite3 を使用します。   
※ MacのOSによってはphp-intlが入っておらず、エラーとなるケースがあります。homebrewなどで新しいPHPをインストールし、再度お試しください。


4.ec-cube ディレクトリが生成されますので、`cd ec-cube`で移動し、ビルトインウェブサーバを起動します。

EC-CUBE 4.0 / 4.1 の場合は、`php bin/console server:run --env=dev` コマンドを実行します。

```shell
cd ec-cube
php bin/console server:run --env=dev
```

EC-CUBE 4.2 以降では `server:run` コマンドは利用できません。[Symfony CLI](https://symfony.com/download){:target="_blank"} の `symfony server:start` コマンド、または PHP のビルトインウェブサーバを使用してください。

```shell
cd ec-cube
# Symfony CLI を使用する場合
symfony server:start --no-tls
# PHP のビルトインウェブサーバを使用する場合
php -S 127.0.0.1:8000
```

5.[http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin){:target='_blank'} にアクセスし、 EC-CUBE の管理ログイン画面が表示されればインストール成功です。  
以下の ID/Password にてログインしてください。

`ID: admin PW: password`

*ビルトインウェブサーバを終了する場合は `Ctrl+C` を押してください*



#### データベースの種類を変更したい場合

インストール後、 `php bin/console eccube:install` コマンドを実行し、 `Database Url` を以下のように設定してください。

```shell
## for MySQL
mysql://<user>:<password>@<host>/<database name>

## for PostgreSQL
postgres://<user>:<password>@<host>/<database name>
```

#### Windows 環境を使用する場合

`php bin/console eccube:install` コマンドは使用できません。
代替として、以下のコマンドを使用して下さい。

```shell
# (optional) データベース削除
php bin/console doctrine:database:drop --force
# データベース作成
php bin/console doctrine:database:create
# (optional) スキーマ削除
php bin/console doctrine:schema:drop --force
# スキーマ生成
php bin/console doctrine:schema:create
# 初期データ生成
php bin/console eccube:fixtures:load
```

- *`php bin/console eccube:install` コマンドでは、これらのコマンドを内部的に実行しています。*
- Symfony と Windows 環境の相性があまり良くないため、動作が大変遅くなる可能性があります。 [Docker Composeを使用してインストールする](/quickstart/docker_compose_install) をおすすめします。
