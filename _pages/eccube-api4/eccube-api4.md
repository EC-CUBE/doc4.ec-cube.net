---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4

---

EC-CUBE4 対応の Web API プラグイン

外部サービスと連携するため、[GraphQL](https://graphql.org) による Web API 機能を実現します。

## システム要件

- EC-CUBE 4.0.5 or higher
- PHP 7.2 or higher
- PostgreSQL or MySQL
- SSLサーバー証明書(TLS) は必須

- *テスト環境の作成には Docker が必要です*

### 現在のバージョンでの制限事項

- *Windows 環境での動作は未確認です*
- *SQLite3 には未対応です*
- *システム間連携を想定したAPIを前提としています。 ネイティブアプリケーションや、 SPA(Single Page Application)、シングルサインオン(SSO)などの用途は想定されていません*
- *Authorization code Grant 以外の Grant には未対応です*
- *OAuth2.0(RFC6749) や OpenID Connect には完全に準拠していません。今後のバージョンアップで準拠を目指していく予定です*



## プラグインのインストール

他のプラグインと同様にEC-CUBE4の管理画面からインストールできます。

[オーナーズストアのEC-CUBE 4.0/4.1 Web API プラグインページへ](https://www.ec-cube.net/products/detail.php?product_id=2121)         
[オーナーズストアのEC-CUBE 4.2/4.3 Web API プラグインページへ](https://www.ec-cube.net/products/detail.php?product_id=2400)

Web API プラグイン自体の開発をされる場合は[GitHubからインストール（開発者向け）](quickstart)をご覧ください。

1. [OAuth2.0 による認可](#oauth20-%E3%81%AB%E3%82%88%E3%82%8B%E8%AA%8D%E5%8F%AF) より API クライアントの認可をしてください。
1. [機能仕様](#%E6%A9%9F%E8%83%BD%E4%BB%95%E6%A7%98) より API をコールしてみましょう！

## OAuth2.0 による認可

EC-CUBE で Web API を実行する際、顧客情報を参照したり、受注情報を更新する場合などは API クライアントの認可が必要です。

このプラグインでは、 [OAuth2.0](http://openid-foundation-japan.github.io/rfc6749.ja.html) プロトコルをサポートしています。

### 対応するフロー

Authorization Code Flow のみに対応しています。

- [Authorization Code Flow](eccube-api4/authZ_code_grant) の設定方法

### エンドポイント

```
Authorization endpoint: https://<ec-cubeのホスト名>/<管理画面URL>/authorize
Token endpoint: https://<ec-cubeのホスト名>/token
```

## 機能仕様

**この記事は GraphQL についての説明はしていませんので、GraphQL 自体の仕様について[GraphQL公式サイト](https://graphql.org/)等でご確認ください。**

エンドポイントはQuery/Mutationで共通です。

```
API endpoint: https://<ec-cubeのホスト名>/api
```

GraphQLの[スキーマ](eccube-api4/schema)は `bin/console eccube:api:dump-schema` コマンドで出力できます。

GraphQL の Query で以下のデータの取得が可能です。

- [商品情報の取得](eccube-api4/query/products)
- [受注情報の取得](eccube-api4/query/orders)
- [顧客情報の取得](eccube-api4/query/customers)

GraphQL の Mutation で以下のデータを更新可能です。

- [商品在庫の更新](eccube-api4/mutation/product_stock)
- [出荷ステータスの更新](eccube-api4/mutation/update_shipped)

商品/受注/会員情報の変更時(登録/更新/削除)に登録されたWebHookに対して変更を通知します。

- [WebHook による通知](eccube-api4/webhook)

## 拡張機構

CustomizeディレクトリやプラグインでAPIを拡張できます。

- [取得可能なデータの追加](eccube-api4/customize/allow_list)
- [Query/Mutationの追加](eccube-api4/customize/query)
