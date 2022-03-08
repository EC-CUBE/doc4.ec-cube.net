---
title: コマンドラインからインストールする
keywords: install Docker
tags: [quickstart, install, command]
permalink: quickstart/command_install
folder: quickstart
---


---

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
php composer.phar create-project ec-cube/ec-cube ec-cube "4.1.x-dev" --keep-vcs
```

※ 初期状態では SQLite3 を使用します。   
※ MacのOSによってはphp-intlが入っておらず、エラーとなるケースがあります。homebrewなどで新しいPHPをインストールし、再度お試しください。


4.ec-cube ディレクトリが生成されますので、`cd ec-cube`で移動し、  
`bin/console server:run --env=dev` コマンドを実行すると、ビルトインウェブサーバが起動します。

```shell
cd ec-cube
bin/console server:run --env=dev
```

5.[http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin){:target='_blank'} にアクセスし、 EC-CUBE の管理ログイン画面が表示されればインストール成功です。  
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
- Symfony と Windows 環境の相性があまり良くないため、動作が大変遅くなる可能性があります。 [Docker Composeを使用してインストールする](/quickstart/docker_compose_install) をおすすめします。
