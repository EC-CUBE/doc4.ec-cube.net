---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/query/products

---
## 商品一覧の取得

### Query

```graphql
query {
  products (page: 1, limit: 2) {
    edges {
      node {
        id
        name
        create_date
      }
    }
    nodes {
      id
      name
      create_date
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
    "products": {
      "edges": [
        {
          "node": {
            "id": "532",
            "name": "はばのしるのです。そして。",
            "create_date": "2020-07-16T09:49:07+09:00"
          }
        },
        {
          "node": {
            "id": "530",
            "name": "てってまっ赤にして。",
            "create_date": "2020-07-16T09:49:06+09:00"
          }
        }
      ],
      "nodes": [
        {
          "id": "532",
          "name": "はばのしるのです。そして。",
          "create_date": "2020-07-16T09:49:07+09:00"
        },
        {
          "id": "530",
          "name": "てってまっ赤にして。",
          "create_date": "2020-07-16T09:49:06+09:00"
        }
      ],
      "totalCount": 532,
      "pageInfo": {
        "hasNextPage": true,
        "hasPreviousPage": false
      }
    }
  }
}
```


## 商品IDを指定して商品を取得する

### Query

``` graphql
query {
  product(id:2) {
    id,
    name
  }
}
```

### 取得結果

```json
{
  "data": {
    "product": {
      "id": "2",
      "name": "彩のジェラート"
    }
  }
}
```

