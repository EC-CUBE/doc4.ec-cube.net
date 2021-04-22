---
title: ComposerからWebインストーラでインストールする
keywords: install Composer Webインストーラ
tags: [quickstart, install, composer]
permalink: quickstart/composer_web_installer
folder: quickstart
---


---

こちらは、[コマンドラインからインストールする](/quickstart/command_install)とほぼ同じやり方になります。   
違いとしては、実行するとEC-CUBEのインストール画面が立ち上がります。EC-CUBEのインストール画面から始めたい方におすすめです。

[コマンドラインからインストールする](/quickstart/command_install)にて、3.の工程の部分を以下のコマンドで実行してください。

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
