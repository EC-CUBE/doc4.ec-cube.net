---
title: 4.2系でプラグインがインストールできない問題の対応策
keywords: weakness
tags: [quickstart, plugin]
permalink: quickstart/trouble-shooting-for-plugin-install
folder: security-guideline
---

## 事象
EC-CUBE4.2.0において、一部レンタルサーバー等でプラグインがインストールできない。

## 対象

### 本体
EC-CUBE 4.2系

### 対象プラグイン
すべて

## 原因

PHP7.2以降で標準で追加されている PHP-sodium 拡張ライブラリがない場合に起こる現象です。
特定のレンタルサーバ等で発生する事象と認識しております。
(EC-CUBE4.2のシステム要件はPHP7.4以降となっています。)

## 根本対応
PHP-sodium 拡張ライブラリをインストールする。

→こちらの対応が難しい場合は、以下の**回避方法**をお試し下さい。

## 回避方法
WebAPIプラグインをアンインストールする

→WebAPIプラグインをアンインストールすることで、プラグインのインストールができるようになります。

## EC-CUBE 4.2.1 以降の仕様

EC-CUBE 4.2.1 にて、sodium拡張がインストールされていない環境の場合、WebインストーラでWebAPIプラグインがアンインストールされるようになりました。

詳しくは以下をご確認ください。

- [EC-CUBE#5912](https://github.com/EC-CUBE/ec-cube/issues/5912#issuecomment-1420335054)
- [EC-CUBE#5934](https://github.com/EC-CUBE/ec-cube/pull/5934)