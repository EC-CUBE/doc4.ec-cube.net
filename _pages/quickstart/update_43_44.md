---
layout: single
title: 4.3から4.4へのマイグレーション
keywords: howto update
tags: [quickstart, getting_started]
permalink: update-43-44
summary : EC-CUBE 4.3から4.4へのマイグレーションについて記載します。
---

# 4.3から4.4へのマイグレーション

EC-CUBE 4.3から4.4へのマイグレーションを解説します。

EC-CUBE本体および一部公式プラグインをEC-CUBE 4.4に対応し、コードの移植が必要な箇所をまとめたものです。

## 主な変更点

- **Symfony 7.4 対応**（Symfony 6.x → 7.4）
- **Doctrine ORM 3.0 対応**（Doctrine ORM 2.x → 3.0）
- **PHP 8.2+ 対応**（PHP 8.1〜8.3 → 8.2〜8.5）
- **Annotations → Attributes への完全移行**

## 目次

- [プラグイン・カスタマイズの互換性について](#プラグインカスタマイズの互換性について)
- [環境設定ファイルの更新](#環境設定ファイルの更新)
- [設定ファイル（YAML）の更新](#設定ファイルyamlの更新)
- [PHP 8.2+対応](#php-82対応)
    - [null許容型の明示](#null許容型の明示)
    - [型パラメータの厳格化](#型パラメータの厳格化)
    - [戻り値型の明示](#戻り値型の明示)
    - [nullをarray_key_exists()のキーとして使用](#nullをarray_key_existsのキーとして使用)
    - [PHPDocアノテーションから型宣言への移行](#phpdocアノテーションから型宣言への移行)
- [Symfony 7対応](#symfony-7対応)
    - [バリデーション制約の名前付き引数形式](#バリデーション制約の名前付き引数形式)
    - [ルーティングの変更](#ルーティングの変更)
    - [トランザクション管理の明示化](#トランザクション管理の明示化)
    - [セッションCookie設定の変更](#セッションcookie設定の変更)
- [Doctrine ORM 3.0対応](#doctrine-orm-30対応)
    - [Annotations → Attributes への移行](#annotations--attributes-への移行)
    - [EntityManagerの変更](#entitymanagerの変更)
    - [ClassMetadataの変更](#classmetadataの変更)
    - [型付きプロパティの必須化](#型付きプロパティの必須化)
    - [Doctrine EventSubscriber → Listenerへの変換](#doctrine-eventsubscriber--listenerへの変換)
- [Monolog Processorの変更](#monolog-processorの変更)
- [BCMath対応](#bcmath対応)
- [削除されたクラスへの対応](#削除されたクラスへの対応)
- [その他の仕様変更](#その他の仕様変更)
    - [Rectorによる自動修正](#rectorによる自動修正)
    - [Symfonyバリデーションメッセージの翻訳変更](#symfonyバリデーションメッセージの翻訳変更)
- [プラグイン開発者向け移行ガイド](#プラグイン開発者向け移行ガイド)
- [トラブルシューティング](#トラブルシューティング)
- [移行チェックリスト](#移行チェックリスト)

---

## プラグイン・カスタマイズの互換性について

EC-CUBE 4.3と4.4では、プラグインおよびカスタマイズの互換性は担保されません。

Doctrine ORM 3.0およびSymfony 7.4への大幅なアップグレードに伴い、以下の変更が必要です：

- **Annotations形式のEntityはサポート外**となります。プラグインやカスタマイズでEntityがAnnotations形式で定義されている場合は、Attributes形式への移行が必要です。
- **ルーティングのAnnotations形式はサポート外**となります。プラグインやカスタマイズでControllerの`@Route`アノテーションを使用している場合は、`#[Route]`アトリビュートへの移行が必要です。

本ドキュメントの修正を実施いただくことで、4.4でプラグインやカスタマイズを動作させることができます。

---

## 環境設定ファイルの更新

### .env.dist / .env の更新

**ECCUBE_AUTH_MAGIC の必須化**

Symfony 7では `framework.secret` が空値を許容しないため、`ECCUBE_AUTH_MAGIC` の設定が必須になります。

```bash
# .env.dist または .env に追加
ECCUBE_AUTH_MAGIC=<your-random-secret-key-here>
```

**ランダムな値を生成する方法：**

```bash
# Linux/Macの場合
openssl rand -hex 32

# または
cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 64 | head -n 1
```

---

## 設定ファイル（YAML）の更新

以下の設定ファイルの更新が必要です。これらの変更はEC-CUBE本体で対応されますが、カスタマイズで設定ファイルを上書きしている場合は確認が必要です。

### app/config/eccube/packages/framework.yaml の更新

```yaml
framework:
    secret: '%env(ECCUBE_AUTH_MAGIC)%'

    # Symfony 7で必須の設定
    handle_all_throwables: true

    # セッション設定の更新
    session:
        handler_id: session.handler.native_file
        cookie_secure: auto        # 'true' から 'auto' に変更
        cookie_samesite: none

    # バリデーション設定
    validation:
        enable_attributes: true    # 追加
        email_validation_mode: html5  # 追加
        # enable_annotations は削除（enable_attributesと併用不可）
```

**重要：Symfony 7でのセッションCookie設定のデフォルト値変更**

Symfony 7.0では、セッションCookieのデフォルト設定が以下のように変更されました：

| 設定 | Symfony 6.4以前 | Symfony 7.0以降 |
|------|----------------|----------------|
| `cookie_secure` | 未設定（false相当） | `auto` |
| `cookie_samesite` | 未設定（null相当） | `lax` |

**開発環境での注意事項**

- `cookie_samesite: none` を設定した場合、ブラウザ仕様により **`Secure` フラグが必須** となります
- HTTP環境では `cookie_secure: auto` により Secure フラグが付かないため、ブラウザがCookieを拒否する可能性があります
- 外部決済モジュールとの連携で `SameSite=None` が必要な場合は、開発環境でもHTTPS対応が推奨されます

### app/config/eccube/packages/doctrine.yaml の更新

```yaml
doctrine:
    orm:
        # Doctrine ORM 3.0での必須設定
        controller_resolver:
            auto_mapping: false  # 追加
```

### app/config/eccube/packages/test/doctrine.yaml の更新

```yaml
doctrine:
    dbal:
        use_savepoints: true  # DAMA Doctrine Test Bundle 8.0で必須
```

### app/config/eccube/packages/security.yaml の更新

```yaml
security:
    # enable_authenticator_manager は削除（Symfony 6.2+でデフォルト有効）
    # 以下の行を削除:
    # enable_authenticator_manager: true
```

### phpunit.xml.dist の更新

PHPUnit 11では設定ファイルの形式が変更されました。

**Before (PHPUnit 9.x):**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
    backupGlobals="false"
    colors="true"
    bootstrap="tests/bootstrap.php"
    convertDeprecationsToExceptions="false"
>
    <!-- ... -->
    <listeners>
        <listener class="Symfony\Bridge\PhpUnit\SymfonyTestsListener" />
        <listener class="\DAMA\DoctrineTestBundle\PHPUnit\PHPUnitListener" />
    </listeners>
</phpunit>
```

**After (PHPUnit 11):**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
    backupGlobals="false"
    colors="true"
    bootstrap="tests/bootstrap.php"
>
    <!-- ... -->
    <!-- convertDeprecationsToExceptions属性は削除 -->

    <!-- listeners から extensions に変更 -->
    <extensions>
        <bootstrap class="DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension"/>
    </extensions>
</phpunit>
```

**主な変更点：**
- `convertDeprecationsToExceptions` 属性を削除
- `<listeners>` → `<extensions>` に変更
- `PHPUnitListener` → `PHPUnitExtension` に変更

---

## PHP 8.2+対応

EC-CUBE 4.4では、PHPのシステム要件は **8.2〜8.5** となります。

### null許容型の明示

エラー例

```
Error: Typed property class@anonymous::$value must not be accessed before initialization
```

コード例

```php
// NG
public string $value; // 初期化されていない

// OK
public ?string $value = null; // null許容型として明示
```

### 型パラメータの厳格化

型パラメータに関するエラーが発生する場合があります。

引数の型が不一致のエラー例

```
TypeError: class@anonymous(): Argument #1 ($value) must be of type string, null given, called in 

```

このようなエラーが出た場合は、引数の型パラメータを正確に指定することで解消できます。

コード例

```php
// エラー
// String型を期待しているのに、nullを渡している。
$object = new class {
    public function processString(string $value): string
    {
        return $value;
    }
};

$result = $object->processString(null);
// -> TypeError: class@anonymous(): Argument #1 ($value) must be of type string, null given, called in test.php on line x


// 修正例1
// 引数をStringに修正
$object = new class {
    public function processString(string $value): string
    {
        return $value;
    }
};

$result = $object->processString('test');

// 修正例2
// null許容型に変更
$object = new class {
    public function processString(?string $value): string // stringから?stringへ変更
    {
        return $value;
    }
};

$result = $object->processString(null);
```

### 戻り値型の明示

PHP 8.5では、メソッドの戻り値型を明示する必要がある場合があります。

エラー例

```
TypeError: class@anonymous::getInt(): Return value must be of type int, string returned
```

コード例

```php
// NG: 戻り値型が不一致の場合にエラーが発生
class Sample {
    public function getValue() {
        return 123;
    }
}

// OK: 戻り値型を明示
class Sample {
    public function getValue(): int {
        return 123;
    }
}
```

### nullをarray_key_exists()のキーとして使用

PHP 8.5では、`array_key_exists()`のキー引数に`null`を渡すと`TypeError`が発生します。

```php
// NG: PHP 8.5以降ではTypeErrorが発生
$array = [null => 1];
var_dump(array_key_exists(null, $array));

// OK: 空文字列を使用
$array = ['' => 1];
var_dump(array_key_exists('', $array));
```

### PHPDocアノテーションから型宣言への移行

EC-CUBE 4.3までは、メソッドの引数や戻り値の型をPHPDocの`@param`や`@return`アノテーションで指定していましたが、EC-CUBE 4.4では実際のコードで型宣言を行う必要があります。

#### 引数の型宣言

4.3までの書き方では、PHPDocで型を指定していましたが、4.4では引数に直接型を指定します。

コード例

```php
// 4.3までの書き方（PHPDocアノテーション）
class SampleService
{
    /**
     * @param string $value
     * @param int $count
     */
    public function process($value, $count)
    {
        // ...
    }
}

// 4.4での書き方（型宣言）
class SampleService
{
    public function process(string $value, int $count): void
    {
        // ...
    }
}
```

#### 戻り値の型宣言

4.3までの書き方では、PHPDocで戻り値の型を指定していましたが、4.4では戻り値型を明示的に宣言します。

コード例

```php
// 4.3までの書き方（PHPDocアノテーション）
class SampleService
{
    /**
     * @return string
     */
    public function getValue()
    {
        return 'test';
    }
}

// 4.4での書き方（型宣言）
class SampleService
{
    public function getValue(): string
    {
        return 'test';
    }
}
```

#### null許容型の指定

null許容型の場合も、型宣言で明示的に指定します。

コード例

```php
// 4.3までの書き方（PHPDocアノテーション）
class SampleService
{
    /**
     * @param string|null $value
     * @return string|null
     */
    public function process($value)
    {
        return $value;
    }
}

// 4.4での書き方（型宣言）
class SampleService
{
    public function process(?string $value): ?string
    {
        return $value;
    }
}
```

#### 複合型（Union型）の指定

PHP 8.0以降では、Union型を使用して複数の型を指定できます。

コード例

```php
// 4.3までの書き方（PHPDocアノテーション）
class SampleService
{
    /**
     * @param string|int $value
     * @return string|int
     */
    public function process($value)
    {
        return $value;
    }
}

// 4.4での書き方（Union型）
class SampleService
{
    public function process(string|int $value): string|int
    {
        return $value;
    }
}
```

**注意**: 実際の型宣言がある場合、PHPDocの`@param`や`@return`は不要になります。Rectorを使用すると、型宣言と重複するPHPDocアノテーションは自動的に削除されます。

---

## Symfony 7対応

### バリデーション制約の名前付き引数形式

Symfony 7.4では、バリデーション制約を名前付き引数形式で記述します。

```php
// 4.3までの書き方（配列形式）
new Assert\NotBlank(['message' => 'エラーメッセージ']);
new Assert\Length(['max' => 255, 'maxMessage' => '最大{{ limit }}文字です']);

// 4.4での書き方（名前付き引数形式）
new Assert\NotBlank(message: 'エラーメッセージ');
new Assert\Length(max: 255, maxMessage: '最大{{ limit }}文字です');
```

### ルーティングの変更

Symfony 7.4では、ルーティングのAnnotations形式はサポートされません。カスタマイズでControllerを作成している場合、`@Route`アノテーションを使用している場合は、`#[Route]`アトリビュートへの移行が必要です。

**複数のルートを定義する例**

```php
// 4.3までの書き方（Annotations）
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class OrderController extends AbstractController
{
    /**
     * @Route("/%eccube_admin_route%/order", name="admin_order", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/order/page/{page_no}", name="admin_order_page", requirements={"page_no": "\d+"}, methods={"GET", "POST"})
     * @Template("@admin/Order/index.twig")
     */
    public function index(Request $request, PaginatorInterface $paginator, ?int $page_no = null): array
    {
        // ...
    }
}

// 4.4での書き方（Attributes）
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

class OrderController extends AbstractController
{
    #[Route(path: '/%eccube_admin_route%/order', name: 'admin_order', methods: ['GET', 'POST'])]
    #[Route(path: '/%eccube_admin_route%/order/page/{page_no}', name: 'admin_order_page', requirements: ['page_no' => '\d+'], methods: ['GET', 'POST'])]
    #[Template(template: '@admin/Order/index.twig')]
    public function index(Request $request, PaginatorInterface $paginator, ?int $page_no = null): array
    {
        // ...
    }
}
```

### トランザクション管理の明示化

Symfony 7およびDoctrine ORM 3.0では、トランザクション管理がより厳格になっています。

`EntityManager::lock()`を使用する場合は、明示的にトランザクションを開始する必要があります。

```php
// 4.4での書き方
if (!$this->entityManager->getConnection()->isTransactionActive()) {
    $this->entityManager->getConnection()->beginTransaction();
}

try {
    // ロック処理
    $this->entityManager->lock($entity, LockMode::PESSIMISTIC_WRITE);
    // ...
    $this->entityManager->getConnection()->commit();
} catch (\Exception $e) {
    $this->entityManager->getConnection()->rollBack();
    throw $e;
}
```

### セッションCookie設定の変更

Symfony 7.0では、セッションCookieのデフォルト設定が変更されました。詳細は「[設定ファイル（YAML）の更新](#設定ファイルyamlの更新)」セクションを参照してください。

**開発環境での注意事項**

HTTP環境で開発している場合、`cookie_samesite: none` を設定するとブラウザがCookieを拒否する可能性があります。外部決済モジュールをテストする場合は、HTTPS環境の使用を推奨します。

---

## Doctrine ORM 3.0対応

Doctrine ORM 3.0は、2.xからの大幅なアップグレードとなります。主な変更点を以下に示します。

### Annotations → Attributes への移行

**Doctrine ORM 3.0ではAnnotationsはサポートされません。** すべてのEntityをPHP 8 Attributesに移行する必要があります。

#### Entity定義の変更

```php
// 4.3までの書き方（Annotations）
/**
 * @ORM\Table(name="dtb_sample")
 * @ORM\Entity(repositoryClass="SampleRepository")
 */
class Sample
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
}

// 4.4での書き方（Attributes）
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'dtb_sample')]
#[ORM\Entity(repositoryClass: SampleRepository::class)]
class Sample
{
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;
    
    #[ORM\Column(name: 'name', type: Types::STRING, length: 255)]
    private ?string $name = null;
}
```

#### インデックスとユニーク制約の分離

ORM 3.0では、`@ORM\Table`の`indexes`と`uniqueConstraints`を別々のアトリビュートとして記述します。

```php
// 4.3までの書き方
/**
 * @ORM\Table(
 *     name="dtb_sample",
 *     indexes={
 *         @ORM\Index(name="sample_idx", columns={"create_date"})
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="sample_unique", columns={"code"})
 *     }
 * )
 */

// 4.4での書き方
#[ORM\Table(name: 'dtb_sample')]
#[ORM\Index(name: 'sample_idx', columns: ['create_date'])]
#[ORM\UniqueConstraint(name: 'sample_unique', columns: ['code'])]
class Sample
```

#### 型定数の使用

型指定には`Doctrine\DBAL\Types\Types`の定数を使用することが推奨されます。

```php
use Doctrine\DBAL\Types\Types;

// 推奨される書き方
#[ORM\Column(name: 'id', type: Types::INTEGER)]
#[ORM\Column(name: 'name', type: Types::STRING, length: 255)]
#[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_MUTABLE)]
#[ORM\Column(name: 'is_active', type: Types::BOOLEAN)]
```

### EntityManagerの変更

#### detach()の削除

`EntityManager::detach()`メソッドは削除されました。代わりに`clear()`を使用してください。

```php
// 4.3までの書き方
$this->entityManager->detach($entity);

// 4.4での書き方
$this->entityManager->clear();
```

#### clear()の引数削除

`clear()`メソッドは引数を取らなくなりました。

```php
// 4.3までの書き方
$this->entityManager->clear(Customer::class);

// 4.4での書き方
$this->entityManager->clear();
```

### ClassMetadataの変更

`ClassMetadata::$reflFields`プロパティへの直接アクセスは削除されました。

```php
// 4.3までの書き方
$reflProperty = $metadata->reflFields['fieldName'];

// 4.4での書き方
$reflProperty = $metadata->getReflectionProperty('fieldName');
```

### 型付きプロパティの必須化

Doctrine ORM 3.0では、Entityのプロパティに型宣言を追加する必要があります。

```php
// 4.3までの書き方
/**
 * @var int|null
 */
private $id;

/**
 * @var string|null
 */
private $name;

// 4.4での書き方
private ?int $id = null;
private ?string $name = null;
```

### Doctrine EventSubscriber → Listenerへの変換

Doctrine ORM 3.0では、EventSubscriberからListenerへの移行が推奨されています。カスタマイズで独自にDoctrine EventSubscriberを実装している場合は、Listenerへの移行が必要です。

**Before (EventSubscriber):**
```php
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;

class MyEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postLoad,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        // ...
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        // ...
    }
}
```

**After (Listener):**
```php
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::postLoad)]
class MyEventListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        // ...
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        // ...
    }
}
```

**重要な変更点：**
- `EventSubscriber` インターフェースを削除
- `getSubscribedEvents()` メソッドを削除
- `#[AsDoctrineListener]` 属性を各イベントに追加
- `Doctrine\ORM\Event\*` → `Doctrine\Persistence\Event\*` へ名前空間変更

---

## Monolog Processorの変更

Monolog 3.0では、Processorのシグネチャが変更されました。カスタマイズで独自にMonolog Processorを実装している場合は、シグネチャの変更が必要です。

**Before (Monolog 2.x):**
```php
use Monolog\Processor\ProcessorInterface;

class CustomProcessor implements ProcessorInterface
{
    public function __invoke(array $record): array
    {
        $record['extra']['custom'] = 'value';
        return $record;
    }
}
```

**After (Monolog 3.x):**
```php
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class CustomProcessor implements ProcessorInterface
{
    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['custom'] = 'value';
        return $record;
    }
}
```

**主な変更点：**
- 引数の型が `array` から `LogRecord` に変更
- 戻り値の型が `array` から `LogRecord` に変更
- 配列アクセス `$record['extra']` からオブジェクトプロパティ `$record->extra` に変更

**注意**: EC-CUBE標準のMonolog Processorは本体で対応されます。

---

## BCMath対応

EC-CUBE 4.4では、金額計算の精度向上のため、BCMath関数を使用した精密計算が推奨されます。

### BCMath Polyfillのインストール

`nanasess/bcmath-polyfill`パッケージをインストールすることで、BCMath拡張がない環境でも動作します。

```bash
composer require nanasess/bcmath-polyfill:^1.0
```

このパッケージは以下を提供します：
- **PHP 8.3に存在しない丸め関数の実装**（`bcfloor`、`bcceil`、`bcround`）
- **BCMath拡張の代替実装**（BCMath拡張がない環境向け）

### BCMath関数の基本

```php
// 加算
$total = bcadd('100.50', '200.30', 2);  // '300.80'

// 減算
$diff = bcsub('500.00', '150.75', 2);  // '349.25'

// 乗算
$tax = bcmul('1000', '0.10', 2);  // '100.00'

// 除算
$unit = bcdiv('1000', '3', 2);  // '333.33'

// 比較（-1: $a < $b, 0: $a = $b, 1: $a > $b）
$cmp = bccomp('100.00', '99.99', 2);  // 1

// 丸め処理（nanasess/bcmath-polyfillが提供）
$floor = bcfloor('123.456');   // '123' （切り捨て、整数部分のみ）
$ceil = bcceil('123.456');     // '124' （切り上げ、整数部分のみ）
$round = bcround('123.456', 2);   // '123.46' （四捨五入、精度指定可能）
```

### 実装例

**Before（floatでの計算）:**
```php
public function calculateTax(float $price, float $taxRate): float
{
    return $price * $taxRate / 100;
}

public function calculateTotal(array $items): float
{
    $total = 0;
    foreach ($items as $item) {
        $total += $item->getPrice() * $item->getQuantity();
    }
    return $total;
}
```

**After（BCMathでの精密計算）:**
```php
public function calculateTax(string $price, string $taxRate): string
{
    // price * taxRate / 100
    return bcdiv(bcmul($price, $taxRate, 4), '100', 2);
}

public function calculateTotal(array $items): string
{
    $total = '0';
    foreach ($items as $item) {
        $itemTotal = bcmul(
            (string)$item->getPrice(),
            (string)$item->getQuantity(),
            2
        );
        $total = bcadd($total, $itemTotal, 2);
    }
    return $total;
}
```

### 注意事項

- BCMath関数は**文字列**を引数として受け取り、**文字列**を返します
- `bcadd`、`bcsub`、`bcmul`、`bcdiv`、`bcround`は最後の引数`$scale`で小数点以下の桁数を指定します（通常、金額は2桁）
- **`bcfloor`と`bcceil`は引数が1つのみで、整数部分を返します**（小数点以下の桁数指定はできません）
- 小数点以下の桁数を指定して切り捨て/切り上げする場合は、10の累乗を掛けてから処理します

---

## 削除されたクラスへの対応

### SameSiteNoneCompatSessionHandler

PHP 7.2対応のレガシーセッションハンドラーが削除されました。

**Before (4.3):**
```yaml
# app/config/eccube/packages/framework.yaml
framework:
    session:
        handler_id: Eccube\Session\Storage\Handler\SameSiteNoneCompatSessionHandler
```

**After (4.4):**
```yaml
# app/config/eccube/packages/framework.yaml
framework:
    session:
        handler_id: session.handler.native_file
        cookie_secure: auto
        cookie_samesite: none
```

### AnnotationReaderFacade

アノテーションリーダーは不要になりました。属性はPHPのリフレクションAPIで直接読み取れます。

**Before (4.3):**
```php
use Eccube\DependencyInjection\Facade\AnnotationReaderFacade;

$reader = AnnotationReaderFacade::create();
$annotations = $reader->getClassAnnotations($reflectionClass);

foreach ($annotations as $annotation) {
    if ($annotation instanceof EntityExtension) {
        // ...
    }
}
```

**After (4.4):**
```php
$attributes = $reflectionClass->getAttributes(\Eccube\Attribute\EntityExtension::class);

foreach ($attributes as $attribute) {
    $instance = $attribute->newInstance();
    // ...
}
```

---

## その他の仕様変更

### Rectorによる自動修正

EC-CUBE 4.4では、Rectorを使用してコードの自動修正が可能です。`rector.php`を使用して以下の修正を一括適用できます：

```bash
# Rectorの実行（ドライラン）
vendor/bin/rector process --dry-run

# Rectorの実行（実際に修正）
vendor/bin/rector process
```

適用されるルールセット：
- `SymfonySetList::SYMFONY_74`
- `DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES`
- `DoctrineSetList::DOCTRINE_DBAL_30`
- `DoctrineSetList::DOCTRINE_CODE_QUALITY`

#### ⚠️ Rector適用後の重要な確認事項：DIバインディングとパラメータ名の不一致

**問題の概要**

**Rectorルール**: `ClassPropertyAssignToConstructorPromotionRector`

このRectorルールがコンストラクタプロモーションを適用する際、**パラメータ名が元のプロパティ名に変更される副作用**があります。
`services.yaml`の`bind:`設定とパラメータ名が一致しない場合、**正しいサービスが注入されず、実行時エラーが発生します**。

**具体的な問題**

**Before（Rector適用前）:**
```php
class Cash implements PaymentMethodInterface
{
    private $purchaseFlow;

    public function __construct(PurchaseFlow $shoppingPurchaseFlow)
    {
        $this->purchaseFlow = $shoppingPurchaseFlow;
    }
}
```

**Rector適用後（ClassPropertyAssignToConstructorPromotionRectorによる副作用）:**
```php
class Cash implements PaymentMethodInterface
{
    // ❌ パラメータ名がプロパティ名 $purchaseFlow に変更されてしまう
    public function __construct(private readonly PurchaseFlow $purchaseFlow)
    {
    }
}
```

**問題点:**
- `services.yaml`には `$shoppingPurchaseFlow: '@eccube.purchase.flow.shopping'` と定義されている
- パラメータ名が `$purchaseFlow` に変更されたため、DIバインディングがマッチしない
- 結果として、正しいPurchaseFlowが注入されず、`OrderUpdateProcessor`などが実行されない

**正しい修正後:**
```php
class Cash implements PaymentMethodInterface
{
    // ✅ パラメータ名を $shoppingPurchaseFlow に戻し、プロパティ名も統一
    public function __construct(private readonly PurchaseFlow $shoppingPurchaseFlow)
    {
    }
}
```

#### 影響を受ける可能性のあるパラメータ名

以下のような特殊な命名規則のパラメータは、Rector適用後に必ず確認してください：

- `$shoppingPurchaseFlow` - ショッピングフロー用PurchaseFlow
- `$cartPurchaseFlow` - カートフロー用PurchaseFlow
- `$orderPurchaseFlow` - 注文フロー用PurchaseFlow
- `$_orderStateMachine` - 注文ステートマシン（アンダースコア始まり）
- その他、`services.yaml`の`bind:`セクションで定義されている特殊な命名のパラメータ

#### チェック方法

Rector適用後、以下を確認してください：

```bash
# 1. services.yamlのbind設定を確認
grep -A 10 "bind:" app/config/eccube/services.yaml

# 2. コンストラクタプロモーションが適用されたクラスのパラメータ名を確認
git diff --name-only | xargs grep "private readonly"
```

### Symfonyバリデーションメッセージの翻訳変更

Symfony 7.4では、Validatorコンポーネントの日本語翻訳メッセージが改善されました。この変更により、テストで期待値として使用しているバリデーションメッセージを更新する必要があります。

#### 影響を受ける主な変更

**1. Length制約（文字数制限）**

最も一般的な影響を受ける変更です。

```php
// Before (Symfony 6.4以前)
'この値は、255文字以内で入力してください。'
'この値は、30文字以上で入力してください。'

// After (Symfony 7.4以降)
'長すぎます。この値は255文字以下で入力してください。'
'短すぎます。この値は30文字以上で入力してください。'
```

**2. その他の主な変更**

| 制約 | 旧メッセージ | 新メッセージ |
|------|------------|------------|
| NotBlank | `入力してください。` | `この値は空にできません。` |
| NotNull | `入力してください。` | `値を入力してください。` |
| Email | `無効なメールアドレスです。` | `有効なメールアドレスではありません。` |
| Url | `無効なURLです。` | `有効なURLではありません。` |
| Type | `{{ type }}型でなければなりません。` | `この値は{{ type }}型でなければなりません。` |

#### テストの修正例

**Before (Symfony 6.4以前):**
```php
public static function dataDownloadMaxLengthProvider(): \Iterator
{
    yield ['order_pdf[title]', 'この値は、255文字以内で入力してください。'];
    yield ['order_pdf[message1]', 'この値は、30文字以内で入力してください。'];
}
```

**After (Symfony 7.4以降):**
```php
public static function dataDownloadMaxLengthProvider(): \Iterator
{
    yield ['order_pdf[title]', '長すぎます。この値は255文字以下で入力してください。'];
    yield ['order_pdf[message1]', '長すぎます。この値は30文字以下で入力してください。'];
}
```

#### 従来のメッセージを使用したい場合

プロジェクトの方針として従来のメッセージを維持したい場合、または独自のメッセージを使用したい場合は、`src/Eccube/Resource/locale/validators.ja.yaml` でSymfonyの翻訳を上書きできます。

**validators.ja.yaml での上書き例：**

```yaml
# Symfony 7.4の新しいメッセージを従来の形式に戻す
This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.: この値は、{{ limit }}文字以内で入力してください。
This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.: この値は、{{ limit }}文字以上で入力してください。
This value is not a valid email address.: 無効なメールアドレスです。
This value is not a valid URL.: 無効なURLです。
```

### 依存パッケージのバージョン

主要な依存パッケージのバージョン変更：

| パッケージ | 4.3 | 4.4 |
|-----------|-----|-----|
| Symfony | 6.4.x | 7.4.x |
| Doctrine ORM | 2.x | 3.x |
| Doctrine DBAL | 3.x | 3.8+ |
| PHP | 8.1〜8.3 | 8.2〜8.5 |
| Twig | 3.x | 3.21+ |
| php-cs-fixer | 3.x | 3.75+ |
| Monolog | 2.x | 3.x |
| PHPUnit | 9.5 | 11.0+ |

---

## プラグイン開発者向け移行ガイド

### 1. EntityExtensionの移行

**Before (4.3):**
```php
<?php
namespace Plugin\YourPlugin\Entity;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerSortNoTrait
{
    /**
     * @var int|null
     * @ORM\Column(name="sort_no", type="integer", nullable=true)
     */
    private $sort_no;

    /**
     * @return int|null
     */
    public function getSortNo()
    {
        return $this->sort_no;
    }

    /**
     * @param int|null $sort_no
     * @return $this
     */
    public function setSortNo($sort_no = null)
    {
        $this->sort_no = $sort_no;
        return $this;
    }
}
```

**After (4.4):**
```php
<?php
namespace Plugin\YourPlugin\Entity;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Attribute\EntityExtension;

#[EntityExtension(\Eccube\Entity\Customer::class)]
trait CustomerSortNoTrait
{
    #[ORM\Column(name: 'sort_no', type: 'integer', nullable: true)]
    private ?int $sort_no = null;

    public function getSortNo(): ?int
    {
        return $this->sort_no;
    }

    public function setSortNo(?int $sort_no = null): self
    {
        $this->sort_no = $sort_no;
        return $this;
    }
}
```

### 2. PurchaseFlowプロセッサーの移行

**Before (4.3):**
```php
<?php
namespace Plugin\YourPlugin\Service\PurchaseFlow\Processor;
use Eccube\Annotation\CartFlow;
use Eccube\Annotation\OrderFlow;
use Eccube\Annotation\ShoppingFlow;
use Eccube\Service\PurchaseFlow\ItemValidator;
use Eccube\Service\PurchaseFlow\ItemInterface;
use Eccube\Service\PurchaseFlow\PurchaseContext;

/**
 * @CartFlow()
 * @ShoppingFlow()
 * @OrderFlow()
 */
class YourValidator extends ItemValidator
{
    protected function validate(ItemInterface $item, PurchaseContext $context)
    {
        // バリデーションロジック
    }
}
```

**After (4.4):**
```php
<?php
namespace Plugin\YourPlugin\Service\PurchaseFlow\Processor;
use Eccube\Attribute\CartFlow;
use Eccube\Attribute\OrderFlow;
use Eccube\Attribute\ShoppingFlow;
use Eccube\Service\PurchaseFlow\ItemValidator;
use Eccube\Service\PurchaseFlow\ItemInterface;
use Eccube\Service\PurchaseFlow\PurchaseContext;

#[CartFlow]
#[ShoppingFlow]
#[OrderFlow]
class YourValidator extends ItemValidator
{
    protected function validate(ItemInterface $item, PurchaseContext $context): void
    {
        // バリデーションロジック
    }
}
```

### 3. コントローラーの移行

**Before (4.3):**
```php
<?php
namespace Plugin\YourPlugin\Controller;
use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class YourPluginController extends AbstractController
{
    /**
     * @Route("/your_plugin/index", name="your_plugin_index")
     * @Template("YourPlugin/index.twig")
     */
    public function index(Request $request)
    {
        $data = $this->getData();
        return [
            'data' => $data,
        ];
    }
}
```

**After (4.4):**
```php
<?php
namespace Plugin\YourPlugin\Controller;
use Eccube\Controller\AbstractController;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class YourPluginController extends AbstractController
{
    #[Route('/your_plugin/index', name: 'your_plugin_index')]
    #[Template('YourPlugin/index.twig')]
    public function index(Request $request): array
    {
        $data = $this->getData();
        return [
            'data' => $data,
        ];
    }
}
```

### 4. FormExtensionの移行

**Before (4.3):**
```php
<?php
namespace Plugin\YourPlugin\Form\Extension;
use Eccube\Annotation\FormAppend;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @FormAppend()
 */
class EntryTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('custom_field', TextType::class, [
            'label' => 'カスタムフィールド',
            'required' => false,
        ]);
    }

    public function getExtendedType()
    {
        return \Eccube\Form\Type\Front\EntryType::class;
    }
}
```

**After (4.4):**
```php
<?php
namespace Plugin\YourPlugin\Form\Extension;
use Eccube\Attribute\FormAppend;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

#[FormAppend]
class EntryTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('custom_field', TextType::class, [
            'label' => 'カスタムフィールド',
            'required' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [\Eccube\Form\Type\Front\EntryType::class];
    }
}
```

### 5. PluginManagerの移行

**Before (4.3):**
```php
<?php
namespace Plugin\YourPlugin;
use Eccube\Plugin\AbstractPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginManager extends AbstractPluginManager
{
    public function install(array $meta, ContainerInterface $container)
    {
        // インストール処理
    }

    public function enable(array $meta, ContainerInterface $container)
    {
        // 有効化処理
    }
}
```

**After (4.4):**
```php
<?php
namespace Plugin\YourPlugin;
use Eccube\Plugin\AbstractPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginManager extends AbstractPluginManager
{
    public function install(array $meta, ContainerInterface $container): void
    {
        // インストール処理
    }

    public function update(array $meta, ContainerInterface $container): void
    {
        // アップデート処理
    }

    public function enable(array $meta, ContainerInterface $container): void
    {
        // 有効化処理
    }

    public function disable(array $meta, ContainerInterface $container): void
    {
        // 無効化処理
    }

    public function uninstall(array $meta, ContainerInterface $container): void
    {
        // アンインストール処理
    }
}
```

---

## トラブルシューティング

### よくあるエラーと解決方法

#### 1. アノテーションクラスが見つからないエラー

**エラー:**
```
Class "Eccube\Annotation\EntityExtension" not found
```

**解決方法:**
`use`文を`Eccube\Annotation\*`から`Eccube\Attribute\*`に変更してください。

```php
// Before
use Eccube\Annotation\EntityExtension;

// After
use Eccube\Attribute\EntityExtension;
```

#### 2. SensioFrameworkExtraBundleが見つからないエラー

**エラー:**
```
Class "Sensio\Bundle\FrameworkExtraBundle\Configuration\Template" not found
```

**解決方法:**
`use`文を以下のように変更してください。

```php
// Before
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

// After
use Symfony\Bridge\Twig\Attribute\Template;
```

#### 3. Proxyクラスが見つからないエラー

**エラー:**
```
Class "Doctrine\ORM\Proxy\Proxy" not found
```

**解決方法:**
`use`文を以下のように変更してください。

```php
// Before
use Doctrine\ORM\Proxy\Proxy;

// After
use Doctrine\Persistence\Proxy;
```

#### 4. PHPUnitの`convertDeprecationsToExceptions`エラー

**エラー:**
```
Unknown or risky test attribute "convertDeprecationsToExceptions"
```

**解決方法:**
`phpunit.xml`または`phpunit.xml.dist`から`convertDeprecationsToExceptions`属性を削除してください。

#### 5. データプロバイダーの静的メソッドエラー

**エラー:**
```
Data Provider method must be static
```

**解決方法:**
データプロバイダーメソッドに`static`キーワードを追加してください。

```php
// Before
public function dataProvider()
{
    return [[1, 2]];
}

// After
public static function dataProvider(): array
{
    return [[1, 2]];
}
```

#### 6. AbstractPluginManagerのメソッド戻り値型エラー

**エラー:**
```
Declaration of Plugin\YourPlugin\PluginManager::install() must be compatible with Eccube\Plugin\AbstractPluginManager::install(): void
```

**解決方法:**
PluginManagerのすべてのメソッドに`: void`戻り値型を追加してください。

```php
public function install(array $meta, ContainerInterface $container): void
{
    // ...
}
```

#### 7. ECCUBE_AUTH_MAGICが設定されていないエラー

**エラー:**
```
Environment variable not found: "ECCUBE_AUTH_MAGIC"
```

**解決方法:**
`.env`ファイルに`ECCUBE_AUTH_MAGIC`を追加してください。

```bash
ECCUBE_AUTH_MAGIC=$(openssl rand -hex 32)
```

#### 8. Monolog ProcessorのLogRecordエラー

**エラー:**
```
Argument #1 ($record) must be of type Monolog\LogRecord, array given
```

**解決方法:**
カスタムMonolog Processorのシグネチャを変更してください。

```php
// Before
public function __invoke(array $record): array
{
    // ...
}

// After
use Monolog\LogRecord;

public function __invoke(LogRecord $record): LogRecord
{
    // ...
}
```

#### 9. HTTP環境でログインできない

**症状:**
- Symfony 6.4では `http://localhost:8080` でログインできたが、Symfony 7では失敗する
- セッションCookieが保存されない

**原因:**
Symfony 7.0で `cookie_samesite` のデフォルト値が `lax` に変更されました。`cookie_samesite: none` を設定した場合、ブラウザ仕様により **`Secure` フラグが必須** ですが、HTTP環境では `cookie_secure: auto` により Secure フラグが付かないため、ブラウザがCookieを拒否します。

**解決方法:**
開発環境でHTTPSリバースプロキシを使用するか、開発環境のみ一時的に以下の設定を使用してください：

```yaml
# app/config/eccube/packages/dev/framework.yaml
framework:
    session:
        cookie_secure: false
        cookie_samesite: lax
```

⚠️ **注意**: 外部決済モジュールをテストする場合は、HTTPS環境を使用してください。`SameSite=Lax` では外部サイトからのPOSTコールバックでセッションが維持されません。

#### 10. DIバインディングとパラメータ名の不一致エラー

**症状:**
- テストが失敗する（特に購入フロー関連）
- 特定の処理が実行されない（例: OrderUpdateProcessorが実行されない）
- OrderStatusが更新されない

**原因:**
`ClassPropertyAssignToConstructorPromotionRector`適用時にパラメータ名がプロパティ名に変更され、`services.yaml`の`bind:`設定とマッチしなくなった。

**解決方法:**
コンストラクタパラメータ名を`services.yaml`の`bind:`設定に合わせて修正してください。

```php
// 問題のあるコード（Rector適用後）
public function __construct(private readonly PurchaseFlow $purchaseFlow)
{
}

// 修正後
public function __construct(private readonly PurchaseFlow $shoppingPurchaseFlow)
{
}
```

**確認コマンド:**
```bash
# services.yamlのbind設定を確認
grep -A 10 "bind:" app/config/eccube/services.yaml

# コンストラクタプロモーションが適用されたクラスを確認
git diff --name-only | xargs grep "private readonly"
```

---

## 移行チェックリスト

プラグインまたはカスタマイズコードのアップグレード時に、以下の項目を確認してください。

### 基本チェック

- [ ] PHP 8.2以降を使用している
- [ ] composer.jsonの依存関係をEC-CUBE 4.4対応版に合わせて更新した
- [ ] `composer install`を実行し、すべての依存関係を更新した
- [ ] `.env`ファイルに`ECCUBE_AUTH_MAGIC`を設定した

### 設定ファイルの更新

- [ ] `app/config/eccube/packages/framework.yaml`を更新した
    - [ ] `handle_all_throwables: true`を追加
    - [ ] `session.cookie_secure: auto`に変更
    - [ ] `validation.enable_attributes: true`を追加
    - [ ] `enable_annotations`を削除
- [ ] `app/config/eccube/packages/doctrine.yaml`を更新した
    - [ ] `controller_resolver.auto_mapping: false`を追加
- [ ] `app/config/eccube/packages/test/doctrine.yaml`を更新した
    - [ ] `use_savepoints: true`を追加
- [ ] `app/config/eccube/packages/security.yaml`を更新した
    - [ ] `enable_authenticator_manager`を削除
- [ ] `phpunit.xml.dist`を更新した
    - [ ] `convertDeprecationsToExceptions`属性を削除
    - [ ] `<listeners>`を`<extensions>`に変更

### Rectorによる自動修正

- [ ] `rector.php`を設定した
- [ ] `vendor/bin/rector process --dry-run`で変更内容を確認した
- [ ] `vendor/bin/rector process`で自動修正を適用した
- [ ] 変更内容をレビューし、問題がないことを確認した
- [ ] **⚠️ 重要**: コンストラクタプロモーション適用後、DIバインディングとパラメータ名の一致を確認した
    - [ ] `services.yaml`の`bind:`設定を確認
    - [ ] コンストラクタプロモーションが適用されたクラスのパラメータ名を確認
    - [ ] 特に`$shoppingPurchaseFlow`、`$cartPurchaseFlow`、`$orderPurchaseFlow`等の特殊な命名が正しいか確認
    - [ ] 不一致があれば手動で修正

### アノテーション→属性の移行

- [ ] すべての`use Eccube\Annotation\*`を`use Eccube\Attribute\*`に変更した
- [ ] すべての`@EntityExtension`を`#[EntityExtension(...)]`に変更した
- [ ] すべての`@CartFlow`、`@ShoppingFlow`、`@OrderFlow`を属性に変更した
- [ ] すべての`@FormAppend`を`#[FormAppend]`に変更した
- [ ] すべての`@Route`を`#[Route(...)]`に変更した
- [ ] すべての`@Template`を`#[Template(...)]`に変更した
- [ ] DoctrineのTable属性のindexes/uniqueConstraintsを分離した

### Doctrine関連

- [ ] `Doctrine\ORM\Proxy\Proxy`を`Doctrine\Persistence\Proxy`に変更した
- [ ] EventSubscriberをListenerに移行した（該当する場合）
- [ ] エンティティの属性定義を確認した
- [ ] `Platform::getName()`を`instanceof`チェックに変更した

### AbstractPluginManager

- [ ] すべてのメソッドに`: void`戻り値型を追加した
- [ ] `install()`, `update()`, `enable()`, `disable()`, `uninstall()`, `migration()`のシグネチャを確認した

### 削除されたクラス/サービス

- [ ] `SameSiteNoneCompatSessionHandler`への参照を削除した
- [ ] `AnnotationReaderFacade`への参照を削除した
- [ ] `sensio/framework-extra-bundle`への依存を削除した

### テスト

- [ ] データプロバイダーを静的メソッドに変更した
- [ ] すべてのテストメソッドに戻り値型`: void`を追加した（該当する場合）
- [ ] バリデーションメッセージの期待値を更新した
- [ ] テストが正常に実行されることを確認した

### Monolog（カスタムProcessorがある場合）

- [ ] カスタムProcessorのシグネチャを`LogRecord`型に変更した

### BCMath

- [ ] 金額計算を行っている場合、BCMath関数の使用を検討した
- [ ] 計算精度が要件を満たしていることを確認した

### 動作確認

- [ ] プラグインのインストール/アンインストールが正常に動作する
- [ ] プラグインの有効化/無効化が正常に動作する
- [ ] エンティティ拡張が正しく機能している
- [ ] フォーム拡張が正しく機能している
- [ ] コントローラーのルーティングが正常に動作する
- [ ] PurchaseFlowプロセッサーが正常に動作する
- [ ] 管理画面/フロント画面で期待通りに動作する

---

## 参考資料

### 公式ドキュメント

- [Symfony 7.0 Upgrade Guide](https://symfony.com/doc/current/setup/upgrade_major.html)
- [Doctrine ORM 3.0 Upgrade Guide](https://github.com/doctrine/orm/blob/3.0.x/UPGRADE.md)
- [PHPUnit 11.0 Release Announcement](https://phpunit.de/announcements/phpunit-11.html)
- [PHP 8 Attributes](https://www.php.net/manual/ja/language.attributes.php)
- [Monolog 3.0 Upgrade Guide](https://github.com/Seldaek/monolog/blob/main/UPGRADE.md)

### EC-CUBE関連

- [EC-CUBE 4系 プラグイン開発](https://doc4.ec-cube.net/plugin)
- [EC-CUBE GitHub リポジトリ](https://github.com/EC-CUBE/ec-cube)

---
