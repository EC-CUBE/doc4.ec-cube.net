---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/webhook

---

## WebHook による通知

商品/受注/会員情報の変更時(登録/更新/削除)に登録されたWebHookに対して変更を通知します。

### 通知リクエスト

以下のようなコマンドと同等の内容を送信します。

```
curl \
    -X POST \
    --header "Content-Type: application/json" \
    --header 'X-ECCUBE-URL': 'https://eccube.exampel.com/' \
    --header 'X-ECCUBE-Signature': '1e577a7deaa9df89edeaf060c1032326814f362a74525e2c534751e82a6f802e' \
    'https://webhook.example.com' \
    --data '{
                "entity": "product",
                "id": 13,
                "action": "created"
            },
            {
                "entity": "product",
                "id": 14,
                "action": "created"
            }'
```

### 対象のEntity

以下のEntityの変更(登録/更新/削除)が対象です。

- product
  - 商品 (Product)
  - 商品規格 (ProductClass)
  - 商品カテゴリ (ProductCategory)
  - 商品タグ (ProductTag)
  - 在庫数 (ProductStock)
  - 商品税率設定 (TaxRule)
  - 商品画像 (ProductImage)
- order
  - 受注 (Order)
  - 受注明細 (OrderItem)
  - 出荷 (Shipping)
  - メール履歴 (MailHistory)
- customer
  - 会員 (Customer)
  - お届け先 (CustomerAddress)
  - お気に入り (CustomerFavoriteProduct)

#### 上記以外のEntityで発火させるカスタマイズ

プラグインやカスタマイズで上記以外の Entity で発火させたい場合は、 `Plugin\Api42\Service\WebHookTrigger` インターフェイスを実装したクラスを定義します。

### 対象のアクション

- 登録: `created`
- 更新: `updated`
- 削除: `deleted`

### シークレット

入力された値をキーにしてペイロードをHMAC-SHA256により署名した値を `X-ECCUBE-Signature` ヘッダーに設定します。

## WebHook側の処理

通知を受け取る側の実装は以下のようにすることを推奨します。

- 素早くレスポンス(200 OK)を返す。受け取った通知をキューに登録して先にレスポンスを返すようにする。処理はレスポンスを返したあとに実行する。
- 空のレスポンスを返す。EC-CUBE側ではレスポンスボディの内容は無視します。
