---
layout: single
title: EC-CUBE4.0から4.1へのマイグレーション
keywords: howto update
tags: [quickstart, getting_started]
permalink: update-40-41
summary : EC-CUBE4.0から4.1へのマイグレーションについて記載します。
---

EC-CUBE4.0から4.1へのマイグレーションを解説します。

EC-CUBE本体および一部公式プラグインをEC-CUBE4.1対応し、コードの移植が必要な箇所をまとめたものです。

- [EC-CUBE 4.1 Roadmap](https://github.com/EC-CUBE/ec-cube/issues/4603)
- [GitHub 4.1ブランチ](https://github.com/EC-CUBE/ec-cube/tree/4.1)
- [Web API プラグイン：Symfony 4.4対応](https://github.com/EC-CUBE/eccube-api4/pull/106)
- [商品レビュープラグイン：Symfony 4.4対応](https://github.com/EC-CUBE/ProductReview-plugin/pull/55)

## Composer2.0対応

Composer 2.0 導入に伴いプラグインの composer.json を必ず変更していただく必要があります。

### name

`ec-cube/<すべて小文字のPluginCode>`

PluginCode はプラグインの namespace に対応し、すべて小文字にする必要があります。

```php
<?php
namespace Plugin\ExamplePlugin;

// 上記の namespace の場合、 composer.json の name は ec-cube/exampleplugin になります。
```

### require

`ec-cube/plugin-installer: "~0.0.6 || ^2.0"` を含める必要があります

```json
  "require": {
    "ec-cube/plugin-installer": "~0.0.6 || ^2.0"
  },
```

### composer.json の変更例

```diff
{
-   "name": "ec-cube/ExamplePlugin",
+   "name": "ec-cube/exampleplugin",
-  "version": "1.0.0",
+  "version": "2.0.0",
  "description": "プラグインのサンプル",
  "type": "eccube-plugin",
  "require": {
-     "ec-cube/plugin-installer": "~0.0.6"
+     "ec-cube/plugin-installer": "~0.0.6 || ^2.0"
   },
  "extra": {
    "code": "ExamplePlugin"
  }
}
```

修正内容について詳しくは[GitHubのIssue](https://github.com/EC-CUBE/ec-cube/issues/4737)をご覧ください。

2020/12/09時点でEC-CUBEが通信するオーナースズトア(package-api)側の4.1対応はされていません。

プラグインのインストールの4.1でのテストは4.0と同様に[オーナーズストア経由のインストールをテストする](plugin_mock_package_api)の手順で行えます。

## Symfony4.4対応

Symfony4.4での変更をすべて網羅できているわけではないため、記載されていない問題があった場合は、SymfonyのUPGRADEドキュメントも合わせて参照してください。

- [UPGRADE-4.0.md](https://github.com/symfony/symfony/blob/4.4/UPGRADE-4.0.md)

また、EC-CUBE4.0とEC-CUBE4.1での互換性を重視し、発生しているdeprecation noticeを意図的に修正していない箇所もあります。

※ログ等で`User Deprecated: xxx`といった出力が出る場合がありますが、動作上は問題ありません。

本修正を行っても、[テストコード/コンテナの取得](#コンテナの取得)の項目を除き、Symfony3.4/4.4ともに動作します。

### Form関連

#### Formのバリデーション

`isValid()`単独で呼び出すことはできません。`isSubmitted() && isValid()` でチェックしてください。

```diff
- if ($form->isValid()) {
+ if ($form->isSubmitted() && $form->isValid()) {
     // do something...
 }
```

#### FormExtension

`getExtendedTypes`メソッドを追加します。

EC-CUBE4.0.x(Symfony3.4)と互換性を保つ場合、`getExtendedType`メソッドを残す必要があります。

```diff
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return EntryType::class;
    }
+
+    /**
+     * Return the class of the type being extended.
+     */
+    public static function getExtendedTypes(): iterable
+    {
+        return [EntryType::class];
+    }
```

### Translator関連

#### message.[locale].yaml

変数を利用している場合、コーテーションで囲う必要があります。

```diff
- common.password_sample: 半角英数記号%min%〜%max%文字
+ common.password_sample: '半角英数記号%min%〜%max%文字'
```

### Log関連

#### monologの設定

`channels`を重複して記述している場合は削除してください。

```diff
monolog:
    channels: ['sample_payment']
    handlers:
        sample_payment:
            type: fingers_crossed
            action_level: info
            passthru_level: info
            handler: sample_payment_rotating_file
            channels: ['sample_payment']
-            channels: ['!event', '!doctrine']
        sample_payment_rotating_file:
            type: rotating_file
            max_files: 60
            path: '%kernel.logs_dir%/%kernel.environment%/sample_payment.log'
            formatter: eccube.log.formatter.line
            level: debug
```

### Container関連

#### インジェクション時のインターフェース指定

コンストラクタインジェクションやメソッドインジェクション利用時に、具象クラスを指定している場合は、インターフェースを指定するようにしてください。

```diff
- public function __construct(Session $session)
+ public function __construct(SessionInterface $session)

- public function index(Request $request, $page_no = 1, Paginator $paginator)
+ public function index(Request $request, $page_no = 1, PaginatorInterface $paginator)
```

#### コンテナからのサービス取得の制限

一部のサービス(doctrine等)を除き、`$container->get(Hoge::class)`で取得することはできません。

インジェクションするか、services.yamlでサービスをpublicに設定してください。

※ただし、publicに変更した場合、パフォーマンスへの影響があります。インジェクションの利用が望ましいです。

インジェクションの記述例：

```diff
-
- public function index()
- {
-     $customerRepository = $this->container->get(CustomerRepository::class);
-     $customerRepository->find(1);
- }

+
+ public function __construct(CustomerRepository $customerRepository)
+ {
+     $this->customerRepository = $customerRepository;
+ }
+
+ public function index()
+ {
+     $this->customerRepository->find(1);
+ }
```

services.yamlの記述例：

```diff
+ services:
+      Plugin\SamplePayment\Service\HogeService
+          public: true
```

PluginManagerでは、インジェクションは利用できません。Repositoryを取得する場合は、以下のコードで取得してください。

```diff
- $pageRepository = $container->get(PageRepository::class);
+ $entityManager = $container->get('doctrine')->getManager();
+ $pageRepository = $entityManager->getRepository(Page::class);
```

### テストコード

#### コンテナの取得

コンテナがメンバ変数からクラス変数に変更されました。

```diff
    public function setUp()
    {
        parent::setUp();

-       $this->helper = $this->container->get(OrderHelper::class);
+       $this->helper = self::$container->get(OrderHelper::class);
    }
```

#### バリデーションメッセージ

一部のバリデーションメッセージが変更になっています。
バリデーションメッセージを検証している自動テストは修正が必要な可能性があります。
プロダクトコードの修正は必要ありません。

### その他の仕様変更

#### 非会員購入時のお客様情報取得方法の変更

非会員購入時にはお客様情報を session に保存していますが、その保持形式がエンティティから配列へ変更になりました。
それに伴い非会員購入時のお客様情報を取得・変更されるようなカスタマイズをされている場合に修正が必要です。

```diff
- $NonMember = $this->session->get('eccube.front.shopping.nonmember')
+ $NonMember = $this->orderHelper->getNonMember('eccube.front.shopping.nonmember')
```

[Customer の Serializable 実装に伴う本体の修正](https://github.com/EC-CUBE/ec-cube/commit/9a84daf16d92a5129eb169ac14f9b219e81c5d90)

## WebAPI対応

EC-CUBE のパッケージに Web API プラグインが同封され、 EC-CUBE インストールで Web API が利用可能になりました。

Web API で取得可能なデータは許可リスト方式のため、プラグインで追加された Entity はデフォルトで取得できません。

追加された Entity の取得を許可する場合は `eccube.api.allow_list` タグを付けたコンポーネントを定義します。

サービスIDは `[プラグインコード].api.allow_list` の形を推奨します。

例えばメーカー管理プラグインでは以下のような ArrayObject の定義をプラグイン内の services.yaml に追加します。

```yaml
services:
    maker4.api.allow_list:
        class: ArrayObject
        tags: ['eccube.api.allow_list']
        arguments:
            - #
                Eccube\Entity\Product: ['maker_url', 'Maker']
                Plugin\Maker4\Entity\Maker: ['id', 'name', 'sort_no', 'create_date', 'update_date']
```

詳しくは[Web API プラグインのドキュメント](https://doc.ec-cube.net/eccube-api4/customize/allow_list)をご確認ください。

### その他削除された関数・機能

#### Application.php

Eccube\Applicationは削除されました。SymfonyのContainerを使用するようにしてください。
