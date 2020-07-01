---
layout: single
title: EC-CUBE4.0から4.1へのマイグレーション
keywords: howto update
tags: [quickstart, getting_started]
permalink: update-40-41
summary : EC-CUBE4.0から4.1へのマイグレーションについて記載します。
---

EC-CUBE4.0から4.1へのマイグレーションを解説します。

EC-CUBE本体および一部公式プラグインをSymfony4.4に移行し、コードの移植が必要な箇所をまとめたものです。

- [EC-CUBE 4.1 Roadmap](https://github.com/EC-CUBE/ec-cube/issues/4603)
- [experimental/symfony4.4](https://github.com/EC-CUBE/ec-cube/tree/experimental/symfony4.4)
- [[WIP] 商品レビュープラグイン：Symfony 4.4対応](https://github.com/EC-CUBE/ProductReview-plugin/pull/55)

Symfony4.4での変更をすべて網羅できているわけではないため、記載されていない問題があった場合は、SymfonyのUPGRADEドキュメントも合わせて参照してください。

- [UPGRADE-4.0.md](https://github.com/symfony/symfony/blob/4.4/UPGRADE-4.0.md)

また、EC-CUBE4.0とEC-CUBE4.1での互換性を重視し、発生しているdeprecation noticeを意図的に修正していない箇所もあります。

※ログ等で`User Deprecated: xxx`といった出力が出る場合がありますが、動作上は問題ありません。

本修正を行っても、[テストコード/コンテナの取得](#コンテナの取得)の項目を除き、Symfony3.4/4.4ともに動作します。

## Form関連

### Formのバリデーション

`isValid()`単独で呼び出すことはできません。`isSubmitted() && isValid()` でチェックしてください。

```diff
- if ($form->isValid()) {
+ if ($form->isSubmitted() && $form->isValid()) {
     // do something...
 }
```

### FormExtension

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

## Translator関連

### message.[locale].yaml

変数を利用している場合、コーテーションで囲う必要があります。

```diff
- common.password_sample: 半角英数記号%min%〜%max%文字
+ common.password_sample: '半角英数記号%min%〜%max%文字'
```

## Log関連

### monologの設定

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

## Container関連

### インジェクション時のインターフェース指定

コンストラクタインジェクションやメソッドインジェクション利用時に、具象クラスを指定している場合は、インターフェースを指定するようにしてください。

```diff
- public function __construct(Session $session)
+ public function __construct(SessionInterface $session)

- public function index(Request $request, $page_no = 1, Paginator $paginator)
+ public function index(Request $request, $page_no = 1, PaginatorInterface $paginator)
```

### コンテナからのサービス取得の制限

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

## テストコード

### コンテナの取得

コンテナがメンバ変数からクラス変数に変更されました。

```diff
    public function setUp()
    {
        parent::setUp();

-       $this->helper = $this->container->get(OrderHelper::class);
+       $this->helper = self::$container->get(OrderHelper::class);
    }
```

## その他削除された関数・機能

### Application.php

Eccube\Applicationは削除されました。SymfonyのContainerを使用するようにしてください。
