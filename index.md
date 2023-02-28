---
layout: single
author_profile: false
title: EC-CUBE 4 開発者向けドキュメント
description: "EC-CUBE 4 の開発者向けドキュメントサイトです。EC-CUBEのインストール方法、本体カスタマイズやプラグイン開発についての情報を提供しています。"
keywords: このサイトについて, QuickStart
sidebar:
  nav: "docs"
permalink: /
---

EC-CUBE 4 の開発者向けドキュメントサイトです。
EC-CUBEのインストール方法、開発ガイドラインや要素技術の概念、本体開発やプラグイン開発のチュートリアル、Cookbookなどの情報を提供しています。

{: .notice--danger}
- 2021/05/10 [プラグイン開発者の方へ ソースコード確認の御願い](https://drive.google.com/file/d/1kCHwoWrveHGPPkH2mu1cz6o2XfNv4Lsq/view?usp=sharing){:target="_blank"} を公開いたしました。
- 2021/06/29 EC-CUBE 4.0.6 以前には [危険度「高」の脆弱性](https://www.ec-cube.net/info/weakness/index.php?level=0&version=4.0){:target="_blank"} が含まれています。必ず EC-CUBE 4.0.6-p1 以降のバージョンへバージョンアップをお願いします。
- 2023/02/28 [4.2系でプラグインがインストールできない問題の対応策](/quickstart/trouble-shooting-for-plugin-install)を公開いたしました。特に**レンタルサーバーで4.2系のプラグインがインストール出来ない方**はご参考下さい。
{: .notice--danger}

ドキュメント内容や、カスタマイズについて不明な点がございましたら、以下をご検討ください。
+ EC-CUBE本体と同様に[GitHub](https://github.com/EC-CUBE/doc4.ec-cube.net/){:target="_blank"}へIssueをご投稿いただく
  詳細は[こちら](/documents/request)をご覧ください
+ [実施予定のUGや勉強会](https://www.ec-cube.net/event/){:target="_blank"}を探して参加する

ドキュメントへの追記、記載内容の修正についても[GitHub](https://github.com/EC-CUBE/doc4.ec-cube.net/){:target="_blank"}にて受け付けております。
追加方法は[こちら](/documents/writing-and-formatting)をご覧ください。



# ec-cube.coについて

## ec-cube.co

クラウド版「ec-cube.co」の技術情報についてまとめました。

+ [ec-cube.coとは](/co)
+ [セキュリティ対策について](/co/co_security)
+ [アップデート指針](/co/co_update_guidelines)
+ [Console](/co/co_console)
+ [ネーミングルール](/co/co_naming_rules)
+ [Git管理機能の仕様](/co/co_git)
+ [カスタマイズディレクトリ利用方法](/co/co_customize_dir_usage)
+ [テスト環境の構築](/co/co_staging)
+ [ログの取得](/co/co_log)
+ [FAQ](/co/co_faq)

# EC-CUBE本体について

## はじめに

EC-CUBEの基本情報やご確認いただきたいことについてまとめました。

+ [最新バージョン ](/quickstart/latest_version)
+ [システム要件](/quickstart/requirement)
+ [本番環境での注意事項](/quickstart/cautions_of_prod)

## 初学者向け情報

初めてEC-CUBEを触られる方向けの学習情報についてまとめました。

+ [学習のステップ](/learning/learning_step)

## インストール

インストール方法についてまとめました。

+ [各種インストール方法](/quickstart/install)
+ [コマンドラインインターフェイス](/quickstart/cli)

## バージョンアップ

本体のバージョンアップやマイグレーションについてまとめました。

+ [4.1から4.2へのマイグレーション](/update-41-42)
+ [4.0から4.1への本体バージョンアップ](/update41)
+ [4.0から4.1へのマイグレーション](/update-40-41)
+ [4.1本体バージョンアップ](/update41x)
+ [4.0本体バージョンアップ](/update)
+ [4.0.3での注意点](/update/4_0_3)
+ [SameSite Cookie 対応](/hotfix_samesite_cookie)

## 本体の仕様

本体仕様、機能仕様についてまとめました。

+ [ER図](https://github.com/EC-CUBE/eccube-specification/tree/4.0/ER-D){:target="_blank"}
+ [結合試験項目書](https://github.com/EC-CUBE/eccube-specification/tree/4.0/IntegrationTest){:target="_blank"}
+ [WebAPI仕様](https://github.com/EC-CUBE/eccube-api4){:target="_blank"}

### 機能仕様

+ [機能一覧](https://www.ec-cube.net/product/functions.php){:target="_blank"}
+ [受注関連](/spec_order)
+ [税率設定](/spec_tax)
+ [支払方法設定](/spec_payment)


## 本体カスタマイズ

本体のカスタマイズ方法についてまとめました。

+ [ディレクトリ構成](/spec_directory-structure)
+ [Controllerのカスタマイズ](/customize_controller)
+ [Entityのカスタマイズ](/customize_entity)
+ [Repositoryのカスタマイズ](/customize_repository)
+ [FormTypeのカスタマイズ](/customize_formtype)
+ [購入フローのカスタマイズ](/customize_service)
+ [受注ステータスのカスタマイズ](/customize_order_state_machine)
+ [テンプレートのカスタマイズ](/customize_template)
+ [スロットリング機能のカスタマイズ](/customize_throttling)
+ [Symfonyの機能を使った拡張](/customize_symfony)
+ [Symfonyの機能を使った拡張 Commandの開発](/customize_symfony#command)

## デザインカスタマイズ

画面のデザインカスタマイズについてまとめました。

+ [デザインテンプレートの基礎](/design_template)
+ [フォームレイアウトの変更](/design_form)
+ [ブロックの利用](/design_block)
+ [レイアウト管理の利用](/design_layout)
+ [CSSの利用](/design_css)
+ [Sass(scss)の利用](/design_sass)

+ [フロント画面デザイン参考資料（スタイルガイド）](http://eccube4-styleguide.herokuapp.com/){:target="_blank"}
+ [管理画面デザイン参考資料（デザインガイド）](https://doc4.ec-cube.net/pdf/ec-cube4_design-guide180930.pdf){:target="_blank"}

+ [フロント画面テンプレート for Adobe XD](http://downloads.ec-cube.net/manual/documents/eccube4_xd_front_template.zip?argument=2qpV46CP&dmai=a5bf51b05bacc5){:target="_blank"}
+ [管理画面テンプレート for Adobe XD](http://downloads.ec-cube.net/manual/documents/eccube4_xd_admin_template.zip?argument=2qpV46CP&dmai=a5bf51b05bacc5){:target="_blank"}

## プラグイン開発

プラグインの開発方法についてまとめました。

+ [プラグイン仕様](/plugin_spec)
+ [プラグインのインストール](/plugin_install)
+ [プラグイン導入時のトラブル対処法](/plugin_error)
+ [プラグインサンプル](/plugin_sample)
+ [プラグインを開発する](/plugin_development)
+ [オーナーズストア経由のインストールをテストする](/plugin_mock_package_api)
+ [プラグインで推奨の命名規則](/plugin_naming_conventions)
+ [管理画面ナビの拡張](plugin_admin_nav)

## 設定変更

本体の設定変更方法についてまとめました。

+ [多言語化](/i18n_multilingualization)
+ [通貨](/i18n_currency)
+ [タイムゾーン](/i18n_timezone)
+ [環境設定](/environmental_setting)
+ [デバッグモード](/debug_mode)
+ [セッション情報保存方法の変更](/session_handler_settings)

## 開発ツール

開発時に役立つツールについてまとめました。

+ [MailCatcher](/development-tools/mail-catcher)

## セキュリティテストの実施

セキュリティテストについての情報をまとめました。

+ [はじめに](/penetration-testing)
+ [Quick Start](/penetration-testing/quick_start)
+ [イントロダクション](/penetration-testing/introduction)
+ [OWASP ZAP について](/penetration-testing/about_owaspzap)
+ [テスト計画](/penetration-testing/planning)
+ [テストの実施](/penetration-testing/testing)
+ [テスト評価](/penetration-testing/review)
+ [セキュリティの改善](/penetration-testing/improvement)

## セキュリティガイドライン

セキュリティガイドラインについてまとめました。

+ [コーディングについて](/security-guideline/coding)
+ [プラグイン開発におけるセキュリティ強化](/security-guideline/plugin)

## 逆引きリファレンス

知っているとちょっと便利な情報をまとめました。

+ [Tips](/reverse-lookup/tips)
+ [カスタマイズサンプル集](/reverse-lookup/sample-code)

## 運用者向け情報

運用のマニュアルはこちらを御覧ください。

+ [EC-CUBE 4管理・運用 マニュアル](https://www.ec-cube.net/manual/ec-cube4/){:target="_blank"}

## 開発に参加する

開発に参加したい方向けの情報をまとめました。

+ [EC-CUBEの開発に参加するには？（公式サイト）](https://www.ec-cube.net/committer/){:target="_blank"}
+ [開発の概要](/contribution-guide/overview)
+ [プルリクエストの作り方](/contribution-guide/pull-request)

## ドキュメントがみつからないときは

開発ドキュメントの作成やメンテナンスに参加する方法をまとめました。

+ [ドキュメントのリクエスト](/documents/request)
+ [ドキュメントの追加・書き方](/documents/writing-and-formatting)
+ [ドキュメントの投稿](/documents/contribute)

## Supporters

EC-CUBEは以下のサポートを受けています。

+ [SAKURA internet](https://www.sakura.ad.jp/){:target="_blank"}
[![SAKURA internet](./images/3-1-2line-rgb-whiteback.png)](https://www.sakura.ad.jp/){:target="_blank"}

+ [VAddy](https://vaddy.net/ja/){:target="_blank"}
[![VAddy](./images/VAddy_logo.png)](https://vaddy.net/ja/){:target="_blank"}
