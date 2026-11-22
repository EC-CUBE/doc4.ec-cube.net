---
title: ComposerからWebインストーラでインストールする
keywords: install Composer Webインストーラ
tags: [quickstart, install, composer]
permalink: quickstart/composer_web_installer
folder: quickstart
description: ComposerでEC-CUBE 4系を取得し、Webインストーラでインストールする方法を解説します。
---


---

こちらは、[コマンドラインからインストールする](/quickstart/command_install)とほぼ同じやり方になります。   
違いとしては、実行するとEC-CUBEのインストール画面が立ち上がります。EC-CUBEのインストール画面から始めたい方におすすめです。

[コマンドラインからインストールする](/quickstart/command_install)にて、3.の工程の部分を以下のコマンドで実行してください。

```shell
php composer.phar create-project --no-scripts ec-cube/ec-cube ec-cube "4.3.x-dev" --keep-vcs
```

※ 上記は EC-CUBE 4.3 をインストールする例です。他のバージョンをインストールする場合は `"4.3.x-dev"` の部分を `"4.2.x-dev"` などに変更してください。

ec-cube ディレクトリが生成されますので、`cd ec-cube`で移動し、ビルトインウェブサーバを起動します。

EC-CUBE 4.0 / 4.1 の場合は、`php bin/console server:run` コマンドを実行します。

```shell
cd ec-cube
php bin/console server:run
```

EC-CUBE 4.2 以降では `server:run` コマンドは利用できません。[Symfony CLI](https://symfony.com/download){:target="_blank"} の `symfony server:start` コマンド、または PHP のビルトインウェブサーバを使用してください。

```shell
cd ec-cube
# Symfony CLI を使用する場合
symfony server:start --no-tls
# PHP のビルトインウェブサーバを使用する場合
php -S 127.0.0.1:8000
```

[http://127.0.0.1:8000/](http://127.0.0.1:8000/){:target="_blank"} にアクセスすると、 EC-CUBEのインストール画面が立ち上がりますので、指示にしたがってインストールしてください。

*ビルトインウェブサーバを終了する場合は `Ctrl+C` を押してください*
