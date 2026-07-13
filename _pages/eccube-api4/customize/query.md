---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/customize/query

---

## Query/Mutationの追加

Query は `Plugin\Api42\GraphQL\Query` インタフェースを、 Mutation は `Plugin\Api42\GraphQL\Mutation` インタフェースを実装したクラスを作成することで Query/Mutation の追加が可能です。

`Hello Query!` の文字列を返す最小のQueryの実装例は以下です。

```php
<?php

namespace Customize\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Plugin\Api42\GraphQL\Query;

class HelloQuery implements Query
{
    public function getName()
    {
        return 'hello';
    }

    public function getQuery()
    {
        return [
            'type' => Type::string(),
            'resolve' => function ($root) {
                return 'Hello Query!';
            },
        ];
    }
}
```

リクエスト

```graphql
query {
  hello
}
```

レスポンス

```json
{
  "data": {
    "hello": "Hello Query!"
  }
}
```

同様に `Mutation` を実装し、 `resolve` 内で更新処理を記載すると Mutation を追加できます。

### 参考

プラグインのデフォルトの Query および Mutation の実装は `Api42/GraphQL/Query` および `Api42/GraphQL/Mutation` にあります。
