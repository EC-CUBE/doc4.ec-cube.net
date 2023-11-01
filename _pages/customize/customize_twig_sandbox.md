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

EC-CUBEはSymfonyを用いて開発しています。  
そのSymfonyでは、twigをデフォルトのテンプレートエンジンとして採用しています。  
twigでは、関数や変数を使用することができ、  
その組み合わせによってViewの構築を便利にできるというメリットがあります。
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
* 商品詳細メタタグ

Sandbox内ではtwigの機能のうち、ホワイトリストに記載されたものしか使用できなくなります。  
（通常の文字列は問題なく記述頂くことが可能です）

![Sandboxが使用されている箇所]({{site.baseurl}}/images/customize/sandbox.png)

## デフォルトのホワイトリスト

**※以下に記載のあるtwigの機能のみ、Snadbox内で使用することが出来ます。**  
記載のない機能は使用できないので、もし必要がある場合はホワイトリストに記載ください。

| 種類 | キーワード | 
| --- | -----|
| tag | apply | 
| tag | block | 
| tag | deprecated | 
| tag | embed | 
| tag | extends | 
| tag | flush | 
| tag | for | 
| tag | if | 
| tag | set | 
| tag | spaceless | 
| tag | verbatim | 
| tag | with | 
| tag | form_theme | 
| tag | stopwatch | 
| tag | trans | 
| tag | trans_default_domain | 
| filter | abs | 
| filter | batch |
| filter | capitalize |
| filter | column |
| filter | convert_encoding |
| filter | country_name |
| filter | currency_name |
| filter | currency_symbol |
| filter | date |
| filter | date_modify |
| filter | default |
| filter | escape |
| filter | first |
| filter | format |
| filter | format_currency |
| filter | format_date |
| filter | format_datetime |
| filter | format_number |
| filter | format_time |
| filter | join |
| filter | json_encode |
| filter | keys |
| filter | language_name |
| filter | last |
| filter | length |
| filter | locale_name |
| filter | lower |
| filter | merge |
| filter | nl2br |
| filter | number_format |
| filter | replace |
| filter | reverse |
| filter | round |
| filter | slice |
| filter | spaceless |
| filter | split |
| filter | striptags |
| filter | timezone_name |
| filter | title |
| filter | trim |
| filter | upper |
| filter | url_encode |
| filter | abbr_class |
| filter | abbr_method |
| filter | file_link |
| filter | file_relative |
| filter | format_args |
| filter | format_args_as_text |
| filter | humanize |
| filter | serialize |
| filter | trans |
| filter | yaml_dump |
| filter | yaml_encode |
| filter | currency_symbol |
| filter | date_day |
| filter | date_day_with_weekday |
| filter | date_format |
| filter | date_min |
| filter | date_sec |
| filter | doctrine_format_sql |
| filter | doctrine_prettify_sql |
| filter | doctrine_pretty_query |
| filter | doctrine_replace_query_parameters |
| filter | e |
| filter | ellipsis |
| filter | file_ext_icon |
| filter | form_encode_currency |
| filter | format_*_number |
| filter | format_log_message |
| filter | no_image_product |
| filter | price |
| filter | purify |
| filter | time_ago |
| functions | cycle |
| functions | date |
| functions | max |
| functions | min |
| functions | random |
| functions | range |
| functions | country_timezones |
| functions | absolute_url |
| functions | asset |
| functions | asset_version |
| functions | csrf_token |
| functions | form_parent |
| functions | fragment_uri |
| functions | impersonation_exit_path |
| functions | impersonation_exit_url |
| functions | is_granted |
| functions | logout_path |
| functions | logout_url |
| functions | path |
| functions | relative_path |
| functions | t |
| functions | url |
| functions | active_menus |
| functions | class_categories_as_json |
| functions | country_names |
| functions | csrf_token_for_anchor |
| functions | currency_names |
| functions | currency_symbol |
| functions | field_choices |
| functions | field_errors |
| functions | field_help |
| functions | field_label |
| functions | field_name |
| functions | field_value |
| functions | get_all_carts |
| functions | get_cart |
| functions | get_carts_total_price |
| functions | get_carts_total_quantity |
| functions | has_errors |
| functions | is_reduced_tax_rate |
| functions | language_names |
| functions | product |
| functions | workflow_can |
| functions | workflow_has_marked_place |
| functions | workflow_marked_places |
| functions | workflow_metadata |
| functions | workflow_transition |
| functions | workflow_transition_blockers |
| functions | workflow_transitions |

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

* 本番モード（`APP_ENV=prod`）では、「ログ出力し、sandbox内の記述を消す」
* 開発モード（`APP_ENV=dev`）では、「エラーを発生させる」

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