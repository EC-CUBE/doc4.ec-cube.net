---
title: テンプレートのカスタマイズ
description: Twig関数 eccube_block_*() を利用して、登録したテンプレートの Twig ブロックを任意の場所で呼び出す方法を説明します。
keywords: core カスタマイズ テンプレート
tags: [core, template]
permalink: customize_template
folder: customize

---

## 概要

EC-CUBE4系では、`eccube_block_<block_name>()` という形式の Twig 関数が提供されています。

この関数を利用することで、あらかじめ登録したテンプレートファイル内に定義した Twig の `{% raw %}{% block %}{% endraw %}` を、テンプレート内の任意の場所で呼び出すことができます。

本ページでは、Twig 関数を利用したブロック呼び出し方法について説明します。

## Twig関数によるブロック呼び出し [#2263](https://github.com/EC-CUBE/ec-cube/pull/2263){:target="_blank"}

EC-CUBEが提供するTwig拡張（`Eccube\Twig\Extension\EccubeBlockExtension`）により、`eccube_block_<block_name>()` という形式の関数を用いて、特定の Twig ブロックを直接レンダリングできます。

呼び出し対象となるのは、`Eccube\Common\EccubeTwigBlock` インターフェイスを実装したクラスの `getTwigBlock()` メソッドで登録したテンプレートファイル内に定義された Twig ブロック（`{% raw %}{% block <block_name> %}{% endraw %}`）です。

登録されたテンプレートは、パラメータ `eccube_twig_block_templates` にまとめられます。初期状態では何も登録されていないため、テンプレートを登録しない限り `eccube_block_*()` は何も出力しません。

{: .notice--warning}
管理画面の [コンテンツ管理] > [ブロック管理] で作成したブロックや、`Block` ディレクトリに配置したブロックテンプレートは、この関数の呼び出し対象ではありません。  
例えば、`{% raw %}{{ eccube_block_logo() }}{% endraw %}` と記述しても、ロゴブロック（`logo.twig`）は表示されません。

## 基本的な使い方

### 1. Twig ブロックを定義したテンプレートを作成する

`{% raw %}{% block %}{% endraw %}` を定義したテンプレートファイルを作成します。  
ここでは `app/template/default/TwigBlock/sample.twig` に作成します。

```twig
{% raw %}
{% block member_banner %}
  <div class="ec-memberBanner">
    会員限定のお知らせです。
  </div>
{% endblock %}

{% block greeting %}
  <p>こんにちは、{{ name }}さん</p>
{% endblock %}
{% endraw %}
```

### 2. テンプレートを登録するクラスを作成する

`Eccube\Common\EccubeTwigBlock` インターフェイスを実装したクラスを作成し、`getTwigBlock()` メソッドで手順1のテンプレートのパスを返します。  
ここでは `app/Customize/Twig/TwigBlock.php` に作成します。

```php
<?php

namespace Customize\Twig;

use Eccube\Common\EccubeTwigBlock;

class TwigBlock implements EccubeTwigBlock
{
    /**
     * @return array
     */
    public static function getTwigBlock()
    {
        return [
            'TwigBlock/sample.twig',
        ];
    }
}
```

`EccubeTwigBlock` を実装したクラスは自動的に登録されるため、サービス定義を追加する必要はありません。  
クラスを作成した後は、キャッシュを削除してください。

```bash
bin/console cache:clear --no-warmup
```

{: .notice--info}
プラグインの場合は、`bin/console eccube:plugin:generate` で生成される `TwigBlock.php` の `getTwigBlock()` に、`@プラグインコード/default/xxx.twig` のような形式でテンプレートのパスを記述します。

### 3. テンプレートから呼び出す

テンプレートファイル（.twig）内で、`eccube_block_` に続けてブロック名を指定して呼び出します。

```twig
{% raw %}
{# member_banner ブロックを呼び出す #}
{{ eccube_block_member_banner() }}

{# 引数で変数を渡すこともできます #}
{{ eccube_block_greeting({'name': 'EC-CUBE'}) }}
{% endraw %}
```

呼び出し元テンプレートの変数はそのままブロック内で参照できます。引数で渡した変数は、呼び出し元の変数にマージされます。  
指定したブロックが見つからない場合は、何も出力されず警告（`<block_name> block is not found`）が発生します。

通常、ブロックの配置は管理画面の 「レイアウト管理」 により行われます。
この Twig 関数を使用することで、レイアウト枠（サイドバー・フッターなど）に依存せず、テンプレート内の任意の位置にブロックを埋め込むことが可能です。


## 活用例
特定ページへの固定表示を行いたい場合など、ページテンプレートの特定の HTML 構造の中にブロックを直接組み込むことができます。
また、Twig の条件分岐と組み合わせることで、条件付きで表示されるブロックを実装できます。

```twig
{% raw %}
{% if app.user %}
  {{ eccube_block_member_banner() }}
{% endif %}
{% endraw %}
```
