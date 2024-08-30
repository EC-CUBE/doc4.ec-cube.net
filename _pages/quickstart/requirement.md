---
layout: single
author_profile: false
title: システム要件
keywords:
tags: [quickstart, getting_started]
permalink: quickstart/requirement

---

## 4.3

| 分類 | ソフトウェア|Version|
|---|-------|---|
|WebServer|Apache |2.4.x <br> (mod_rewrite / mod_ssl 必須) |
|PHP |PHP |8.1 〜 8.3 |
|Database|PostgreSQL|12.x 〜 16.x <br> (pg_settingsテーブルへの参照権限 必須) |
|Database|MySQL| 8.0<br> (InnoDBエンジン 必須) |
|Database|SQLite(開発用途向け) |3.x |

## 4.2

| 分類 | ソフトウェア|Version|
|---|-------|---|
|WebServer|Apache |2.4.x <br> (mod_rewrite / mod_ssl 必須) |
|PHP | PHP | 7.4 〜 8.1|
|Database|PostgreSQL| 10.x 〜 14.x <br> (pg_settingsテーブルへの参照権限 必須) |
|Database|MySQL|5.7 or 8.0<br> (InnoDBエンジン 必須) |
|Database|SQLite(開発用途向け) |3.x |

## 4.0/4.1

| 分類 | ソフトウェア|Version|
|---|-------|---|
|WebServer|Apache |2.4.x <br> (mod_rewrite / mod_ssl 必須) |
|PHP | PHP | 7.3 〜 7.4 <small>(※1)</small>|
|Database|PostgreSQL| 9.6.x 〜 14.x <small>(※2)</small><br> (pg_settingsテーブルへの参照権限 必須) |
|Database|MySQL|5.7.x <small>(※3)</small><br> (InnoDBエンジン 必須) |
|Database|SQLite(開発用途向け) |3.x |

※1 EC-CUBE 4.0.0〜4.0.1 は PHP 7.1〜7.2対応、4.0.2〜4.0.3 は PHP 7.1〜7.3対応、4.0.4〜4.0.x は PHP 7.1〜7.4対応となります  
※2 EC-CUBE 4.0系は PostgreSQL 9.2.x〜13.x 対応となります  
※3 EC-CUBE 4.0系 は MySQL 5.5.x〜5.7.x 対応となります

# PHPライブラリ

| 分類 | ライブラリ|
|---|---|
|必須ライブラリ|pgsql / mysqli (利用するデータベースに合わせること) <br> pdo_pgsql / pdo_mysql / pdo_sqlite (利用するデータベースに合わせること) <br> pdo <br> phar <br> mbstring <br> zlib <br> ctype <br> session <br> JSON <br> xml <br> libxml <br> OpenSSL <br> zip <br> cURL <br> fileinfo <br> intl <br> GD <br> Sodium(※4) |
|推奨ライブラリ|hash <br> APCu / WinCache (利用する環境に合わせること) <br> Zend OPcache |

※4 EC-CUBE 4.2系は Web API プラグインを利用する場合、Sodium拡張 が必要になります。  
プラグインのインストールが出来ない方は[こちらの項目](/quickstart/trouble-shooting-for-plugin-install)をご参考下さい

# 動作確認ブラウザ

管理画面と標準のフロントテンプレート

| OS | ブラウザ|
|---|-------|
|Windows | Edge 最新 |
||FireFox 最新 |
||Google Chrome 最新 |
|Mac|Safari 最新|
|iOS|Safari 最新|
|Android| 標準ブラウザ 最新|
