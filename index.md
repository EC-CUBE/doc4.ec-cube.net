---
layout: single
author_profile: false
title: EC-CUBE 4.0 開発者向けドキュメント
description: "EC-CUBE 4.0 の開発者向けドキュメントサイトです。EC-CUBEのインストール方法、本体カスタマイズやプラグイン開発についての情報を提供しています。"
keywords: このサイトについて, QuickStart
sidebar:
  nav: "docs"
permalink: /
---

EC-CUBE 4.0 の開発者向けドキュメントサイトです。
EC-CUBEのインストール方法、開発ガイドラインや要素技術の概念、本体開発やプラグイン開発のチュートリアル、Cookbookなどの情報を提供しています。

ドキュメント内容や、カスタマイズについて不明な点がございましたら、以下をご検討ください。
+ EC-CUBE本体と同様に[GitHub](https://github.com/EC-CUBE/doc4.ec-cube.net/){:target="_blank"}へIssueをご投稿いただく
  詳細は[こちら](documents/request)をご覧ください
+ [実施予定のUGや勉強会](https://www.ec-cube.net/event/){:target="_blank"}を探して参加する

ドキュメントへの追記、記載内容の修正についても[GitHub](https://github.com/EC-CUBE/doc4.ec-cube.net/){:target="_blank"}にて受け付けております。
追加方法は[こちら](/documents/writing-and-formatting)をご覧ください。

{: .notice--danger}
- 2021/05/10 [プラグイン開発者の方へ ソースコード確認の御願い](https://drive.google.com/file/d/1kCHwoWrveHGPPkH2mu1cz6o2XfNv4Lsq/view?usp=sharing){:target="_blank"}を公開いたしました。
- 2021/05/10 EC-CUBE 4.0.5 以前には[危険度「高」の脆弱性](https://www.ec-cube.net/info/weakness/index.php?level=0&version=4.0){:target="_blank"}が含まれています。必ず EC-CUBE 4.0.6 以降のバージョンへバージョンアップをお願いします。
{: .notice--danger}

## ec-cube.coについて

クラウド版「ec-cube.co」の技術情報については、[こちら](co)を御覧ください。

## 初学者向け情報

+ [学習のステップ](/learning/learning_step)

## 運用者向け情報

運用者向けには以下のサイトをご覧ください。
+ [EC-CUBE 4管理・運用 マニュアル（株式会社シロハチ様）](https://www.shiro8.net/manual4/v40x/index.html){:target="_blank"}

## はじめに

+ [システム要件](/quickstart/requirement)
+ [本番環境での注意事項](/quickstart/cautions_of_prod)

## インストール

+ [インストール方法](/quickstart/install)
+ [コマンドラインインターフェイス](/quickstart/cli)

## バージョンアップ

+ [バージョンアップ方法](/update)
+ [4.0.3での注意点](/update/4_0_3)
+ [EC-CUBE4.0から4.1へのマイグレーション](/update-40-41)

## 本体の仕様

+ [ER図](https://github.com/EC-CUBE/eccube-specification/tree/4.0/ER-D){:target="_blank"}
+ [結合試験項目書](https://github.com/EC-CUBE/eccube-specification/tree/4.0/IntegrationTest){:target="_blank"}
+ [WebAPI仕様](https://github.com/EC-CUBE/eccube-api4){:target="_blank"}

### 機能仕様

+ [機能一覧](https://www.ec-cube.net/product/functions.php){:target="_blank"}
+ [受注関連](spec_order)
+ [税率設定](spec_tax)

## 本体カスタマイズ

+ [ディレクトリ構成](spec_directory-structure)
+ [Controllerのカスタマイズ](customize_controller)
+ [Entityのカスタマイズ](customize_entity)
+ [Repositoryのカスタマイズ](customize_repository)
+ [FormTypeのカスタマイズ](customize_formtype)
+ [購入フローのカスタマイズ](customize_service)
+ [受注ステータスのカスタマイズ](customize_order_state_machine)
+ [テンプレートのカスタマイズ](customize_template)
+ [Symfonyの機能を使った拡張](customize_symfony)

## デザインカスタマイズ

+ [フロント画面デザイン参考資料（スタイルガイド）](http://eccube4-styleguide.herokuapp.com/){:target="_blank"}
+ [管理画面デザイン参考資料（デザインガイド）](/pdf/ec-cube4_design-guide180930.pdf)

+ [デザインテンプレートの基礎](design_template)
+ [フォームレイアウトの変更](design_form)
+ [ブロックの利用](design_block)
+ [レイアウト管理の利用](design_layout)
+ [CSSの利用](design_css)
+ [Sass(scss)の利用](design_sass)
+ [フロント画面テンプレート for Adobe XD](http://downloads.ec-cube.net/manual/documents/eccube4_xd_front_template.zip?argument=2qpV46CP&dmai=a5bf51b05bacc5){:target="_blank"}
+ [管理画面テンプレート for Adobe XD](http://downloads.ec-cube.net/manual/documents/eccube4_xd_admin_template.zip?argument=2qpV46CP&dmai=a5bf51b05bacc5){:target="_blank"}

## プラグイン開発

+ [プラグイン仕様](plugin_spec)
+ [プラグインのインストール](plugin_install)
+ [インストール失敗時の対処法](plugin_error)
+ [プラグインサンプル](plugin_sample)
+ [オーナーズストア経由のインストールをテストする](plugin_mock_package_api)
+ [プラグインで推奨の命名規則](plugin_naming_conventions)

## 多言語化

+ [多言語化](i18n_multilingualization)
+ [通貨](i18n_currency)
+ [タイムゾーン](i18n_timezone)

## 開発ツール

+ [MailCatcher](/development-tools/mail-catcher)

## セキュリティテストの実施

- [EC-CUBE Penetration Testing with OWASP ZAP](/penetration-testing)

## 逆引きリファレンス

- [Tips](/reverse-lookup/tips)
- [カスタマイズサンプル集](/reverse-lookup/sample-code)

## 開発に参加する

+ [EC-CUBEの開発に参加するには？（公式サイト）](https://www.ec-cube.net/committer/){:target="_blank"}
+ [開発の概要](/contribution-guide/overview)
+ [プルリクエストの作り方](/contribution-guide/pull-request)

## ドキュメントがみつからないときは

+ [ドキュメントのリクエスト](/documents/request)
+ [ドキュメントの追加・書き方](/documents/writing-and-formatting)
+ [ドキュメントの投稿](/documents/contribute)

## Supporters

EC-CUBEは以下のサポートを受けています。

+ [SAKURA internet](https://www.sakura.ad.jp/){:target="_blank"}
[![SAKURA internet](./images/3-1-2line-rgb-whiteback.png)](https://www.sakura.ad.jp/){:target="_blank"}

+ [VAddy](https://vaddy.net/ja/){:target="_blank"}
[![VAddy](./images/VAddy_logo.png)](https://vaddy.net/ja/){:target="_blank"}
