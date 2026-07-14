---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/mutation/product_stock

---

## 商品在庫の更新

updateProductStock で商品在庫の更新ができます。
`stock_unlimited: false` の場合は `stock` が必須、`stock_unlimited: true` の場合は `stock` を指定できません。

### スキーマ

```graphql
type Mutation {
  updateProductStock(
    """商品コード"""
    code: String!

    """在庫数（在庫無制限の場合、0以上の数値を指定）"""
    stock: Int

    """在庫無制限（無制限は true 、制限は false を指定）"""
    stock_unlimited: Boolean!
  ): ProductClass
}
```

### 実行例

#### リクエスト（在庫有限）

```graphql
mutation {
  updateProductStock (
    code: "sand-01",
    stock: 10,
    stock_unlimited: false
  ) {
    code
    stock
    stock_unlimited
  }
}
```

#### レスポンス（在庫有限）

```json
{
  "data": {
    "updateProductStock": {
      "code": "sand-01",
      "stock": 10,
      "stock_unlimited": false,
    }
  }
}
```

#### リクエスト（在庫無限）

```graphql
mutation {
  updateProductStock (
    code: "sand-01",
    stock_unlimited: true
  ) {
    code
    stock
    stock_unlimited
  }
}
```

#### レスポンス（在庫無限）

```json
{
  "data": {
    "updateProductStock": {
      "code": "sand-01",
      "stock": null,
      "stock_unlimited": true,
    }
  }
}
```

#### Variables を利用したリクエスト例

```graphql
mutation UpdateProductStock(
    $code: String!,
    $stock: Int,
    $unlimited: Boolean!
  ){
  updateProductStock (
    code: $code,
    stock: $stock,
    stock_unlimited: $unlimited
  ) {
    code
    stock
    stock_unlimited
  }
}
```

```json
{
	"code": "sand-01",
	"stock": 10,
	"unlimited": false
}
```
