---
title: 開発の概要
description: EC-CUBEの開発のワークフローや利用しているツールについて紹介しています。
keywords: contribution-guide ワークフロー
tags: [contribution-guide, guideline]
permalink: contribution-guide/overview
folder: contribution-guide
---

EC-CUBEの開発のワークフローや利用しているツールについて紹介しています。

## 開発ワークフロー

EC-CUBE本体の開発はGitHub Flowにもとづいて行なっています。

詳細は下記の参考ページをご確認下さい。

- [GitHub Flowの説明](https://gist.github.com/Gab-km/3705015){:target="_blank"}
- [GitHub Flowの概念図](http://qiita.com/tbpgr/items/4ff76ef35c4ff0ec8314){:target="_blank"}

## 情報交換・コミュニケーション

- <a href="https://qiita.com/tags/ec-cube4/" target="_blank">Qiita</a>
    - 開発時のTIPSなどを公開しています。
- <a href="https://ec-cube.slack.com/messages/" target="_blank">Slack</a>
    - 開発者同士のコミュニケーションツールとして活用しています。
- <a href="https://github.com/EC-CUBE/ec-cube/issues/" target="_blank">GitHubのIssues</a>
    - 「改善要望・実装アイデア・バグ報告」など、開発の核となる、情報が集まっています。


## ツール

### バージョン管理

- <a href="https://github.com/EC-CUBE/ec-cube/" target="_blank">GitHub</a>
    - 前述した、Issuesでの情報共有のほか、ソースのバージョン管理・差分管理など、重要な役割を担います。

### テスト・コード解析

- <a href="https://travis-ci.org/" target="_blank">Travis</a>
    - テストコードを指定した環境で、ユニットテストを行います。

- <a href="https://ci.appveyor.com/login" target="_blank">AppVeyor</a>
    - こちらはWindows環境テスト用のCIです。

- <a href="https://scrutinizer-ci.com/" target="_blank">Scrutinizer</a>
    - 静的コード解析を行います。
