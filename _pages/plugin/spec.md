---
title: プラグイン仕様
keywords: plugin spec プラグイン 仕様
tags: [quickstart, getting_started]
permalink: plugin_spec
description: EC-CUBE4のプラグインのディレクトリ構成や設定ファイルなどの仕様を解説します。
---

## 概要

プラグイン仕様の概要を記載します。

## ディレクトリ構成

プラグインの一般的なディレクトリ構成を示します。

```text
app/Plugin/SamplePlugin/
├── Command
├── Common
│   └── Nav.php
├── Controller
├── Doctrine
│   └── Query
├── Entity
├── EventListener
│   └── EventListener.php
├── Form
│   ├── Extension
│   └── Type
├── PluginManager.php
├── Repository
├── Resource
│   ├── assets
│   ├── config
│   │   └── services.yaml
│   ├── locale
│   └── template
└── composer.json
```

上記のすべてのディレクトリ、ファイルが必要なわけではありません。
必須となるのは、`composer.json`のみです。

## 設定ファイル

プラグインの情報を記述する`composer.json`と、コンテナの定義を行う`services.yaml`があります。

### composer.json

プラグインの情報を記述します。
`[プラグインディレクトリ]/composer.json`に設置します。

設定項目は以下のとおりです。

- name: パッケージ名
    - EC-CUBE 4.0系
        - `"ec-cube/[大文字小文字を区別するプラグインコード]"` を記述します。
    - EC-CUBE 4.1系以降
        - `"ec-cube/[すべて小文字のプラグインコード]"` を記述します。
- version: バージョン
    - プラグインのバージョン番号です。
    - phpのバージョンフォーマットに合わせてください。
- description: プラグイン名称
- type: パッケージタイプ
    - `"eccube-plugin"` にします。
- require: 依存パッケージ
    - プラグインが利用するパッケージがあれば追記します。
    - EC-CUBE 4.0系/4.1系
        - `"ec-cube/plugin-installer": 	"~0.0.6 || ^2.0"` は常に記述してください。
    - EC-CUBE 4.2系以降
        - `"ec-cube/plugin-installer": 	"^2.0"` は常に記述してください。
- extra: 追加情報
    - `"code": "[プラグインコード]"` を記述してください。

記載例は以下の通りです。

```json
{
    "name": "ec-cube/productreview42",
    "version": "4.3.0",
    "description": "商品レビュー管理プラグイン",
    "type": "eccube-plugin",
    "require": {
        "ec-cube/plugin-installer": "^2.0"
    },
    "extra": {
        "code": "ProductReview42"
    }
}
```

### services.yaml

コンテナの定義を行います。
`[プラグインディレクトリ]/Resource/config/services.yaml`に設置します。
yamlフォーマットの他に、phpやxmlでも記述可能です。

コンテナの定義については、Symfonyの公式ドキュメントを参照してください。
[https://symfony.com/doc/current/service_container.html](https://symfony.com/doc/current/service_container.html){:target="_blank"}

### その他の設定ファイル
その他のプラグイン独自の設定ファイルをプラグイン内に含めることは可能ですが、プラグインインストール後に生成または変更される設定ファイルは `app/PluginData` ディレクトリ以下に設置するようにしてください。プラグインのディレクトリ内にこれらのファイルを配置した場合、**プラグインのアップデートや再インストールの操作によって設定ファイルが失われます。**
`app/PluginData` ディレクトリ以下を利用する場合は、他のプラグインとの衝突を避けるため、`app/PluginData/[プラグインコード]` ディレクトリを作成して利用することを推奨します。

## 静的コンテンツ

プラグインで利用する静的コンテンツ (html,css,js,画像など) は、`Resource/assets` 以下に配置します。プラグイン以下の `Resource/assets` ディレクトリはインストール/アップデート時に `[EC-CUBEホームディレクトリ]/html/plugin/[プラグインコード]/assets` 以下にコピーされます。 [#3821](https://github.com/EC-CUBE/ec-cube/pull/3821){:target="_blank"}


例えば、以下のように `sample.jpg` ファイルを配置しておくと、 `[EC-CUBEホームディレクトリ]/html/plugin/SamplePlugin/assets/sample.jpg` にコピーされます。

```text
SamplePlugin
 └── Resource
      └── assets
         └── sample.jpg
```

Twigファイルからこの `sample.jpg` へのパスは以下の記述で取得できます。

```twig
{% raw %}{{ asset('SamplePlugin/assets/sample.jpg', 'plugin') }}{% endraw %}
```

展開されたパスは以下のようになります。

```text
/html/plugin/SamplePlugin/assets/sample.jpg
```


## プラグインのパッケージング

開発したプラグインを配布したり、オーナーズストアに申請する際は、アーカイブする必要があります。
アーカイブの方式は、tar.gzで行ってください。
また、以下の点に注意してアーカイブを作成してください。
- フォルダごと圧縮しないようにする
- `.git` ディレクトリや `.DS_Store` ファイル等をアーカイブに含めないようにする

```bash
$ cd app/Plugin/[PluginDir]
$ COPYFILE_DISABLE=1 tar --exclude  ".git" --exclude ".DS_Store" -cvzf ../[PluginDir].tar.gz *
```

## 3.0.xからの変更点

3.0.xからの主な変更点を記載します。

- ServiceProviderの廃止
    - ServiceProviderで行っていたコンテナ定義は、Symfonyの機構を利用するようになりました。
- マイグレーション機構の変更
    - マイグレーションは、doctrine:schema:updateを利用するようになりました。
    - PluginManagerではマイグレーションは行わず、初期データの投入・更新・削除のみ行うようにしてください。
- フックポイントの非推奨化
    - `eccube.event.admin.request`など、リクエストの実行前後に動作するフックポイントは非推奨となりました。
    - twigファイルにパーツを差し込むために利用している場合は、スニペットを用意し、ユーザに貼り付けてもらう方式になります。
    - Responseフックポイントは、レンダリング後のHTMLレスポンスに対して id や class を基準に介入する方式で、プラグイン間の競合やデザイン自由度の低下といった課題があったため非推奨となりました。[#2440](https://github.com/EC-CUBE/ec-cube/issues/2440){:target="_blank"}
- ファイル設置のみのプラグインはロードされない
    - dtb_pluginにレコードが登録されている必要があります。

## スニペットの書き方

スニペットとは、既存のTwigテンプレートに後付けで挿入されるTwigの断片です。  
EC-CUBE4では、画面拡張を行う際にTwigテンプレートを直接改修するのではなく、プラグイン側から`TemplateEvent`を利用してパーツを埋め込む方法が推奨されています。  
この仕組みを利用することで、EC-CUBE本体の改修を避けられる・EC-CUBEのアップデート影響を最小限にできる・プラグインとして安全に機能追加できるといったメリットがあります。

### addSnippet メソッド
`TemplateEvent`に用意されている`addSnippet()`を利用することで、指定したTwigファイルをスニペットとして登録できます。

```php
/**
 * スニペットを追加する.
 *
 * ここで追加したコードは, </body>タグ直前に出力される
 *
 * @param $snippet
 * @param bool $include twigファイルとしてincludeするかどうか
 *
 * @return $this
 */
public function addSnippet($snippet, $include = true)
{
    $this->snippets[$snippet] = $include;

    $this->setParameter('plugin_snippets', $this->snippets);

    return $this;
}
```

### 実装例（サンプル決済プラグイン）
以下は、[サンプル決済プラグイン](https://github.com/EC-CUBE/sample-payment-plugin){:target="_blank"}に含まれる実装例です。

- [sample-payment-plugin/SamplePaymentEvent.php](https://github.com/EC-CUBE/sample-payment-plugin/blob/d025ecde6042c0e935125fe48cf59ae5af570b25/SamplePaymentEvent.php){:target="_blank"}

```php
    /**
     * リッスンしたいサブスクライバのイベント名の配列を返します。
     * 配列のキーはイベント名、値は以下のどれかを指定します。
     * - 呼び出すメソッド名
     * - 呼び出すメソッド名と優先度の配列
     * - 呼び出すメソッド名と優先度の配列の配列
     * 優先度を省略した場合は0
     *
     * 例：
     * - array('eventName' => 'methodName')
     * - array('eventName' => array('methodName', $priority))
     * - array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopping/index.twig' => 'onShoppingIndexTwig',
            'Shopping/confirm.twig' => 'onShoppingConfirmTwig',
            '@admin/Order/edit.twig' => 'onAdminOrderEditTwig',
            'Mypage/navi.twig' => 'onMypageNaviTwig',
        ];
    }

    public function onShoppingIndexTwig(TemplateEvent $event)
    {
        $event->addSnippet('@SamplePayment42/credit.twig');
    }
...
```

ここでは`Shopping/index.twig`が描画される際に、`@SamplePayment42/credit.twig`をスニペットとして追加しています。

- [sample-payment-plugin/Resource/template/credit.twig](https://github.com/EC-CUBE/sample-payment-plugin/blob/4.2/Resource/template/credit.twig)

```twig
<script>
 $(function () {
     $(".ec-orderPayment").last().after($("#credit").detach());
 });
</script>
{% raw %}
{% if Order.Payment.getMethodClass == 'Plugin\\SamplePayment42\\Service\\Method\\CreditCard' %}
    <div id="credit" class="ec-orderPaymentCard">
        <div class="ec-rectHeading">
            <h2>カード(暫定実装)</h2>
        </div>
        <div class="ec-input">
            {# jsで取得したトークンをhiddenでサーバサイドへsubmitする. #}
            {{ form_widget(form.sample_payment_token) }}

            {# カード番号をサーバサイドへPOSTしないよう, name属性は出力しない #}
            <input type="text" id="shopping_order_sample_payment_card_no">
        </div>
    </div>
    <script>
     $(function () {
            $('#shopping-form > div > div.ec-orderRole__summary > div > div.ec-totalBox__btn > button').on('click', function (e) {
                // トークン取得処理
                var card_no = $('#shopping_order_sample_payment_card_no').val();
                if (card_no == '') {
                    alert('カード番号が入力されていません');
                    return false;
                }
                // サーバ通信してトークンを取得
                var token = 'aaabbbccc123456';

                // hiddenにトークンをセット
                $('#shopping_order_sample_payment_token').val(token);
            });
        });
    </script>
{% else %}
    {{ form_widget(form.sample_payment_token, { type: 'hidden', 'id': 'credit' }) }}
{% endif %}
{% endraw %}
```

`@SamplePayment42/credit.twig` は、プラグイン内に配置されたTwigファイルを指します。  
`sample-payment-plugin/Resource/template/credit.twig` に記述し、プラグイン内に配置したTwigを`addSnippet()`で登録するだけで、`Shopping/index.twig` などの決められた挿入位置に画面要素を追加できます。  
このようにEC-CUBE4では、`TemplateEvent`が提供する`addSnippet()` を利用することで、JavaScriptと組み合わせた画面制御をTwigテンプレートを直接改修せずに実現できます。


## プラグインサンプル

- [決済プラグインサンプル](https://github.com/EC-CUBE/sample-payment-plugin){:target="_blank"}
