---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/query/customers

---
## 顧客一覧の取得

```graphql
query {
  customers (page: 1, limit: 3, multi: "440", customer_status: ["3"]) {
    nodes {
      id
      name01
      name02
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

```json
{
  "data": {
    "customers": {
      "nodes": [
        {
          "id": "440",
          "name01": "山口",
          "name02": "さゆり"
        }
      ],
      "totalCount": 1,
      "pageInfo": {
        "hasNextPage": false,
        "hasPreviousPage": false
      }
    }
  }
}
```

## 顧客IDを指定して顧客を取得する

### Query

``` graphql
query {
  customer(id:2) {
    id,
    name01
  }
}
```

### 取得結果

```json
{
  "data": {
    "customer": {
      "id": "2",
      "name01": "山口"
    }
  }
}

```
