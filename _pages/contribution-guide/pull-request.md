---
title: プルリクエストの作り方
description: EC-CUBEのプルリクエストを送る場合の手順や考慮してもらいたいことについて説明します。
keywords: Githubflow
tags: [contribution-guide, guideline]
permalink: contribution-guide/pull-request
folder: contribution-guide
---

EC-CUBEのプルリクエストを送る場合の手順や考慮してもらいたいことについて説明します。

## ソースコードをローカルにクローンする

<a href="https://github.com/EC-CUBE/ec-cube" target="_blank">ec-cube</a>のリポジトリを自身の Github アカウントに <a href="https://help.github.com/ja/github/getting-started-with-github/fork-a-repo" target="_blank">Fork</a> し、ローカルに <a href="https://help.github.com/ja/github/creating-cloning-and-archiving-repositories/cloning-a-repository" target="_blank">clone</a>　します。

## パッケージのインストール

依存関係のあるパッケージをインストールします。

```sh
$ composer install
```

## リモートの追加

ec-cubeのリポジトリをリモートに追加します。

リモートリポジトリ名を `upstream` としていますが、任意の名前で構いません。

```sh
$ git remote add upstream https://github.com/EC-CUBE/ec-cube.git
```

## ローカルのmasterブランチの更新

eccubeは4.1ブランチがmasterブランチとなっています。

```sh
$ git pull upstream 4.1
```

## 開発用ブランチの作成

```sh
$ git checkout -b [任意のブランチ名] upstream/4.1
```

## 自身のGithubリポジトリの更新

作成したブランチで変更内容を commit 後、自身のGithubリポジトリに push を行います。

```sh
$ git push origin [任意のブランチ名]
```

## プルリクエストを送る

自身のGithubリポジトリのページから、<a href="https://github.com/EC-CUBE/ec-cube" target="_blank">ec-cube</a> へプルリクエストを作成します。

- GitHubの自分のレポトリから、PullRequestする

### プルリクエストのマージ条件

以下がクリアされる事で本体の「Master」にマージされます。

1. 開発者・コミッターのレビュー

2. CIのチェック
    - Travis			 : ユニットテスト
    - AppVeyor		 : ユニットテスト( Win環境 )
    - Scritinizer	: 静的コード解析

### プルリクエストを送る際に行ってもらいたいこと

不要なコミットログはまとめてください。
対象は`git rebase`について把握している方ですので、必須ではありません。

以下のようなコミットを行った場合は、`112233445`から`334455667`はまとめてください。
```sh
$ git log --pretty=format:"%h - %an : %s"
334455667 - myself : 機能A修正
223344556 - myself : 機能A修正
112233445 - myself : 機能A追加
001122334 - other_user : 別ユーザーのコミット
```
`git rebase`を実行し、まとめてください。
```sh
$ git rebase -i 001122334
pick 112233445 機能A追加
squash 223344556 機能A修正
squash 334455667 機能A修正
```
以下のようになったらプルリクエストを上げてください。
```sh
$ git log --pretty=format:"%h - %an : %s"
445566778 - myself : 機能A追加
001122334 - other_user : 別ユーザーのコミット
$ git push origin master
$ ...
```

ただし、コメントに対して修正を加えた場合は履歴がわかるようにするためにまとめすぎないようにしてください。
```sh
$ git log --pretty=format:"%h - %an : %s"
667788990 - myself : 修正に間違いがあったため修正
556677889 - myself : レビュー結果を反映
445566778 - myself : 機能A追加
001122334 - other_user : 別ユーザーのコミット
```
`445566778`のコミット後にプルリクエストを上げ、レビューされた内容を反映するために修正した場合、`556677889`と`667788990`はまとめますが、`445566778`はまとめないでください。

マージ済みのコミットはまとめないでください。
