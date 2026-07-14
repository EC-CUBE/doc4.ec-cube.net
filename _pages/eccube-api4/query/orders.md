---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/query/orders

---
## 受注一覧の取得

### Query

```graphql
query {
  orders (page: 2, limit: 4) {
    nodes {
      id
      payment_total
    }
    totalCount
    pageInfo {
      hasNextPage
      hasPreviousPage
    }
  }
}
```

### 取得結果

``` json
{
  "data": {
    "orders": {
      "nodes": [
        {
          "id": "1600",
          "payment_total": 17132066
        },
        {
          "id": "1599",
          "payment_total": 7374435
        },
        {
          "id": "1598",
          "payment_total": 3277763
        },
        {
          "id": "1597",
          "payment_total": 1854663
        }
      ],
      "totalCount": 1222,
      "pageInfo": {
        "hasNextPage": true,
        "hasPreviousPage": true
      }
    }
  }
}
```

## 注文IDを指定して注文を取得する

### Query

``` graphql
query {
  order(id:2) {
    id,
    payment_total
  }
}
```

### 取得結果

```json
{
  "data": {
    "order": {
      "id": "2",
      "payment_total": 1854663
    }
  }
}
```

