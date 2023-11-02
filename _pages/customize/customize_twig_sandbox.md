---
layout: single
title: Twig Sandboxによるホワイトリスト制御
keywords: core カスタマイズ twig ホワイトリスト sandbox サンドボックス
tags: [core, twig, sandbox]
permalink: customize_twig_sandbox
folder: customize
---


---

## Twig Sandboxによるホワイトリスト制御

※この機能はEC-CUBE4.2.3から利用できます。

セキュリティ対策として、不本意なtwig機能の実行を防ぐためにホワイトリストによる制御機能が追加されました。  
この機能を用いることで、twigで使用可能な機能（タグ・フィルター・ファンクション）の実行を取捨選択できるようになります。

### Tips: twigの機能について

EC-CUBEでは、テンプレートエンジンにtwigを使用しています。  
twigでは、関数や変数を使用することができ、その組み合わせによってViewの構築を便利にできるというメリットがあります。  
ただし一方で、その便利な機能を悪用することで予期せぬ情報流出が起きるリスクもあります。  

EC-CUBEのtwigテンプレートでの出力は、標準ではHTMLエスケープが行われます。  
しかし例えば以下のようにrawフィルタ等を用い、意図的にエスケープを解除することが可能です。  
その際、外部からの入力値が含まれていると、攻撃手段として利用される可能性があります。

```
{{"{{ variable|raw " }}}}
```

## Sandboxによる機能制御

上記のリスクを無くすために、EC-CUBEでは以下の部分に対してSandboxを適用しています。  

* 商品詳細フリーエリア
* メタタグ （管理画面＞コンテンツ管理＞ページ管理>メタタグ）

Sandbox内ではtwigの機能のうち、ホワイトリストに記載されたものしか使用できなくなります。  
（通常の文字列は問題なく記述頂くことが可能です）

![Sandboxが使用されている箇所]({{site.baseurl}}/images/customize/sandbox.png)

## デフォルトのホワイトリスト

**※以下に記載のあるtwigの機能のみ、Snadbox内で使用することが出来ます。**  
記載のない機能は使用できないので、もし必要がある場合はホワイトリストに記載ください。

<details>
<summary>tagのホワイトリスト</summary>

{{ "* apply" | markdownify }}
{{ "* block" | markdownify }}
{{ "* deprecated" | markdownify }}
{{ "* embed" | markdownify }}
{{ "* extends" | markdownify }}
{{ "* flush" | markdownify }}
{{ "* for " | markdownify }}
{{ "* if" | markdownify }}
{{ "* set" | markdownify }}
{{ "* spaceless" | markdownify }}
{{ "* verbatim" | markdownify }}
{{ "* with" | markdownify }}
{{ "* form_theme" | markdownify }}
{{ "* stopwatch" | markdownify }}
{{ "* trans" | markdownify }}
{{ "* trans_default_domain" | markdownify }}

</details>


<details>
<summary>filterのホワイトリスト</summary>

{{ "* abs" | markdownify }}
{{ "* batch" | markdownify }}
{{ "* capitalize" | markdownify }}
{{ "* column" | markdownify }}
{{ "* convert_encoding" | markdownify }}
{{ "* country_name" | markdownify }}
{{ "* currency_name" | markdownify }}
{{ "* currency_symbol" | markdownify }}
{{ "* date" | markdownify }}
{{ "* date_modify" | markdownify }}
{{ "* default" | markdownify }}
{{ "* escape" | markdownify }}
{{ "* first" | markdownify }}
{{ "* format" | markdownify }}
{{ "* format_currency" | markdownify }}
{{ "* format_date" | markdownify }}
{{ "* format_datetime" | markdownify }}
{{ "* format_number" | markdownify }}
{{ "* format_time" | markdownify }}
{{ "* join" | markdownify }}
{{ "* json_encode" | markdownify }}
{{ "* keys" | markdownify }}
{{ "* language_name" | markdownify }}
{{ "* last" | markdownify }}
{{ "* length" | markdownify }}
{{ "* locale_name" | markdownify }}
{{ "* lower" | markdownify }}
{{ "* merge" | markdownify }}
{{ "* nl2br" | markdownify }}
{{ "* number_format" | markdownify }}
{{ "* replace" | markdownify }}
{{ "* reverse" | markdownify }}
{{ "* round" | markdownify }}
{{ "* slice" | markdownify }}
{{ "* spaceless" | markdownify }}
{{ "* split" | markdownify }}
{{ "* striptags" | markdownify }}
{{ "* timezone_name" | markdownify }}
{{ "* title" | markdownify }}
{{ "* trim" | markdownify }}
{{ "* upper" | markdownify }}
{{ "* url_encode" | markdownify }}
{{ "* abbr_class" | markdownify }}
{{ "* abbr_method" | markdownify }}
{{ "* file_link" | markdownify }}
{{ "* file_relative" | markdownify }}
{{ "* format_args" | markdownify }}
{{ "* format_args_as_text" | markdownify }}
{{ "* humanize" | markdownify }}
{{ "* serialize" | markdownify }}
{{ "* trans" | markdownify }}
{{ "* yaml_dump" | markdownify }}
{{ "* yaml_encode" | markdownify }}
{{ "* currency_symbol" | markdownify }}
{{ "* date_day" | markdownify }}
{{ "* date_day_with_weekday" | markdownify }}
{{ "* date_format" | markdownify }}
{{ "* date_min" | markdownify }}
{{ "* date_sec" | markdownify }}
{{ "* doctrine_format_sql" | markdownify }}
{{ "* doctrine_prettify_sql" | markdownify }}
{{ "* doctrine_pretty_query" | markdownify }}
{{ "* doctrine_replace_query_parameters" | markdownify }}
{{ "* e" | markdownify }}
{{ "* ellipsis" | markdownify }}
{{ "* file_ext_icon" | markdownify }}
{{ "* form_encode_currency" | markdownify }}
{{ "* format_*_number" | markdownify }}
{{ "* format_log_message" | markdownify }}
{{ "* no_image_product" | markdownify }}
{{ "* price" | markdownify }}
{{ "* purify" | markdownify }}
{{ "* time_ago" | markdownify }}

</details>


<details>
<summary>functionのホワイトリスト</summary>

{{ "* cycle" | markdownify }}
{{ "* date" | markdownify }}
{{ "* max" | markdownify }}
{{ "* min" | markdownify }}
{{ "* random" | markdownify }}
{{ "* range" | markdownify }}
{{ "* country_timezones" | markdownify }}
{{ "* absolute_url" | markdownify }}
{{ "* asset" | markdownify }}
{{ "* asset_version" | markdownify }}
{{ "* csrf_token" | markdownify }}
{{ "* form_parent" | markdownify }}
{{ "* fragment_uri" | markdownify }}
{{ "* impersonation_exit_path" | markdownify }}
{{ "* impersonation_exit_url" | markdownify }}
{{ "* is_granted" | markdownify }}
{{ "* logout_path" | markdownify }}
{{ "* logout_url" | markdownify }}
{{ "* path" | markdownify }}
{{ "* relative_path" | markdownify }}
{{ "* t" | markdownify }}
{{ "* url" | markdownify }}
{{ "* active_menus" | markdownify }}
{{ "* class_categories_as_json" | markdownify }}
{{ "* country_names" | markdownify }}
{{ "* csrf_token_for_anchor" | markdownify }}
{{ "* currency_names" | markdownify }}
{{ "* currency_symbol" | markdownify }}
{{ "* field_choices" | markdownify }}
{{ "* field_errors" | markdownify }}
{{ "* field_help" | markdownify }}
{{ "* field_label" | markdownify }}
{{ "* field_name" | markdownify }}
{{ "* field_value" | markdownify }}
{{ "* get_all_carts" | markdownify }}
{{ "* get_cart" | markdownify }}
{{ "* get_carts_total_price" | markdownify }}
{{ "* get_carts_total_quantity" | markdownify }}
{{ "* has_errors" | markdownify }}
{{ "* is_reduced_tax_rate" | markdownify }}
{{ "* language_names" | markdownify }}
{{ "* product" | markdownify }}
{{ "* workflow_can" | markdownify }}
{{ "* workflow_has_marked_place" | markdownify }}
{{ "* workflow_marked_places" | markdownify }}
{{ "* workflow_metadata" | markdownify }}
{{ "* workflow_transition" | markdownify }}
{{ "* workflow_transition_blockers" | markdownify }}
{{ "* workflow_transitions" | markdownify }}

</details>


## ホワイトリストを編集する場合

設定はyamlファイルで行います。  
以下のファイルを編集します。

`app/config/eccube/packages/twig_extensions.yaml`



デフォルトで入っている以下のリストに対して、追加・削除を行ってください。

### tagの場合

```yaml
parameters:
    eccube.twig_sandbox.allowed_tags:
        - 'apply'
        - 'block'
        - 'deprecated'
        - 'embed'
        - 'extends'
        - 'flush'
        - 'for'
        - 'if'
        - 'set'
        - 'spaceless'
        - 'verbatim'
        - 'with'
        - 'form_theme'
        - 'stopwatch'
        - 'trans'
        - 'trans_default_domain'
```

### filterの場合

```yaml
    eccube.twig_sandbox.allowed_filters:
        - 'abs'
        - 'batch'
        - 'capitalize'
        - 'column'
        - 'convert_encoding'
        - 'country_name'
(以下略)
```

### functionの場合

```yaml
    eccube.twig_sandbox.allowed_functions:
        - 'cycle'
        - 'date'
        - 'max'
        - 'min'
        - 'random'
        - 'range'
(以下略)
```

### その他

クラスメソッド・プロパティの制御を行いたい場合は、以下の項目を編集ください

```yaml
    eccube.twig_sandbox.allowed_methods:
        'Symfony\Bridge\Twig\AppVariable': [ 'getrequest' ]
        'Symfony\Component\HttpFoundation\Request': [ 'geturi' ]
    eccube.twig_sandbox.allowed_properties: []
```

## Sandboxの挙動そのものを自身で編集したい場合

現在、Sandbox内でホワイトリストで許可されていない記述が現れた場合、

* 本番モード（`APP_ENV=prod`）では、「エラーをログ出力し、sandbox内の記述を消す」
* 開発モード（`APP_ENV=dev`）では、「エラーを表示させる」

という挙動をするようになっております。

この挙動自身をカスタマイズしたい場合、以下のソースコードをご編集ください。  
`src/Eccube/Twig/Extension/IgnoreTwigSandboxErrorExtension.php`

```php
// devではエラー画面が表示されるようにする
$appEnv = env('APP_ENV');
if ($appEnv === 'dev') {
    throw $e;
} else {
    // ログ出力
    log_warning($e->getMessage(), ['exception' => $e]);

    // 例外がスローされた場合、sandboxが効いた状態になってしまうため追加
    $sandbox = $env->getExtension(SandboxExtension::class);
    if (!$sandbox->isSandboxedGlobally()) {
        $sandbox->disableSandbox();
    }
```

## 参考情報

当機能のホワイトリスト制御は、twigのSandboxを利用しております。  
こちらのドキュメントも併せてご参照ください。

[Sandbox Extension](https://php-log.net/twig/3.x/api.html#sandbox-extension){:target="_blank"}