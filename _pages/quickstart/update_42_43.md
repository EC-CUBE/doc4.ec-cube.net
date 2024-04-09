---
layout: single
title: 4.2から4.3へのマイグレーション
keywords: howto update
tags: [quickstart, getting_started]
permalink: update-42-43
summary : EC-CUBE4.2から4.3へのマイグレーションについて記載します。
---

EC-CUBE4.2から4.3へのマイグレーションを解説します。

EC-CUBE本体および一部公式プラグインをEC-CUBE4.3対応し、コードの移植が必要な箇所をまとめたものです。

- [EC-CUBE 4.3 Roadmap](https://github.com/EC-CUBE/ec-cube/issues/6069){:target="_blank"}
- [Symfony6 対応](https://github.com/EC-CUBE/ec-cube/pull/6073){:target="_blank"}
- [商品レビュープラグイン：Symfony 6対応](https://github.com/EC-CUBE/ProductReview-plugin/pull/93){:target="_blank"}
- [APIプラグイン：Symfony 6対応](https://github.com/EC-CUBE/eccube-api4/pull/164){:target="_blank"}
- [メルマガプラグイン：Symfony 6対応](https://github.com/EC-CUBE/mail-magazine-plugin/pull/146){:target="_blank"}

## プラグインの互換性について

EC-CUBE 4.2と4.3はプラグインの互換性を担保しています。

本ドキュメントの修正を実施いただくことで、両バージョンでプラグインを動作させることができます。

両バージョンでプラグインを動作させる場合、`composer.json` のプラグインコードの変更は不要です。

## PHP8.3対応

EC-CUBE 4.3では、PHPのシステム要件は8.1〜8.3となります。

PHP7.4〜8.1で発生していた非推奨のWarningのうち、特に型パラメータに関するものはエラーとなり動作しなくなります。

以下はエラーの一例です。

```
$ bin/console
PHP Fatal error:  Declaration of Eccube\Kernel::getCacheDir() must be compatible with Symfony\Component\HttpKernel\Kernel::getCacheDir(): string in /path/to/ec-cube/src/Eccube/Kernel.php on line 68
Symfony\Component\ErrorHandler\Error\FatalError^ {#45
  #message: "Compile Error: Declaration of Eccube\Kernel::getCacheDir() must be compatible with Symfony\Component\HttpKernel\Kernel::getCacheDir(): string"
  #code: 0
  #file: "./src/Eccube/Kernel.php"
  #line: 68
  -error: array:4 [
    "type" => 64
    "message" => "Declaration of Eccube\Kernel::getCacheDir() must be compatible with Symfony\Component\HttpKernel\Kernel::getCacheDir(): string"
    "file" => "/path/to/ec-cube/src/Eccube/Kernel.php"
    "line" => 68
  ]
}

```

このようなエラーが出た場合は、引数や戻り値等の型パラメータを指定することで解消できます。

上記のエラーの修正例は、以下になります。

```diff
class Kernel extends BaseKernel
{

...

-    public function getCacheDir()
+    public function getCacheDir(): string
    {
        return $this->getProjectDir().'/var/cache/'.$this->environment;
    }
```

PHPに関する非互換の変更は、php.netの移行ガイドもあわせてご参考ください。

- https://www.php.net/manual/ja/migration82.php
- https://www.php.net/manual/ja/migration83.php

## Symfony6対応

Symfony6での変更をすべて網羅できているわけではないため、記載されていない問題があった場合は、SymfonyのUPGRADEドキュメントも合わせて参照してください。

- [UPGRADE-6.0.md](https://github.com/symfony/symfony/blob/6.4/UPGRADE-6.0.md){:target="_blank"}

また、EC-CUBE4.2とEC-CUBE4.3での互換性を重視し、発生しているdeprecation noticeを意図的に修正していない箇所もあります。

※ログ等で`User Deprecated: xxx`といった出力が出る場合がありますが、動作上は問題ありません。

本修正を行っても、EC-CUBE 4.2/4.3ともに動作します。

### Session関連

#### SessionInterface のインジェクションの廃止

`SessionInterface`によるインジェクションが廃止されました。

`Session`を利用する際は、`RequestStack`から取得するようにしてください。

```diff
- use Symfony\Component\HttpFoundation\Session\SessionInterface;
+ use Symfony\Component\HttpFoundation\RequestStack; 
...

- public function index(SessionInterface $session)
+ public function index(RequestStack $requestStack)
{
+    $session = $requestStack->getSession();    
    $session->get('key');
}
```

EC-CUBE 4.2で動作させる必要がない(4.3のみに対応する)場合は、`Eccube\Session\Session`を利用することができます。

```diff
- use Symfony\Component\HttpFoundation\Session\SessionInterface;
+ use Eccube\Session\Session; 
...

- public function index(SessionInterface $session)
+ public function index(Session $session)
{
    $session->get('key');
}
```

### Container関連

#### コンテナからのサービス取得の制限

`$container->get(Hoge::class)`でサービスを取得する場合は、サービスロケータとして指定する必要があります。

また、コンテナクラスをuseする際は、`Symfony\Component\DependencyInjection\ContainerInterface`ではなく、`Psr\Container\ContainerInterface`をuseしてください。

```diff
- use Symfony\Component\DependencyInjection\ContainerInterface;
+ use Psr\Container\ContainerInterface;

...

public function __construct(ContainerInterface $container)
{
    $this->customerRepository = $this->container->get(CustomerRepository::class);
}

```

services.yamlの記述例：

```
services
    Eccube\Controller\SampleController:
        arguments:
            $container: !service_locator
                Eccube\Repository\CustomerRepository: '@Eccube\Repository\CustomerRepository'
```

PluginMangerでのサービス呼び出しも、本仕様変更の影響を受けます。

コンテナからのサービス取得は、以下が可能です。

```
// ManagerRegistory
- $container->get('doctrine');
- $container->get(ManagerRegistry::class);

// EntityManager
- $container->get('doctrine.orm.default_entity_manager');
- $container->get('doctrine.orm.entity_manager');
- $container->get(EntityManagerInterface::class);

// EccubeConfig
- $container->get(EccubeConfig::class);
```

#### パラメータ取得の変更

`$container->getParameter('key')` でパラメータを取得することはできません。

代わりに、`Eccube\Common\EccubeConfig`を利用してください。

```diff
- use Symfony\Component\DependencyInjection\ContainerInterface;
+ use Eccube\Common\EccubeConfig; 

...

- public function index(ContainerInterface $container)
+ public function index(EccubeConfig $eccubeConfig)
{
-    $projectDir = $container->getParameter('kernel.project_dir');
+    $projectDir = $eccubeConfig->get('kernel.project_dir');
}
```

### PasswordEncoder関連

#### PasswordEncoderの廃止

`PasswordEncoder`は廃止され、`PasswordHaser`を利用するようになりました。

`PasswordHaser`を使用する際のパスワードのハッシュ化処理は、以下のようなコードになります。

```diff
- use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
+ use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

...

public function __construct(
-    EncoderFactoryInterface $encoderFactory,
+    UserPasswordHasherInterface $passwordHasher
) {
-    $this->encoderFactory = $encoderFactory;
+    $this->passwordHasher = $passwordHasher;
}

...

- $encoder = $this->encoderFactory->getEncoder($Customer);

if ($Customer->getPlainPassword() !== $this->eccubeConfig['eccube_default_password']) {
-    $Customer->setPassword($encoder->encodePassword($Customer->getPlainPassword(), $Customer->getSalt()));
+    $Customer->setPassword($this->passwordHasher->hashPassword($Customer, $Customer->getPlainPassword()));
}
```

独自にPasswordEncoderを実装している場合は、以下のようにPasswordHasherを使用するように変更してください。

```diff
- class UserPasswordEncoder implements UserPasswordEncoderInterface
+ class UserPasswordEncoder
```

```diff
Plugin\Api42\EventListener\UserResolveListener:
    arguments:
        - '@Eccube\Security\Core\User\MemberProvider'
-        - '@Plugin\Api42\Security\Core\Encoder\UserPasswordEncoder'
+        - '@Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface'
```

`@Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface` に差し替え、動作が確認できれば、PasswordEncoderは削除して問題ありません。

#### パスワードのハッシュアルゴリズムの自動更新

`PasswordHaser`を利用することで、パスワードのハッシュアルゴリズムが自動更新されるようになります。

使用しているアルゴリズムのチェックは、ユーザーがログインしたタイミングで行われ、アルゴリズムの更新が必要と判断されると、パスワードを新しいアルゴリズムでハッシュ化し、DBに保存します。

EC-CUBE伝統のAUTH_MAGICを使ったアルゴリズムを利用している場合、上記の通りログインのタイミングで更新されます。新しいアルゴリズムはsalt値がハッシュ化したパスワードに含まれるため、以降、dtb_member/customerのsaltカラムは利用されません。

[Password Hashing and Verification](https://symfony.com/doc/current/security/passwords.html){:target="_blank"} も参考にしてください。

### テストコード

#### expectOutputRegex で検証ができない

csvダウロードのレスポンスチェック等、expectOutputRegexで検証している場合、動作しない場合があります。

`$client->getInternalResponse()->getContent()` で出力内容を取得できるので、こちらを利用してください。

```diff
...

- $this->expectOutputRegex("/{$review->getTitle()}/");

$this->client->request(
    'POST',
    $this->generateUrl('product_review_admin_product_review_download')
);

$this->assertTrue($this->client->getResponse()->isSuccessful());

+ $content = $this->client->getInternalResponse()->getContent();
+ $content = mb_convert_encoding($content, 'UTF-8', 'SJIS-win'); // 必要あればエンコーディング変換
+ $this->assertMatchesRegularExpression("/{$review->getTitle()}/", $content);
```

#### セッションを検証したい

セッションの値を検証したい場合、`static::getContainer()->get('session')` は機能しません。

`Eccube\Tests\Web\AbstractWebTestCase::createSession`を利用してください。

```diff

- $session = static::getContainer()->get('session');
+ $session = $this->createSession($this->client)
$outPut = $session->getFlashBag()->get('eccube.admin.success');

```

#### バリデーションメッセージ

一部のバリデーションメッセージが変更になっています。
バリデーションメッセージを検証している自動テストは修正が必要な可能性があります。
プロダクトコードの修正は必要ありません。

### その他の仕様変更

## オーナーズストア経由でのプラグインインストールテスト

EC-CUBE がオーナーズストア経由でプラグインをインストールする際のエンドポイントが変更になっています。

現時点(2024/01/31)では未実装ですが、以下のエンドポイントとなる予定です。

```
ECCUBE_PACKAGE_API_URL=https://package-api-c2.ec-cube.net/v43
```

## purchaseflow.yamlの変更
4.3からpurchaseflow.yamlの形式が変更されました。
従来の「親から子のメソッドを設定する」方式から、「どのメソッドをどの親に設定するか」という方式に変更されています。
またpriorityの設定によって、purchaseflow内の実行順を変更することができます。
実装の詳細は[こちら](https://github.com/EC-CUBE/ec-cube/pull/5147)を参照ください。
### 修正例
例としてProductStatusValidatorの変更を取り上げます。<br>
以下が4.2での定義になります。
```yaml
# 4.2_purchaseflow.yaml
eccube.purchase.flow.cart:
    class: Eccube\Service\PurchaseFlow\PurchaseFlow
    calls:
        - [setFlowType, ['cart']]
        - [setItemValidators, ['@eccube.purchase.flow.cart.item_validators']]
            
eccube.purchase.flow.cart.item_validators:
    class: Doctrine\Common\Collections\ArrayCollection
    arguments:
        - #
            - '@Eccube\Service\PurchaseFlow\Processor\ProductStatusValidator' # 商品の公開状態のチェック
```

続いて以下が4.3での定義になります。
```yaml
# 4.3_purchaseflow.yaml
eccube.purchase.flow.cart:
    class: Eccube\Service\PurchaseFlow\PurchaseFlow
    calls:
        - [ setFlowType, [ 'cart' ] ]

eccube.purchase.flow.item.validator.product.status.validator: # 商品の公開状態のチェック
    class: Eccube\Service\PurchaseFlow\Processor\ProductStatusValidator
    tags:
        - { name: eccube.item.validator, flow_type: cart, priority: 90 }
```
