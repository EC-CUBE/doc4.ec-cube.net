---
layout: single
title: Web API β版 プラグインスタートアップガイド
keywords: api
tags: [quickstart, getting_started]
permalink: api_quickstart_guide
description: EC-CUBE 4 Web API の利用方法について簡単な説明です。
---

**Web API は開発途中の機能につき、仕様は変更になる場合があります。**
**また本番環境では絶対に利用しないでください。**

## 実装内容

Web API ベータ版では以下の実装がなされています。

- OAuth2.0に準拠した認可
- GraphQLによる情報の取得

以下は実装されていません。

- GraphQLによる情報の更新
- Webhooks

## EC-CUBEのインストール

EC-CUBE 4 Web API は [GitHubのexperimental/apiブランチ](https://github.com/EC-CUBE/ec-cube/tree/experimental/api) で開発が進められています。
EC-CUBE 4 Web API を利用するためにはライブラリの更新が必要です。通常の EC-CUBE のインストールに加えて、秘密鍵と公開鍵の設置が必要です。
秘密鍵と公開鍵の設置ディレクトリはデフォルトでそれぞれ `{EC-CUBEのインストールディレクトリ}/var/oauth/private.key` , `{EC-CUBEのインストールディレクトリ}/var/oauth/public.key` です。
コマンドラインでのインストールと鍵の設置例は以下です。

```shell
# EC-CUBEのインストール
git clone https://github.com/EC-CUBE/ec-cube.git
cd ec-cube
git checkout -b experimental/api origin/experimental/api
composer install

# DATABASE_URL と DATABASE_SERVER_VERSION を適宜変更
# sed -i -e 's/DATABASE_URL=sqlite:\/\/\/var\/eccube.db/DATABASE_URL=postgres:\/\/postgres@127.0.0.1\/eccube/g' ./.env
# sed -i -e 's/DATABASE_SERVER_VERSION=3/DATABASE_SERVER_VERSION=9/g' ./.env

bin/console e:i --no-interaction

# 鍵の生成
mkdir var/oauth
openssl genrsa -out private.key 2048
openssl rsa -in private.key -pubout -out public.key
mv private.key var/oauth
mv public.key var/oauth
```

詳しい鍵の設定方法については利用しているライブラリ([oauth2-server](https://oauth2.thephpleague.com/)の[ドキュメント](https://oauth2.thephpleague.com/installation/)が参考になります。

## OAuth2.0による認可

**この記事は OAuth2.0 についての説明はしていませんので、OAuth2.0 自体の仕様について他のサイト等でご確認ください。**

OAuth2.0の認可を利用するためにまずはクライアントの登録が必要です。
クライアントは `bin/console trikoder:oauth2:create-client` コマンドで登録できます。
クライアントの登録例は以下です。

```shell
bin/console trikoder:oauth2:create-client client_identifier client_secret --redirect-uri=http://127.0.0.1:8000/ --grant-type=authorization_code --grant-type=client_credentials --grant-type=implicit --grant-type=password --grant-type=refresh_token --scope=read --scope=write
```

詳しいコマンドの使い方については利用しているライブラリ([oauth2-bundle](https://github.com/trikoder/oauth2-bundle)の[ドキュメント](https://github.com/trikoder/oauth2-bundle/blob/master/docs/basic-setup.md)が参考になります。

### エンドポイント

各エンドポイントは以下となります。
アクセスできるHTTPメソッドが制限されているので気をつけましょう。

| エンドポイント | URL | ローカル環境での例 | HTTPメソッド |
|--------------|-----|----------------|------------|
| authorize エンドポイント | `{EC-CUBE_ROOT}/{ADMIN_ROUTE}/authorize` | http://127.0.0.1:8000/admin/authorize | GET |
| token エンドポイント | `{EC-CUBE_ROOT}//token` | http://127.0.0.1:8000/token | POST |

### Authorization code grant での認可

#### 認可の流れ

1. クライアントから authorize エンドポイントへリダイレクト
2. 管理画面へログイン
3. 権限認可の確認
4. 設定した redirect_uri へリダイレクト
5. クライアントから token エンドポイントへPOST
6. JSON形式でレスポンス

#### 1. クライアントから authorize エンドポイントへリダイレクト

以下のパラメータを付与して authorize エンドポイントへリダイレクトしてください。
パラメータはクライアントの登録時に登録した情報と一致している必要があります。

| key | value |
|-----|-------|
| `response_type` | `code` |
| `client_id` | 設定した client id |
| `redirect_uri` | 設定した redirect URI |
| `scope` | スコープを指定。デフォルトでは `read` と `write` を指定可能 |
| `state` | CSRF トークン。セッション等に保存しておき、レスポンス時にチェックをする必要がある。 |

これらのパラメータは認可サーバでチェックされます。

リクエストの例

```uri
http://127.0.0.1:8000/admin/authorize?response_type=code&client_id=client_identifier&redirect_uri=http://127.0.0.1:8000/&scope=read&state=hogehoge
```

#### 4. 設定した redirect_uri へリダイレクト

以下のパラメータを付与して redirect_uri へリダイレクトされます。

| key | value |
|-----|-------|
| `code` | 認可コード |
| `state` | (1) の手順で送信したstateパラメータが送信されます。<br>この値を (1) の値と比較することで、自分自身が送信したリクエストに対するレスポンスであることを確認します。 |

レスポンスの例

```uri
http://127.0.0.1:8000/?code=def502001db9c325b8a4c27bdacb937f72062123319cd5432bb617a56d77fa0501fd259325a9872d3763d1896850520aad1fb96117d914d9ab8dff59476123410b6937488bfcd8288559f761817fd74a2eafee3a60d887d3d7acb7be3e709becb47ce41be6e057afb78641b08d2666e62283412b2f262e5bf9d35590c0d8feb88383b9da0564123cd789aa847802685561349e12c36035e5445f4d991a48372d08a99bc4c2f1f5b5d59a0c881fcb7d8e1d129df8be2b0ee462e07be614ce65f7c0a5aa682177cbfb61b233d2f07d7ee78b0447d2b42fb77e20eec4fc941fdd0c172797811c51224d0a03681923ed1fb9ea4c2a924f4e9da570eadbe6cb0de1190d3ffea9265fab5e3645ef62a110b499733958a08e77ec62d1464261fb09eb8502a5b3ce544eb256a1a42460eed1992c92cfd39e440c3411f4812aa7e207b7706c17fa45f216fd335b21397d0212c3f8267f4e49d4ac77ac4cce42b5e9f1e2d00f7ff0abc6785ef967b0efdb5b98f19948acb9aa328349582987b250d61c41f6c6a8c2&state=hogehoge
```

#### 5. クライアントから token エンドポイントへPOST

以下のパラメータを付与して token エンドポイントへPOSTしてください。
パラメータはクライアントの登録時に登録した情報と一致している必要があります。

| key | value |
|-----|-------|
| `grant_type` | `authorization_code` |
| `client_id` | 設定した client id |
| `client_secret` | 設定した client secret |
| `redirect_uri` | リダイレクトされたものと同じ redirect URI |
| `code` | (1) で取得した認可コード |

curl でのリクエスト例

```shell
curl --location --request POST 'http://127.0.0.1:8000/token' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'grant_type=authorization_code' \
--data-urlencode 'client_id=client_identifier' \
--data-urlencode 'client_secret=client_secret' \
--data-urlencode 'redirect_uri=http://127.0.0.1:8000/' \
--data-urlencode 'code=def502001db9c325b8a4c27bdacb937f72062123319cd5432bb617a56d77fa0501fd259325a9872d3763d1896850520aad1fb96117d914d9ab8dff59476123410b6937488bfcd8288559f761817fd74a2eafee3a60d887d3d7acb7be3e709becb47ce41be6e057afb78641b08d2666e62283412b2f262e5bf9d35590c0d8feb88383b9da0564123cd789aa847802685561349e12c36035e5445f4d991a48372d08a99bc4c2f1f5b5d59a0c881fcb7d8e1d129df8be2b0ee462e07be614ce65f7c0a5aa682177cbfb61b233d2f07d7ee78b0447d2b42fb77e20eec4fc941fdd0c172797811c51224d0a03681923ed1fb9ea4c2a924f4e9da570eadbe6cb0de1190d3ffea9265fab5e3645ef62a110b499733958a08e77ec62d1464261fb09eb8502a5b3ce544eb256a1a42460eed1992c92cfd39e440c3411f4812aa7e207b7706c17fa45f216fd335b21397d0212c3f8267f4e49d4ac77ac4cce42b5e9f1e2d00f7ff0abc6785ef967b0efdb5b98f19948acb9aa328349582987b250d61c41f6c6a8c2'
```

#### 6. JSON形式でレスポンス

EC-CUBEからJSON形式で以下のパラメータが返ってきます。

| key | value |
|-----|-------|
| `token_type` | `Bearer` |
| `expires_in` | アクセストークンのTTL |
| `access_token` | アクセストークン |
| `refresh_token` | リフレッシュトークン |

レスポンス例

```json
{"token_type":"Bearer","expires_in":3600,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImIwODc0MmY1NzJjZjM3OGFiMjk5NmIwYWVjOTFhODNiMWUwZWVlMTg4OTEyMDUxN2Y0ZWVjMmJhNDVkNTFhMzdkOGU4MDNiNDQ2ZmEyOTU4In0.eyJhdWQiOiJlYmQ3NDZlM2E0MjcxNGE2M2YyYjI0N2JmOWI0MjUwNiIsImp0aSI6ImIwODc0MmY1NzJjZjM3OGFiMjk5NmIwYWVjOTFhODNiMWUwZWVlMTg4OTEyMDUxN2Y0ZWVjMmJhNDVkNTFhMzdkOGU4MDNiNDQ2ZmEyOTU4IiwiaWF0IjoxNTgyNzc5NzM4LCJuYmYiOjE1ODI3Nzk3MzgsImV4cCI6MTU4Mjc4MzMzOCwic3ViIjoiYWRtaW4iLCJzY29wZXMiOlsicmVhZCJdfQ.gTbfzr2nzy-wUmYleXlFq1gs-wN7oH8l5nfEsfq5QAZjkl4K4__uLlh2GpStmjveYqY-rxP7Qy7mUBXgIFH3tl0Tnwg52GH9_ftAhz6ZzPilymUzuXtmJ3aj4GZ4Ctm62GtcM1psbzP444BnY9QYuejpQT3tV0VS1enrV8ZkAPKkWvCcOyGLCabfPZ626rThMxMC0I7Mwc-sHAooZ4ebsDUbAQnXj2mwc5zWUpv7r8AsLXnUaMZoAXSk9NQnZvqn7VXo4EkQJkdArT0_QPoPZvFxHrAI5lMWTojMXCZyQMH6cV4OllKHqluij16NA9dGjG73kETyhzbgsm-8e0Hxww","refresh_token":"def502003895e9eb8526f5160b756233895390044561b7de0d67a7a5ae1cef188ed549d95184fb3c824b50e8c6afbe065336e5c18691a750793d1dc8b3d4176f536b1dad6f5c38585133ef0ae44ceac721c65b33b9a8f78c40662112c548acfb3cd4da8b0733c79ac68c22c560f39ef5b4edb33f2ed52579608a3d80559eec37874637afd60a37f53ed5902bc869cca0ce15e09028fccfc27fd60bae8f28b9a98ce068b53b4094d19de000823f6955f9d80b2925e1340932166bdf5014fc083f9b858d7dd39b7707242eb1465b989e65160748fffef0074bc151bc95c59a6134102b18b0349c86e86035632fb235cecd23528f91ad79a599a7186aaad2c7ddca4884401a679212a757beee84f8bb6f05b03d29542091134fd0a41f2356d84726addf03546f383ac93b861bf01a2a1358a94ea856c9a16242c0c896a6feb76e1aa097b4156883368529eae157c46849c4e62e983976d625916f20280004179af27f609dbd7411d7cf25fec08acc9ee60dfe8efb8d7123f30be9c1c05d5ee362a0c70630de7dc0e0d6dbf9b223"}
```

## GraphQLによる情報の取得

**この記事は GraphQL についての説明はしていませんので、GraphQL 自体の仕様について[GraphQL公式サイト](https://graphql.org/)等でご確認ください。**

GraphQL の Query で以下のデータの取得が可能です。

- 商品一覧
- 受注一覧
- 顧客一覧

### エンドポイント

各エンドポイントは以下となります。
取得にはOAuth2.0の `read` の認可が必要になります。

| エンドポイント | URL | ローカル環境での例 | scope |
|--------------|-----|----------------|------------|
| GraphQL Query | `{EC-CUBE_ROOT}/api` | http://127.0.0.1:8000/api | read |

### データの取得例

OAuth2.0 で取得した access_token をヘッダに設定してエンドポイントへ Query を POST するとデータを取得できます。

Queryの例

```query
{
  products {
    id
    name
    ProductClasses {
      id
      code
      price02
      stock
    }
    Status {
      id
      name
    }
    Creator {
      id
    }
    ProductTag {
      id
    }
  }
  orders {
    id
    pre_order_id
    name01
    name02
    message
    Country {
      id
    }
    OrderItems {
      id
      product_name
      price
    }
    Shippings {
      id
    }
  }
  customers {
    name01
    name02
    email
    point
  }
}
```

curlでのアクセス例

```
curl --location --request POST 'http://127.0.0.1:8000/api' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImM5OWNhMWYxZjQ1MDQ5YjU0OWVlOTU4NzIyZjcxNTg0MDFiMTk5MWE1YzY3ZGQ4Y2U1YTA4YzMxNmIyZmVmMDVjZWIxMmU0YmU1YmFlNTViIn0.eyJhdWQiOiJlYmQ3NDZlM2E0MjcxNGE2M2YyYjI0N2JmOWI0MjUwNiIsImp0aSI6ImM5OWNhMWYxZjQ1MDQ5YjU0OWVlOTU4NzIyZjcxNTg0MDFiMTk5MWE1YzY3ZGQ4Y2U1YTA4YzMxNmIyZmVmMDVjZWIxMmU0YmU1YmFlNTViIiwiaWF0IjoxNTgyNzc5ODM5LCJuYmYiOjE1ODI3Nzk4MzksImV4cCI6MTU4Mjc4MzQzOSwic3ViIjoiYWRtaW4iLCJzY29wZXMiOlsicmVhZCJdfQ.OApSTChdaKJ69wHK-Z9rqch0AyGUA7uSnqIujDMWzTUck0sxqsoTVMakluRXPV2WTbc9WeHhkLVhOvnMIQRXZBKIokCC1V-kMWk8q8MER_D2iZ-1fOVyrNR4bS_toZ5YGe7-_AmgrmN6QRL9tAxBbz8RhBwOt62MSi_-RN08gvvScmkY0x8SrhcqLyaHbSMQMGNlaOjRh6a8x3FULsRr93IPUxb6Z214cmb_Tq3dsP7TMFkOlndf2Gco9ivl72Jkqvot89O78GDsMPHaHkWBwkAUpxffu0EgPLIztL--uRZtt3OhM00N6Q8MtUoyc5xs1_ajcBdiujFfp6jljQeQFw' \
--data-raw '{"query":"{\n  products {\n    id\n    name\n    ProductClasses {\n      id\n      code\n      price02\n      stock\n    }\n    Status {\n      id\n      name\n    }\n    Creator {\n      id\n    }\n    ProductTag {\n      id\n    }\n  }\n  orders {\n    id\n    pre_order_id\n    name01\n    name02\n    message\n    Country {\n      id\n    }\n    OrderItems {\n      id\n      product_name\n      price\n    }\n    Shippings {\n      id\n    }\n  }\n  customers {\n    name01\n    name02\n    email\n    point\n  }\n}\n","variables":{}}' | jq .
```

## 小ネタ

### APIの制限を一時的に解除する

OAuth2.0によるアクセスの制限はその Routing に対応する Controller の該当のメソッドに `@Security()` アノテーションを付与することで設定できます。
`ApiController::index` の `@Security()` アノテーションを削除することで一時的にAPIのOAuth2.0のアクセス制限を外すことができます。

```php
    /**
     * @Route("/api", name="api")
     * @Security("has_role('ROLE_OAUTH2_READ')")
     */
    public function index(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $schema = $this->getSchema();
        $result = GraphQL::executeQuery($schema, $body['query']);

        return $this->json($result);
    }
```

https://github.com/EC-CUBE/ec-cube/blob/23e5193fd41dab0007d32ba69728c14c5d5bfde8/src/Eccube/Controller/ApiController.php#L65

### ダミーデータの作成

ダミーデータを作成するコマンド `bin/console eccube:fixtures:generate` が用意されていますのでご活用ください。

コマンド実行例

```shell
bin/console eccube:fixtures:generate --products=2 --orders=2 --customers=2 --without-image --env=dev
```

