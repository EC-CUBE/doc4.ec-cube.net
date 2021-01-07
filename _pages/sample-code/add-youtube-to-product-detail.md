---
title: 商品詳細画面へのYoutube動画の追加
keywords: core カスタマイズ Entity Product
tags: [core, entity, product]
permalink: sample-code/add-youtube-to-product-detail
folder: sample-code
---

## 商品詳細画面へのYoutube動画の追加

1. ProductTraitの作成
1. proxyの生成とDBスキーマの変更
1. detail.twigへのyoutubeタグ追加

### ProductTraitの作成

trait と `@EntityExtension` アノテーションを使用して、 ProductEntity のフィールドを拡張可能です。
継承を使用せずに Proxy クラスを生成するため、複数のプラグインや、独自カスタマイズからの拡張を共存できます。

``` php
<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
  * @EntityExtension("Eccube\Entity\Product")
 */
trait ProductTrait
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $youtube_url;
}
```

`@EntityExtension` アノテーションの引数には、 trait を適用したいクラスを指定します。
trait には、追加したいフィールドを実装します。
`@ORM\Column` など、 Doctrine ORM のアノテーションを使用して、データベース定義を設定します。

#### 管理画面でのフォーム表示

`@EntityExtension` アノテーションで拡張したフィールドに `@FormAppend` アノテーションを追加することで、フォームを自動生成できます。

``` php
<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Eccube\EntityExtension("Eccube\Entity\Product")
 */
trait ProductTrait
{
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Eccube\FormAppend(
     *     auto_render=true,
     *     type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *     options={
     *          "required": false,
     *          "label": "Youtube URL"
     *     })
     */
    public $youtube_url;

    /**
     * 無くても動くけど一応作る
     */
    public function getYoutubeUrl()
    {
        return $this->youtube_url;
    }

    /**
     * 無くても動くけど一応作る
     */
    public function setYoutubeUrl($url = null)
    {
        $this->youtube_url = $url;
        return $this;
    }
}

```

`@FormAppend` アノテーションを追加すると、対象のエンティティを使用しているフォームに、追加したフィールドのフォームが追加されます。
入力チェックを使用したい場合は、 `@NotBlank` など [Symfony 標準のアノテーション](https://symfony.com/doc/current/reference/constraints.html){:target="_blank"} を使用できます。

フォームを詳細にカスタマイズしたい場合は、 `auto_render=true` を指定し、 `form_theme` や `type`, `option` を個別に指定します。


### Proxyの生成とDBスキーマの変更

trait の実装ができたら、 `bin/console eccube:generate:proxies` コマンドで Proxy クラスを生成します。

```
bin/console eccube:generate:proxies
```

Proxy を生成できたら、 `bin/console doctrine:schema:update` コマンドで、定義をデータベースに反映します。

```
## 作成した Proxy クラスを確実に認識できるようキャッシュを削除
bin/console cache:clear --no-warmup

## 実行する SQL を確認
bin/console doctrine:schema:update --dump-sql

## SQL を実行
bin/console doctrine:schema:update --dump-sql --force
```

### detail.twigへのyoutubeタグ追加

テンプレートの``Prdoduct/detail.twig``のYoutube動画を表示させたい箇所へYoutubeのタグを追加します。

<script src="https://gist.github.com/tao-s/3b67f9f6dc19f78593eda49877df3b6b.js"></script>

URLをtwigテンプレート上でエスケープせずに出力するには、`url`フィルターを使います。

`youtube_url`がちゃんと定義されているかどうかをチェックして、定義されている時だけYoutubeのタグを出すようにします。
