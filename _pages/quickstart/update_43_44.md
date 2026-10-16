---
layout: single
title: 4.3から4.4へのマイグレーション
keywords: howto update
tags: [quickstart, getting_started]
permalink: update-43-44
summary : EC-CUBE 4.3から4.4へのマイグレーションについて記載します。
---

# 4.3から4.4へのマイグレーション

EC-CUBE本体および一部公式プラグインを4.4に対応した際に、コードの移植が必要になった箇所をまとめたものです。

## 主な変更点

- Symfony 7.4 対応（Symfony 6.4 → 7.4）
- Doctrine ORM 3 / DBAL 4 対応（ORM 2.x → 3.x、DBAL 3.x → 4.x）
- PHP 8.2+ 対応（PHP 8.1〜8.3 → 8.2〜8.5）
- Annotations → Attributes への完全移行（`doctrine/annotations`、`sensio/framework-extra-bundle` を依存から削除）
- 在庫数を `ProductStock::stock` に一本化（`dtb_product_class.stock` 列を削除）
- 非推奨 public API と未発火のイベント定数を削除

## 目次

- [プラグイン・カスタマイズの互換性について](#プラグインカスタマイズの互換性について)
- [環境設定ファイルの更新](#環境設定ファイルの更新)
- [設定ファイル（YAML）の更新](#設定ファイルyamlの更新)
- [PHP 8.2+対応](#php-82対応)
- [Symfony 7対応](#symfony-7対応)
- [Doctrine ORM 3.0対応](#doctrine-orm-30対応)
- [在庫数の持ち方の変更](#在庫数の持ち方の変更)
- [Monolog Processorの変更](#monolog-processorの変更)
- [BCMath対応](#bcmath対応)
- [削除されたクラスへの対応](#削除されたクラスへの対応)
- [削除された public API・イベント定数](#削除された-public-apiイベント定数)
- [その他の仕様変更](#その他の仕様変更)
- [プラグイン開発者向け移行ガイド](#プラグイン開発者向け移行ガイド)
- [トラブルシューティング](#トラブルシューティング)
- [移行チェックリスト](#移行チェックリスト)
- [参考資料](#参考資料)

---

## プラグイン・カスタマイズの互換性について

EC-CUBE 4.3と4.4では、プラグインおよびカスタマイズの互換性は担保されません。Doctrine ORM 3.0およびSymfony 7.4へのアップグレードに伴い、Annotations形式のEntityと`@Route`アノテーションはサポート外になります。それぞれAttributes形式、`#[Route]`アトリビュートへ移行してください。

本ドキュメントの修正を実施すると、4.4でプラグインやカスタマイズを動作させることができます。

---

## 環境設定ファイルの更新

Symfony 7では`framework.secret`が空値を許容しないため、`.env.dist` / `.env`の`ECCUBE_AUTH_MAGIC`が必須になります。

```bash
# .env.dist または .env に追加
ECCUBE_AUTH_MAGIC=<your-random-secret-key-here>

# ランダムな値の生成例（Linux/Mac）
openssl rand -hex 32
```

---

## 設定ファイル（YAML）の更新

これらの変更はEC-CUBE本体で対応されますが、カスタマイズで設定ファイルを上書きしている場合は確認してください。

### app/config/eccube/packages/framework.yaml

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

    # ESI / fragments は無効化（/_fragment を公開しない）
    # render_esi() / render_hinclude() を使う場合のみ有効にする
    esi: { enabled: false }
    fragments: { enabled: false }
```

Symfony 7.0でセッションCookieのデフォルト値が変わりました。

| 設定 | Symfony 6.4以前 | Symfony 7.0以降 |
|------|----------------|----------------|
| `cookie_secure` | 未設定（false相当） | `auto` |
| `cookie_samesite` | 未設定（null相当） | `lax` |

`cookie_samesite: none`はブラウザ仕様により`Secure`フラグが必須です。HTTP環境では`cookie_secure: auto`でSecureフラグが付かないため、ブラウザがCookieを拒否してログインできなくなります。開発環境での対処は「[トラブルシューティング](#トラブルシューティング)」を参照してください。外部決済モジュールとの連携で`SameSite=None`が必要な場合は、開発環境でもHTTPS対応を推奨します。

### app/config/eccube/packages/doctrine.yaml

```yaml
doctrine:
    orm:
        # Doctrine ORM 3.0での必須設定
        controller_resolver:
            auto_mapping: false  # 追加
```

### app/config/eccube/packages/test/doctrine.yaml

```yaml
doctrine:
    dbal:
        use_savepoints: true  # DAMA Doctrine Test Bundle 8.0で必須
```

### app/config/eccube/packages/security.yaml

```yaml
security:
    # enable_authenticator_manager は削除（Symfony 6.2+でデフォルト有効）
    # enable_authenticator_manager: true
```

### phpunit.xml.dist

PHPUnit 11で設定ファイルの形式が変わりました。

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
    backupGlobals="false"
    colors="true"
    bootstrap="tests/bootstrap.php"
    failOnDeprecation="true"
    displayDetailsOnTestsThatTriggerDeprecations="true"
>
    <!-- ... -->
    <!-- convertDeprecationsToExceptions属性は削除 -->

    <!-- 非推奨 API の検出範囲。自コードが直接呼ぶもの (direct) だけを対象にし、
         self (自コードの @deprecated 予告) と indirect (vendor 内部) は除外する -->
    <source ignoreSelfDeprecations="true"
            ignoreIndirectDeprecations="true">
        <include>
            <directory suffix=".php">src</directory>
        </include>
    </source>

    <!-- listeners から extensions に変更 -->
    <extensions>
        <bootstrap class="DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension"/>
    </extensions>
</phpunit>
```

- `convertDeprecationsToExceptions`属性を削除（残っていると`Unknown or risky test attribute`エラーになります）
- `<listeners>` → `<extensions>`、`PHPUnitListener` → `PHPUnitExtension`に変更
- `failOnDeprecation="true"`を追加（非推奨 API を呼ぶとテストが失敗します）
- `SYMFONY_DEPRECATIONS_HELPER`は PHPUnit 10 以降では読まれないため削除（`symfony/phpunit-bridge`の`bootstrap.php`が PHPUnit 10 以上で早期 return します）
- データプロバイダーメソッドは`public static`にする（`Data Provider method must be static`エラーになります）

---

## PHP 8.2+対応

EC-CUBE 4.4では、PHPのシステム要件は8.2〜8.5になります。PHPDocの`@param` / `@return`で型を示していたコードは、引数・戻り値・プロパティに型宣言を付けてください。型宣言と重複するPHPDocはRectorが自動的に削除します。

```php
// 4.3までの書き方（PHPDocアノテーション）
/**
 * @param string|null $value
 * @return string|null
 */
public function process($value)
{
    return $value;
}

// 4.4での書き方（型宣言）
public function process(?string $value): ?string
{
    return $value;
}
```

型を厳格にすると、未初期化の型付きプロパティ（`Typed property ... must not be accessed before initialization`）や、`string`型の引数に`null`を渡す`TypeError`が表面化します。nullを取りうる値は`?string $value = null`のようにnull許容型として宣言してください。

PHP 8.5では`array_key_exists()`のキー引数に`null`を渡すと`TypeError`になります。`[null => 1]`のようなキーは空文字列`''`に置き換えてください。

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

Symfony 7.4では、ルーティングのAnnotations形式はサポートされません。`@Route`アノテーションを`#[Route]`アトリビュートへ移行してください。

```php
// 4.3までの書き方（Annotations）
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

    /**
     * @Route("/%eccube_admin_route%/order", name="admin_order", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/order/page/{page_no}", name="admin_order_page", requirements={"page_no": "\d+"}, methods={"GET", "POST"})
     * @Template("@admin/Order/index.twig")
     */
    public function index(Request $request, PaginatorInterface $paginator, ?int $page_no = null): array

// 4.4での書き方（Attributes）
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Twig\Attribute\Template;

    #[Route(path: '/%eccube_admin_route%/order', name: 'admin_order', methods: ['GET', 'POST'])]
    #[Route(path: '/%eccube_admin_route%/order/page/{page_no}', name: 'admin_order_page', requirements: ['page_no' => '\d+'], methods: ['GET', 'POST'])]
    #[Template(template: '@admin/Order/index.twig')]
    public function index(Request $request, PaginatorInterface $paginator, ?int $page_no = null): array
```

### トランザクション管理の明示化

Symfony 7およびDoctrine ORM 3.0では、`EntityManager::lock()`を使用する場合に明示的にトランザクションを開始します。

```php
// 4.4での書き方
if (!$this->entityManager->getConnection()->isTransactionActive()) {
    $this->entityManager->getConnection()->beginTransaction();
}

try {
    $this->entityManager->lock($entity, LockMode::PESSIMISTIC_WRITE);
    // ...
    $this->entityManager->getConnection()->commit();
} catch (\Exception $e) {
    $this->entityManager->getConnection()->rollBack();
    throw $e;
}
```

---

## Doctrine ORM 3.0対応

### Annotations → Attributes への移行

Doctrine ORM 3.0ではAnnotationsはサポートされません。すべてのEntityをPHP 8 Attributesに移行してください。型指定には`Doctrine\DBAL\Types\Types`の定数を使用し、プロパティには型宣言を付けます。

```php
// 4.3までの書き方（Annotations）
/**
 * @ORM\Table(name="dtb_sample")
 * @ORM\Entity(repositoryClass="SampleRepository")
 */
class Sample
{
    /**
     * @var int|null
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
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

型定数には`Types::INTEGER`、`Types::STRING`のほか`Types::DATETIMETZ_MUTABLE`、`Types::BOOLEAN`などがあります。

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

### EntityManagerの変更

```php
// detach() は削除。clear() を使う
$this->entityManager->detach($entity);         // 4.3
$this->entityManager->clear();                 // 4.4

// clear() は引数を取らない
$this->entityManager->clear(Customer::class);  // 4.3
$this->entityManager->clear();                 // 4.4

// flush() にエンティティは渡せない
$this->entityManager->flush($entity);          // 4.3
$this->entityManager->flush();                 // 4.4

// Configuration::setSQLLogger() は削除。CSV 出力などで SQL ログを無効化していたコードは呼び出しごと削除する
// （ORM 3 は既定で SQL ログをメモリに蓄積しない）
$this->entityManager->getConfiguration()->setSQLLogger(null);  // 4.3（削除する）

// doctrine/common は 4.4 の依存に含まれないため Doctrine\Common\Util\ClassUtils は使えない
// プロキシから実エンティティのクラス名を得るには ClassMetadata を使う
$entityName = ClassUtils::getClass($entity);                                       // 4.3
$entityName = $this->entityManager->getClassMetadata($entity::class)->getName();  // 4.4
```

### ClassMetadataの変更

`ClassMetadata::$reflFields`プロパティへの直接アクセスは削除されました。

```php
// 4.3までの書き方
$reflProperty = $metadata->reflFields['fieldName'];

// 4.4での書き方
$reflProperty = $metadata->getReflectionProperty('fieldName');
```

プロキシの判定に使う`Doctrine\ORM\Proxy\Proxy`は`Doctrine\Persistence\Proxy`に変わりました。

### Doctrine EventSubscriber → Listenerへの変換

カスタマイズで独自にDoctrine EventSubscriberを実装している場合は、Listenerへ移行してください。

```php
// 4.3までの書き方（EventSubscriber）
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;

class MyEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::postLoad];
    }

    public function prePersist(LifecycleEventArgs $args): void { /* ... */ }
    public function postLoad(LifecycleEventArgs $args): void { /* ... */ }
}

// 4.4での書き方（Listener）
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::postLoad)]
class MyEventListener
{
    public function prePersist(LifecycleEventArgs $args): void { /* ... */ }
    public function postLoad(LifecycleEventArgs $args): void { /* ... */ }
}
```

- `EventSubscriber`インターフェースと`getSubscribedEvents()`を削除し、`#[AsDoctrineListener]`属性をイベントごとに追加
- `Doctrine\ORM\Event\*` → `Doctrine\Persistence\Event\*`へ名前空間変更

### Doctrine DBAL 4対応

DBAL が 3.x から 4.x になり、以下のメソッドが削除されました。

```php
// 4.3までの書き方
$platformName = $connection->getDatabasePlatform()->getName();
switch ($platformName) {
    case 'postgresql':
        // ...
}
$schemaManager = $connection->getSchemaManager();

// 4.4での書き方
use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SQLitePlatform;

$platform = $connection->getDatabasePlatform();
match (true) {
    $platform instanceof PostgreSQLPlatform => /* ... */,
    $platform instanceof AbstractMySQLPlatform => /* ... */,
    $platform instanceof SQLitePlatform => /* ... */,
};
$schemaManager = $connection->createSchemaManager();
```

---

## 在庫数の持ち方の変更

4.4 では在庫数の正典が`ProductStock::stock`（`dtb_product_stock.stock`）に一本化され、`dtb_product_class.stock`列は削除されました。4.3 までは両方のテーブルに在庫数が二重に保持されていました。

| 項目 | 4.3 | 4.4 |
|------|-----|-----|
| 在庫数の保存先 | `dtb_product_class.stock` と `dtb_product_stock.stock` | `dtb_product_stock.stock` のみ |
| `ProductClass::getStock()` / `setStock()` | 自身の列を読み書き | `ProductStock` へ委譲（引き続き使用可） |
| 在庫の有無 | `stock` と `stock_unlimited` から計算 | `dtb_product_class.in_stock`（flush 時に自動再計算） |
| `ProductRepository::COLUMNS['stock']` | `pc.stock` | `ps.stock` |

### DQL / SQL の修正

`pc.stock`を参照する DQL は`QueryException`になります。`ProductStock`を join して参照してください。

```php
// 4.3までの書き方
$qb->andWhere('pc.stock > 0');

// 4.4での書き方
$qb->leftJoin('pc.ProductStock', 'ps')
   ->andWhere('ps.stock > 0');

// 在庫の有無だけを判定する場合は in_stock を使う
$qb->andWhere('pc.in_stock = true');
```

### ProductStockを差し替えているコードの修正

`new ProductStock()`で作成して`setProductStock()`で差し替えると、委譲先が入れ替わり入力した在庫数が失われます。`ProductClass::setStock()`を使ってください（`ProductStock`が無ければ自動で作成されます）。

```php
// 4.3までの書き方
$ProductStock = new ProductStock();
$ProductStock->setProductClass($ProductClass);
$ProductStock->setStock($stock);
$ProductClass->setProductStock($ProductStock);

// 4.4での書き方
$ProductClass->setStock($stock);
```

### マイグレーションの実行

`doctrine:schema:update`では旧列は削除されません（`LegacyProductClassStockColumnSubscriber`が削除を抑止します）。4.3 からのアップデート後にマイグレーション`Version20260924000000`を実行してください。旧列から`dtb_product_stock`を補完したうえで旧列を削除し、`in_stock`を再計算します。マイグレーションを実行するまで`in_stock`はすべて false のため、在庫なしとして扱われます。

```bash
bin/console doctrine:migrations:migrate
```

---

## Monolog Processorの変更

Monolog 3.0では、Processorの引数と戻り値が`array`から`LogRecord`に変わりました。カスタマイズで独自にProcessorを実装している場合はシグネチャを変更してください（EC-CUBE標準のProcessorは本体で対応済みです）。

```php
// 4.3までの書き方（Monolog 2.x）
public function __invoke(array $record): array
{
    $record['extra']['custom'] = 'value';
    return $record;
}

// 4.4での書き方（Monolog 3.x）
use Monolog\LogRecord;

public function __invoke(LogRecord $record): LogRecord
{
    $record->extra['custom'] = 'value';
    return $record;
}
```

`symfony/monolog-bundle`も 4.x になり、`excluded_404s`、`console_formater_options`の設定と`elasticsearch`、`mongo`、`sentry`、`raven`のハンドラ type が削除されました。プラグインで`monolog.yaml`を追加している場合は確認してください。

---

## BCMath対応

EC-CUBE 4.4 本体は`nanasess/bcmath-polyfill`（^1.2）に依存しており、BCMath拡張がない環境でも動作します（インストーラでは`bcmath`は推奨モジュールです）。このパッケージはBCMath拡張の代替実装に加えて、PHP 8.4 で追加された丸め関数`bcfloor`、`bcceil`、`bcround`を PHP 8.2 / 8.3 でも使えるようにします。プラグイン単体で依存を宣言する場合は以下を追加します。

```bash
composer require nanasess/bcmath-polyfill:^1.2
```

金額計算には float ではなく BCMath 関数（`bcadd`、`bcsub`、`bcmul`、`bcdiv`、`bccomp`）の使用を推奨します。引数と戻り値は文字列で、最後の引数`$scale`で小数点以下の桁数を指定します（通常、金額は2桁）。`bcfloor`と`bcceil`は引数が1つのみで整数部分を返すため、小数点以下の桁数を指定して切り捨て/切り上げする場合は10の累乗を掛けてから処理します。

```php
$tax = bcdiv(bcmul($price, $taxRate, 4), '100', 2);  // price * taxRate / 100
$total = bcadd($total, bcmul((string)$price, (string)$quantity, 2), 2);
$round = bcround('123.456', 2);  // '123.46'
$floor = bcfloor('123.456');     // '123'
```

---

## 削除されたクラスへの対応

### SameSiteNoneCompatSessionHandler

PHP 7.2対応のレガシーセッションハンドラーが削除されました。`framework.yaml`の`handler_id`を標準のハンドラーに戻します。

```yaml
# 4.3までの書き方
framework:
    session:
        handler_id: Eccube\Session\Storage\Handler\SameSiteNoneCompatSessionHandler

# 4.4での書き方
framework:
    session:
        handler_id: session.handler.native_file
        cookie_secure: auto
        cookie_samesite: none
```

### AnnotationReaderFacade

アノテーションリーダーは不要になりました。属性はPHPのリフレクションAPIで直接読み取れます。

```php
// 4.3までの書き方
use Eccube\DependencyInjection\Facade\AnnotationReaderFacade;

$reader = AnnotationReaderFacade::create();
foreach ($reader->getClassAnnotations($reflectionClass) as $annotation) {
    if ($annotation instanceof EntityExtension) {
        // ...
    }
}

// 4.4での書き方
foreach ($reflectionClass->getAttributes(\Eccube\Attribute\EntityExtension::class) as $attribute) {
    $instance = $attribute->newInstance();
    // ...
}
```

---

## 削除された public API・イベント定数

### 非推奨public APIの削除

4.3 で`@deprecated`だった以下の API は削除されました。

| 削除された API | 代替 |
|---------------|------|
| `OrderItem::getTaxRuleId()` / `setTaxRuleId()` | なし（`tax_rule_id`列は残る） |
| `Product::isEnable()` / `ProductClass::isEnable()` | なし |
| `Cart::getLock()` / `setLock()` | なし |
| `transChoice()` | `trans()` |
| `Order::getTotalPrice()` | `Order::getPaymentTotal()` |
| `CacheUtil::clear()` | `CacheUtil::clearCache()` |
| `CustomerStatus::NONACTIVE` / `ACTIVE` | `CustomerStatus::PROVISIONAL` / `REGULAR` |

`Order::getTax()` / `setTax()` / `getDiscount()`と`BaseInfo::getPhpPath()` / `setPhpPath()`は`@deprecated`のまま残っています。

### 未発火のイベント定数の削除

`EccubeEvents`から、dispatch されていなかった 52 個の定数が削除されました。これらを購読していても元々呼び出されていなかったため実挙動は変わりませんが、定数名を参照しているコードは`undefined constant`エラーになります。

```
ADMIN_ADMIM_INDEX_INITIALIZE
ADMIN_CONTENT_LAYOUT_INDEX_INITIALIZE
ADMIN_CONTENT_LAYOUT_INDEX_COMPLETE
ADMIN_ORDER_MAIL_INDEX_CONFIRM
ADMIN_ORDER_MAIL_MAIL_ALL_INITIALIZE
ADMIN_ORDER_MAIL_MAIL_ALL_CHANGE
ADMIN_ORDER_MAIL_MAIL_ALL_CONFIRM
ADMIN_ORDER_MAIL_MAIL_ALL_COMPLETE
ADMIN_ORDER_DELETE_COMPLETE
ADMIN_ORDER_CSV_EXPORT_SHIPPING
ADMIN_SHIPPING_INDEX_INITIALIZE
ADMIN_SHIPPING_INDEX_SEARCH
ADMIN_PRODUCT_PRODUCT_CLASS_INDEX_INITIALIZE
ADMIN_PRODUCT_PRODUCT_CLASS_INDEX_CLASSES
ADMIN_PRODUCT_PRODUCT_CLASS_EDIT_INITIALIZE
ADMIN_PRODUCT_PRODUCT_CLASS_EDIT_COMPLETE
ADMIN_PRODUCT_PRODUCT_CLASS_EDIT_UPDATE
ADMIN_PRODUCT_PRODUCT_CLASS_EDIT_DELETE
ADMIN_PRODUCT_DISPLAY_COMPLETE
ADMIN_SETTING_SHOP_TAX_RULE_EDIT_PARAMETER_INITIALIZE
ADMIN_SETTING_SHOP_TAX_RULE_EDIT_PARAMETER_COMPLETE
ADMIN_SETTING_SYSTEM_MEMBER_DELETE_INITIALIZE
FRONT_MYPAGE_MYPAGE_DELETE_INITIALIZE
FRONT_CART_INDEX_INITIALIZE
FRONT_CART_INDEX_COMPLETE
FRONT_CART_ADD_INITIALIZE
FRONT_CART_ADD_COMPLETE
FRONT_CART_ADD_EXCEPTION
FRONT_CART_UP_INITIALIZE
FRONT_CART_UP_COMPLETE
FRONT_CART_UP_EXCEPTION
FRONT_CART_DOWN_INITIALIZE
FRONT_CART_DOWN_COMPLETE
FRONT_CART_DOWN_EXCEPTION
FRONT_CART_REMOVE_INITIALIZE
FRONT_CART_REMOVE_COMPLETE
FRONT_PRODUCT_INDEX_COMPLETE
FRONT_PRODUCT_INDEX_DISP
FRONT_PRODUCT_INDEX_ORDER
FRONT_PRODUCT_DETAIL_FAVORITE
FRONT_PRODUCT_DETAIL_COMPLETE
FRONT_SHOPPING_INDEX_INITIALIZE
FRONT_SHOPPING_CONFIRM_INITIALIZE
FRONT_SHOPPING_CONFIRM_PROCESSING
FRONT_SHOPPING_CONFIRM_COMPLETE
FRONT_SHOPPING_DELIVERY_INITIALIZE
FRONT_SHOPPING_DELIVERY_COMPLETE
FRONT_SHOPPING_PAYMENT_INITIALIZE
FRONT_SHOPPING_PAYMENT_COMPLETE
FRONT_SHOPPING_SHIPPING_CHANGE_INITIALIZE
FRONT_SHOPPING_SHIPPING_EDIT_CHANGE_INITIALIZE
FRONT_SHOPPING_SHIPPING_MULTIPLE_CHANGE_INITIALIZE
```

---

## その他の仕様変更

### Rectorによる自動修正

`rector.php`を使用して、ここまでの修正の多くを一括適用できます。

```bash
vendor/bin/rector process --dry-run  # 変更内容の確認
vendor/bin/rector process            # 修正の適用
```

本体の`rector.php`（Rector 2.x）で適用される主なルールセットです。

- `LevelSetList::UP_TO_PHP_84`
- `SymfonySetList::COMPOSER_BASED`、`SYMFONY_CODE_QUALITY`、`SYMFONY_CONSTRUCTOR_INJECTION`
- `DoctrineSetList::COMPOSER_BASED`、`DOCTRINE_CODE_QUALITY`、`ANNOTATIONS_TO_ATTRIBUTES`
- `PHPUnitSetList::COMPOSER_BASED`、`PHPUNIT_CODE_QUALITY`
- `SetList::DEAD_CODE`

`SYMFONY_74`のようなバージョン別のセット定数は Rector 2.6.2 で撤去されました。`COMPOSER_BASED`は`composer.json`のインストール済みバージョンに合うルールだけを実行します。プラグインに適用する場合は`withPaths()`に`app/Plugin/<PluginCode>`を追加してください。

#### Rector適用後のDIバインディングの確認

`ClassPropertyAssignToConstructorPromotionRector`はコンストラクタプロモーションを適用する際、パラメータ名を元のプロパティ名に変更します。`services.yaml`の`bind:`に`$shoppingPurchaseFlow: '@eccube.purchase.flow.shopping'`のようにパラメータ名で束縛しているサービスは、名前が変わるとバインディングが一致せず、正しいPurchaseFlowが注入されません。購入フロー関連のテストが失敗する、`OrderUpdateProcessor`が実行されずOrderStatusが更新されない、といった症状になります。

```php
// Rector適用前
public function __construct(PurchaseFlow $shoppingPurchaseFlow)
{
    $this->purchaseFlow = $shoppingPurchaseFlow;
}

// Rector適用後（パラメータ名が $purchaseFlow に変わり、bind: と一致しない）
public function __construct(private readonly PurchaseFlow $purchaseFlow)
{
}

// 修正後（パラメータ名を bind: に合わせる）
public function __construct(private readonly PurchaseFlow $shoppingPurchaseFlow)
{
}
```

`$shoppingPurchaseFlow`、`$cartPurchaseFlow`、`$orderPurchaseFlow`、`$_orderStateMachine`など、`bind:`で定義されているパラメータ名を持つクラスはRector適用後に確認してください。

```bash
grep -A 10 "bind:" app/config/eccube/services.yaml
git diff --name-only | xargs grep "private readonly"
```

### Symfonyバリデーションメッセージの翻訳変更

Symfony 7.4では、Validatorコンポーネントの日本語翻訳メッセージが変わりました。テストで期待値として使用しているメッセージを更新してください。

| 制約 | 旧メッセージ | 新メッセージ |
|------|------------|------------|
| Length（max） | `この値は、{{ limit }}文字以内で入力してください。` | `長すぎます。この値は{{ limit }}文字以下で入力してください。` |
| Length（min） | `この値は、{{ limit }}文字以上で入力してください。` | `短すぎます。この値は{{ limit }}文字以上で入力してください。` |
| NotBlank | `入力してください。` | `この値は空にできません。` |
| NotNull | `入力してください。` | `値を入力してください。` |
| Email | `無効なメールアドレスです。` | `有効なメールアドレスではありません。` |
| Url | `無効なURLです。` | `有効なURLではありません。` |
| Type | `{{ type }}型でなければなりません。` | `この値は{{ type }}型でなければなりません。` |

従来のメッセージを維持したい場合は、`src/Eccube/Resource/locale/validators.ja.yaml`でSymfonyの翻訳を上書きできます。

```yaml
# Symfony 7.4の新しいメッセージを従来の形式に戻す
This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.: この値は、{{ limit }}文字以内で入力してください。
This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.: この値は、{{ limit }}文字以上で入力してください。
This value is not a valid email address.: 無効なメールアドレスです。
This value is not a valid URL.: 無効なURLです。
```

### パスワードポリシーの変更

パスワード要件が NIST SP 800-63B-4 に沿って変更されました。テストで会員や管理者を作成しているプラグインは、固定のパスワード文字列を 15 文字以上に見直してください。

| 設定 | 4.3 | 4.4 |
|------|-----|-----|
| `eccube_password_min_len` | 12 | 15 |
| `eccube_password_max_len` | 50 | 128 |
| `eccube_password_pattern` | 英字と数字を各 1 種類以上 | 制御文字以外の全 Unicode 文字を許可 |
| `eccube_password_compromised_check` | なし | `true`（漏洩パスワードチェック。閉域網では`false`にする） |
| `eccube_default_password` | `abc********123` | `abc*********123`（15 文字） |

### キャッシュディレクトリの分離

`var/cache`に集約されていた生成物が、用途ごとに 3 つのディレクトリに分かれました。キャッシュ削除のスクリプトや、`%kernel.cache_dir%`配下に一時ファイルを置いているプラグインは確認してください。

| ディレクトリ | 用途 |
|-------------|------|
| `var/cache/<env>` | `kernel.cache_dir` |
| `var/build/<env>` | `kernel.build_dir`。コンパイル済みコンテナ・ルーティング・メタデータ・prod の twig。`bin/console eccube:cache:build`が生成 |
| `var/runtime/<env>` | リクエスト処理中に Web サーバーが書き込むもの（cache pool、CSV 一時ファイル等）。`%eccube_runtime_dir%` |

設定ファイルを変更した後は`var/cache`と`var/build`の両方を削除してください。

### 依存パッケージのバージョン

| パッケージ | 4.3 | 4.4 |
|-----------|-----|-----|
| PHP | 8.1〜8.3 | 8.2〜8.5 |
| Symfony | 6.4.x | 7.4.x |
| Doctrine ORM | 2.x | 3.x |
| Doctrine DBAL | 3.x | 4.x |
| Doctrine Persistence | 3.x | 4.x |
| Twig | 3.8 | 3.21+ |
| Monolog | 2.x | 3.x |
| symfony/monolog-bundle | 3.x | 4.x |
| psr/log | 2.x | 3.x |
| mobiledetect/mobiledetectlib | 2.x | 4.x |
| nanasess/bcmath-polyfill | 1.0+ | 1.2+ |
| PHPUnit | 9.x | 11.x |
| symfony/phpunit-bridge | 7.x | 8.x |
| dama/doctrine-test-bundle | 6.x | 8.x |
| Rector | 1.x | 2.x |
| PHPStan | 1.x | 2.x |
| php-cs-fixer | 3.x | 3.75+ |

4.4 で依存から外れたパッケージです。プラグインが直接参照している場合は置き換えてください。

| パッケージ | 代替 |
|-----------|------|
| `doctrine/annotations` | PHP 8 Attributes |
| `doctrine/common` | `ClassUtils`は`EntityManager::getClassMetadata()`で代替 |
| `sensio/framework-extra-bundle` | `#[Template]`、`#[IsGranted]`等の Symfony 本体の属性 |
| `codeception/codeception` | E2E テストは Playwright（`e2e/`）へ移行 |

---

## プラグイン開発者向け移行ガイド

`Eccube\Annotation\*`は`Eccube\Attribute\*`に置き換わりました（`Class "Eccube\Annotation\EntityExtension" not found`になります）。各例では4.3から変わる行だけを示します。

### 1. EntityExtensionの移行

```php
// 4.3までの書き方
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
}

// 4.4での書き方
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
}
```

### 2. PurchaseFlowプロセッサーの移行

```php
// 4.3までの書き方
use Eccube\Annotation\CartFlow;
use Eccube\Annotation\OrderFlow;
use Eccube\Annotation\ShoppingFlow;

/**
 * @CartFlow()
 * @ShoppingFlow()
 * @OrderFlow()
 */
class YourValidator extends ItemValidator
{
    protected function validate(ItemInterface $item, PurchaseContext $context)
    {
        // ...
    }
}

// 4.4での書き方
use Eccube\Attribute\CartFlow;
use Eccube\Attribute\OrderFlow;
use Eccube\Attribute\ShoppingFlow;

#[CartFlow]
#[ShoppingFlow]
#[OrderFlow]
class YourValidator extends ItemValidator
{
    protected function validate(ItemInterface $item, PurchaseContext $context): void
    {
        // ...
    }
}
```

### 3. コントローラーの移行

```php
// 4.3までの書き方
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/your_plugin/index", name="your_plugin_index")
     * @Template("YourPlugin/index.twig")
     */
    public function index(Request $request)

// 4.4での書き方
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\Routing\Attribute\Route;

    #[Route('/your_plugin/index', name: 'your_plugin_index')]
    #[Template('YourPlugin/index.twig')]
    public function index(Request $request): array
```

### 4. FormExtensionの移行

```php
// 4.3までの書き方
use Eccube\Annotation\FormAppend;

/**
 * @FormAppend()
 */
class EntryTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ...
    }

    public function getExtendedType()
    {
        return \Eccube\Form\Type\Front\EntryType::class;
    }
}

// 4.4での書き方
use Eccube\Attribute\FormAppend;

#[FormAppend]
class EntryTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // ...
    }

    public static function getExtendedTypes(): iterable
    {
        return [\Eccube\Form\Type\Front\EntryType::class];
    }
}
```

### 5. PluginManagerの移行

`AbstractPluginManager`の`install()`、`update()`、`enable()`、`disable()`、`uninstall()`、`migration()`は`: void`戻り値型を持ちます。オーバーライドしているメソッドに`: void`を付けないと`Declaration of ... must be compatible with Eccube\Plugin\AbstractPluginManager::install(): void`エラーになります。

```php
// 4.3までの書き方
public function install(array $meta, ContainerInterface $container)

// 4.4での書き方
public function install(array $meta, ContainerInterface $container): void
```

### 6. Symfony 7で削除されたAPIの置き換え

公式プラグインの 4.4 対応で実際に必要になった置き換えです。

| 4.3までの書き方 | 4.4での書き方 |
|----------------|--------------|
| `UserProviderInterface::loadUserByUsername()` | `loadUserByIdentifier()` |
| Command の `protected static $defaultName = '...'` | `#[AsCommand(name: '...')]`（`Symfony\Component\Console\Attribute\AsCommand`） |
| `@required`（PHPDoc） | `#[Required]`（`Symfony\Contracts\Service\Attribute\Required`） |
| `@IsGranted(...)`（Sensio） | `#[IsGranted(...)]`（`Symfony\Component\Security\Http\Attribute\IsGranted`） |
| `@Template(...)`（Sensio） | `#[Template(...)]`（`Symfony\Bridge\Twig\Attribute\Template`） |
| `security.enable_authenticator_manager: true` | 設定を削除 |
| `AssociationMapping`を配列として`$mapping['type']`で判定 | `$mapping->isToMany()`等のメソッドで判定 |

---

## トラブルシューティング

### HTTP環境でログインできない

Symfony 6.4では`http://localhost:8080`でログインできたのに、Symfony 7ではセッションCookieが保存されず失敗する場合は、「[設定ファイル（YAML）の更新](#設定ファイルyamlの更新)」で説明したセッションCookie設定が原因です。開発環境でHTTPSリバースプロキシを使用するか、開発環境のみ一時的に以下の設定を使用してください。

```yaml
# app/config/eccube/packages/dev/framework.yaml
framework:
    session:
        cookie_secure: false
        cookie_samesite: lax
```

インストール後に変更した場合は、`var/cache`と`var/build`を削除してください。

外部決済モジュールをテストする場合はHTTPS環境を使用してください。`SameSite=Lax`では外部サイトからのPOSTコールバックでセッションが維持されません。

---

## 移行チェックリスト

- [ ] PHP 8.2以降を使用し、`composer.json`の依存関係を4.4対応版に更新した
- [ ] `.env`に`ECCUBE_AUTH_MAGIC`を設定した
- [ ] framework.yaml / doctrine.yaml / test/doctrine.yaml / security.yaml / phpunit.xml.dist を更新した
- [ ] Rectorを適用し、コンストラクタプロモーション後のパラメータ名が`services.yaml`の`bind:`と一致することを確認した
- [ ] `Eccube\Annotation\*`、`@Route`、`@Template`、`@ORM\*`をすべてAttributesに移行し、`indexes` / `uniqueConstraints`を分離した
- [ ] EntityManager / ClassMetadata / DBAL の削除APIと`Doctrine\ORM\Proxy\Proxy`を置き換え、EventSubscriberをListenerに移行した
- [ ] `pc.stock`の参照と`new ProductStock()`による差し替えを修正し、アップデート後に`doctrine:migrations:migrate`を実行した
- [ ] `AbstractPluginManager`のメソッドに`: void`を付けた
- [ ] 削除されたクラス・非推奨 public API・`EccubeEvents`定数・Symfony 7で削除されたAPIを参照していない
- [ ] カスタムMonolog Processorを`LogRecord`に対応させた
- [ ] テストのデータプロバイダーを`static`にし、バリデーションメッセージの期待値と固定パスワード（15文字以上）を更新した
- [ ] プラグインのインストール/有効化/無効化/アンインストール、Entity拡張、Form拡張、ルーティング、PurchaseFlowプロセッサーが管理画面/フロント画面で動作する

---

## 参考資料

### 公式ドキュメント

- [Symfony 7.0 Upgrade Guide](https://symfony.com/doc/current/setup/upgrade_major.html)
- [Doctrine ORM 3.0 Upgrade Guide](https://github.com/doctrine/orm/blob/3.0.x/UPGRADE.md)
- [Doctrine DBAL 4.0 Upgrade Guide](https://github.com/doctrine/dbal/blob/4.0.x/UPGRADE.md)
- [PHPUnit 11.0 Release Announcement](https://phpunit.de/announcements/phpunit-11.html)
- [PHP 8 Attributes](https://www.php.net/manual/ja/language.attributes.php)
- [Monolog 3.0 Upgrade Guide](https://github.com/Seldaek/monolog/blob/main/UPGRADE.md)

### EC-CUBE関連

- [EC-CUBE 4系 プラグイン開発](https://doc4.ec-cube.net/plugin)
- [EC-CUBE GitHub リポジトリ](https://github.com/EC-CUBE/ec-cube)
- [EC-CUBE 4.4.0-alpha リリースノート](https://github.com/EC-CUBE/ec-cube/releases/tag/4.4.0-alpha-1)

### 4.4対応済みプラグインの実例

- [eccube-api4 #186](https://github.com/EC-CUBE/eccube-api4/pull/186)
- [ProductReview-plugin #95](https://github.com/EC-CUBE/ProductReview-plugin/pull/95)
- [mail-magazine-plugin #150](https://github.com/EC-CUBE/mail-magazine-plugin/pull/150)

---
