---
title: MailCatcher
keywords: mailcatcher
tags: [development-tools, mail-catcher]
permalink: /development-tools/mail-catcher
forder: development-tools
---

## 概要

EC-CUBEのローカル開発環境を構築する際、Webサーバーの他に別途メールサーバーが必要となります。

本記事ではDockerHubの公開イメージを利用した、メールサーバーを構築する手順について紹介します。

## 前提条件

Docker for Mac、またはDocker for WindowsがPCにインストールされていることが条件です。

## MailCatcherの利用

<a href="https://mailcatcher.me/" target="_blank">MailCatcher</a>を利用することで、SMTPのメールサーバーを開発環境に導入することができます。


DockerHubのレジストリから<a href="https://hub.docker.com/r/schickling/mailcatcher/" target="_blank">MailCatcherの公開イメージ</a>を取得することで、手軽にメールサーバーを導入できます。

## EC-CUBEの設定

EC-CUBEの`.env`ファイルに以下の設定を行います。

```
MAILER_URL=smtp://127.0.0.1:1025
```

## Dockerコンテナの起動
以下２通りの方法があります。

### Dockerコマンド
以下のdockerコマンドでSMTPサーバーが利用できるようになります。

```bash
$ docker run -d -p 1080:1080 -p 1025:1025 --name mailcatcher schickling/mailcatcher

$ docker ps
CONTAINER ID        IMAGE                    COMMAND                  CREATED              STATUS              PORTS                                            NAMES
41290748f3ab        schickling/mailcatcher   "mailcatcher --no-qu…"   About a minute ago   Up 58 seconds       0.0.0.0:1025->1025/tcp, 0.0.0.0:1080->1080/tcp   mailcatcher
```

コンテナの停止、再起動は以下のコマンドです。

```bash
# コンテナの停止
$ docker stop mailcatcher

# コンテナの再起動
$ docker start mailcatcher

```

### Docker Compose

また、docker-composeを利用する場合、`docker-compose.yml`を作成してください。

```yml
version: '3'

services:
  mailcatcher:
          image: schickling/mailcatcher
          ports:
              - "1080:1080"
              - "1025:1025"
```

```bash
$ docker-compose up -d
```
を実行することで、MailCatcherのコンテナが作成されます。

終了する場合は以下のコマンドです。

```bash
$ docker-compose down
```

## メールクライアント

Google chrome 等のブラウザから　http://127.0.0.1:1080/ にアクセスすると以下の画面となります。

![mailcatcher-image](/images/development-tools/mailcatcher-client.png)

ブラウザ上でメール送信結果を確認できます。

## 参考

- <a href="https://mailcatcher.me/" target="_blank">https://mailcatcher.me/</a>
- <a href="https://hub.docker.com/r/schickling/mailcatcher/" target="_blank">https://hub.docker.com/r/schickling/mailcatcher/</a>
