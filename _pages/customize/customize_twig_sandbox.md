---
layout: single
title: Twig Sandboxによる許可リスト制御
keywords: core カスタマイズ twig 許可リスト sandbox サンドボックス
tags: [core, twig, sandbox]
permalink: customize_twig_sandbox
folder: customize
---


---

## Twig Sandboxによる許可リスト制御

※この機能はEC-CUBE4.2.3から利用できます。

セキュリティ対策として、不本意なtwig機能の実行を防ぐために許可リストによる制御機能が追加されました。  
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

Sandbox内ではtwigの機能のうち、許可リストに記載されたものしか使用できなくなります。  
（通常の文字列は問題なく記述頂くことが可能です）

![Sandboxが使用されている箇所]({{site.baseurl}}/images/customize/sandbox.png)

## デフォルトの許可リスト

**※以下に記載のあるtwigの機能のみ、Snadbox内で使用することが出来ます。**  
記載のない機能は使用できないので、もし必要がある場合は許可リストに記載ください。

[デフォルトの許可リストの設定](https://github.com/EC-CUBE/ec-cube/blob/4.2/app/config/eccube/packages/twig_extensions.yaml){:target="_blank"}


## 許可リストを編集する場合

設定はyamlファイルで行います。  
以下のファイルを編集します。

* デフォルトの許可リストの場合、 `app/config/eccube/packages/twig_extensions.yaml`
* Customizeの場合、`app/Customize/Resource/config/services.yaml``

以下のリストを用意し、追加・削除を行ってください。

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
(以下略)
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

メソッド・プロパティの制御を行いたい場合は、以下の項目を編集ください

```yaml
    eccube.twig_sandbox.allowed_methods:
        'Symfony\Bridge\Twig\AppVariable': [ 'getrequest' ]
        'Symfony\Component\HttpFoundation\Request': [ 'geturi' ]
    eccube.twig_sandbox.allowed_properties: []
```

## Sandboxの挙動について

現在、Sandbox内で許可リストで許可されていない記述が現れた場合、

* 開発モード（`APP_ENV=dev`）では、「エラーを表示させる」
* 本番モード（`APP_ENV=prod`）では、「エラーをログ出力し、sandbox内の記述を消す」

という挙動をするようになっております。

### 開発モードの場合

以下のようなエラー画面が表示されます。

![エラー画面]({{site.baseurl}}/images/customize/error_screen.png)


### 本番モードの場合

以下のような文言がログファイルに出力されています。

```
Filter "abs" is not allowed in "__string_template__cceb5b1ce6c6124b4a33368da2e5f5c5" at line 3
```

![エラー画面]({{site.baseurl}}/images/customize/log_screen.png)


### 今まで表示されていた商品詳細の情報が急に表示されなくなった場合

上記のSandboxの機能により、許可リストに記載されていないキーワードが使用されている可能性があります。  
キーワードが制限されたログが出力されているかどうか確認してください。

出力されている場合は、[許可リスト](#デフォルトの許可リスト) を確認いただき、必要であればそのキーワードを追加ください。

## 参考情報

当機能の許可リスト制御は、twigのSandboxを利用しております。  
こちらのドキュメントも併せてご参照ください。

[Sandbox Extension](https://php-log.net/twig/3.x/api.html#sandbox-extension){:target="_blank"}