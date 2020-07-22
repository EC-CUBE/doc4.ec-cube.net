---
title: プラグインで推奨の命名規則
keywords: plugin naming conventions プラグイン 命名規約
tags: [plugin, naming, conventions]
permalink: plugin_naming_conventions

---

### プラグインコード

重複を避けるため, `[ベンダー名][プラグイン]`の形式を推奨
キャメルケースで記述する

```
AcmeCategoryContent
```

### テーブル名

※プラグイン固有のテーブルを作成する場合

`plg_[プラグインコードのスネークケース]_xxx`

```php
use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="plg_acme_category_content_config")
 * @ORM\Entity(repositoryClass="Plugin\AcmeCategoryContent\Repository\ConfigRepository")
 */
class Config
```

### テーブルのカラム名およびgetter/setter

※traitを使用してカラムを追加する場合

`[プラグインコードのスネークケース]_xxx`
getter/setterはキャメルケースで記述する

```php
use Eccube\Annotation\EntityExtension;
use Doctrine\ORM\Mapping as ORM;

/**
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * @ORM\Column(name="acme_category_content_xxx" type="integer", nullable=true)
     */
    private $acme_category_content_xxx;

    public function getAcmeCategoryContentXxx()
    {
    }

    public function setAcmeCategoryContentXxx($xxx)
    {
    }
}
```

### ルーティング

#### フロント画面

`[プラグインコードのスネークケース]_xxx`

```php
class PageController extends AbstractController
{
    /**
     * @Route("/acme_category_content/page", name="acme_category_content_page")
     * @Template("@AcmeCategoryContent/page.twig")
     */
    public function index(Request $request)
```

#### 管理画面

管理画面は先頭に `admin_` をつける

`admin_[プラグインコードのスネークケース]_xxx`

```php
class ConfigController extends AbstractController
{
    /**
     * @Route("/%eccube_admin_route%/acme_category_content/config", name="admin_acme_category_content_config")
     * @Template("@AcmeCategoryContent/admin/config.twig")
     */
    public function index(Request $request)
```

### URL

`/[プラグインコードのスネークケース]/xxx/xxx`

```php
class ConfigController extends AbstractController
{
    /**
     * @Route("/%eccube_admin_route%/acme_category_content/config", name="acme_category_content_admin_config")
     * @Template("@AcmeCategoryContent/admin/config.twig")
     */
    public function index(Request $request)
```

### FormExtensionで追加するフィールド

`[プラグインコードのスネークケース]_xxx`

```php
class XxxExtention extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('acme_category_content_name', TextType::class);

```

### htmlで使用するID, class

`[プラグインコードのスネークケース]_xxx`

```html
<div id="acme_category_content_info" class="card rounded border-0 mb-4">
```

### コマンド名

`[プラグインコードのスネークケース]:xxx:xxx`

```php
class XxxCommand extends Command
{
    protected static $defaultName = 'acme_category_content:xxx:yyy';
```

### 言語ファイル

`[プラグインコードのスネークケース].xxx.xxx`

```yaml
# nav
acme_category_content.admin.nav.category: カテゴリコンテンツ一覧

# flash messages
acme_category_content.admin.save.success: 登録しました。
acme_category_content.admin.save.failed: 登録に失敗しました。
```

プラグインコード以降の命名規則は[EC-CUBE本体の命名規則](https://github.com/EC-CUBE/ec-cube/pull/3593)に従う

### パラメータ

`[プラグインコードのスネークケース].xxx.xxx`

```yaml
parameters:
  acme_category_content.xxx: 1

services:
  acme_category_content.log.formatter.line:
      class: Monolog\Formatter\LineFormatter
      arguments: ...
```
