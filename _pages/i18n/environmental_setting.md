---
title: 環境設定
keywords: debug
tags: [debug, env]
permalink: environmental_setting
folder: i18n

---


## 概要

ec-cubeプロジェクトのルートにある.envファイルを開くと、APP_ENVという定数があります。

APP_ENVの設定値を変更することで、環境毎に異なる設定を行うことが出来ます。

環境変数の設定で開発モードを有効にすると開発に便利な機能を利用できますが、ブラウザでアクセスをすると内部の情報が確認できる状態となります。
開発モードの利用はローカルでの開発のみに限定し、サイトをインターネット上で公開される際は必ずプロダクションモードを利用してください。

## 設定例


### 本番環境

本番環境では、以下のように設定します。

```
APP_ENV=prod
```


### 開発環境

開発環境では以下のように設定します。


```
APP_ENV=dev
```


### テスト環境

テスト環境では以下のように設定します。

```
APP_ENV=test
```


## 設定ファイルの場所

app/config/eccube/packages/配下に環境ごとの設定ファイルがあります。


## プロファイラの表示

`APP_ENV=dev` の場合には、


symfonyのプロファイラを表示することができます。

![デバッグツールバーの位置](/images/environmental_setting/debug_toolbar1.png)

symfonyのアイコンをクリックすると、

以下のように画面下に表示されます。

![デバッグツールバーを開いた状態](/images/environmental_setting/debug_toolbar2.png)


実行されたSQLや、リクエストの内容、

表示速度など多くの情報がブラウザ上で確認できるようになります。
