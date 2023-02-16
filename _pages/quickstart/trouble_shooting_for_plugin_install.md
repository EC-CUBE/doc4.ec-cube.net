---
title: 4.2系でプラグインがインストールできない問題の対応策
keywords: weakness
tags: [quickstart, plugin]
permalink: quickstart/trouble-shooting-for-plugin-install
folder: security-guideline
---

# 概要

## 事象
EC-CUBE4.2.0(2022年9月リリース)において、一部レンタルサーバー等でプラグインがインストールできない。

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

→こちらの対応が難しい場合は、以下の**暫定対応**をお試し下さい。

## 暫定対応
WebAPIプラグインをアンインストールする


## 今後のEC-CUBE側対応
2月22日リリース予定のEC-CUBE4.2.1にて、上記対応を実施しなくても動作するよう修正をいたします。  
（4.2.1でsodiumのチェックをして、入っていない場合はAPIプラグインがインストールされないように対処をします）
