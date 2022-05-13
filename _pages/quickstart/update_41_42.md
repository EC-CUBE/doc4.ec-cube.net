---
layout: single
title: 4.1から4.2へのマイグレーション
keywords: howto update
tags: [quickstart, getting_started]
permalink: update-41-42
summary : EC-CUBE4.1から4.2へのマイグレーションについて記載します。
---

EC-CUBE4.1から4.2へのマイグレーションを解説します。

EC-CUBE本体および一部公式プラグインをEC-CUBE4.2対応し、コードの移植が必要な箇所をまとめたものです。

- [EC-CUBE 4.2 Roadmap](https://github.com/EC-CUBE/ec-cube/issues/5356){:target="_blank"}
- [Symfony5 support](https://github.com/EC-CUBE/ec-cube/pull/5353){:target="_blank"}
- [メルマガプラグイン](https://github.com/EC-CUBE/mail-magazine-plugin/compare/4.n){:target="_blank"}

## プラグインコードの変更

4.2は、4.1からの互換性がないバージョンになります。

ソースコードを共存させることができないため、4.0/4.1対応版プラグインとは別プラグインとして実装する必要があります。

### composer.json

composer.jsonのプラグインコードを変更します。

以下はメルマガプラグインの修正例です。

```diff
{
-  "name": "ec-cube/mailmagazine4",
+  "name": "ec-cube/mailmagazine42",
  "version": "4.2.0",
  "description": "メールマガジンプラグイン",
  "type": "eccube-plugin",
  "require": {
    "ec-cube/plugin-installer": "~0.0.6 || ^2.0"
  },
  "extra": {
-    "code": "MailMagazine4"
+    "code": "MailMagazine42"
  }
}
```

### namespaceの変更

namespaceをプラグインコードにあわせ、変更します。

以下はメルマガプラグインの修正例です。

```diff
<?php
- namespace Plugin\MailMagazine4;
+ namespace Plugin\MailMagazine42;

use Eccube\Common\EccubeNav;

class MailMagazineNav implements EccubeNav
{

```

## Symfony5.4対応

Symfony5.4での変更をすべて網羅できているわけではないため、記載されていない問題があった場合は、SymfonyのUPGRADEドキュメントも合わせて参照してください。

- [UPGRADE-5.0.md](https://github.com/symfony/symfony/blob/5.4/UPGRADE-5.0.md){:target="_blank"}

### Form関連

#### FormExtension

getExtendedTypesの戻り値の型を定義する必要があります。

```diff
    /**
     * Return the class of the type being extended.
     */
-    public static function getExtendedTypes()
+    public static function getExtendedTypes(): iterable
    {
        return [EntryType::class];
    }
```

### Repository関連

#### ManagerRegistry

ManagerRegistryのnamespaceが変更されました。

`Doctrine\Persistence\ManagerRegistry`へ変更します。

```diff
<?php

namespace Plugin\MailMagazine4\Repository;

- use Doctrine\Common\Persistence\ManagerRegistry;
+ use Doctrine\Persistence\ManagerRegistry; 
use Eccube\Repository\AbstractRepository;
use Plugin\MailMagazine4\Entity\MailMagazineSendHistory;
use Eccube\Doctrine\Query\Queries;
```

### twig関連

#### forループでのifの利用

forループでのifは利用できなくなります。filterを使用してください。

```diff
- {% for f in searchForm if f.vars.eccube_form_options.auto_render %}
+ {% for f in searchForm|filter(f => f.vars.eccube_form_options.auto_render) %}
```

### SwiftMailer関連

#### SymfonyMailerへの移行

メール送信ライブラリがSwiftMailer から SymfonyMailer に変更されました。

SwiftMailerを直接使用している場合は、SymfonyMailerでの実装に変更してください。

```diff
-        $message = (new \Swift_Message())
-            ->setSubject('['.$this->BaseInfo->getShopName().'] '.$formData['mail_subject'])
-            ->setFrom([$this->BaseInfo->getEmail01() => $this->BaseInfo->getShopName()])
-            ->setTo([$Order->getEmail()])
-            ->setBcc($this->BaseInfo->getEmail01())
-            ->setReplyTo($this->BaseInfo->getEmail03())
-            ->setReturnPath($this->BaseInfo->getEmail04())
-            ->setBody($formData['tpl_data']);
+        $message = (new Email())
+            ->subject('['.$this->BaseInfo->getShopName().'] '.$formData['mail_subject'])
+            ->from(new Address($this->BaseInfo->getEmail01(), $this->BaseInfo->getShopName()))
+            ->to($Order->getEmail())
+            ->bcc($this->BaseInfo->getEmail01())
+            ->replyTo($this->BaseInfo->getEmail03())
+            ->returnPath($this->BaseInfo->getEmail04())
+            ->text($formData['tpl_data']);
```

こちらの差分も合わせて参考にしてください。

https://github.com/EC-CUBE/ec-cube/pull/5353/commits/ff6a6962736c87fe8e9b7427ba2cbebbb3000c43

### Event関連

#### EventDispatcherのシグネチャ変更

$eventDispatcher->dispatch()の引数の順序が逆になりました。

独自にフックポイントを定義している場合は、引数の順序を変更してください。

```diff

-        $this->eventDispatcher->dispatch(EccubeEvents::FRONT_ENTRY_INDEX_INITIALIZE, $event);
+        $this->eventDispatcher->dispatch($event, EccubeEvents::FRONT_ENTRY_INDEX_INITIALIZE);

```

### パラメータ関連

#### envパラメータ

envパラメータで、boolや数値が利用できなくなりました。

services.yamlで、envパラメータを利用している場合は、文字列で記述するように変更してください。

```diff
parameters:

-    env(ECCUBE_FORCE_SSL): false
+    env(ECCUBE_FORCE_SSL): '0'

-    env(ECCUBE_2FA_EXPIRE): 14
+    env(ECCUBE_2FA_EXPIRE): '14'
```

### テストコード

#### setUp/tearDown

setUp/tearDownメソッドは、戻り値の型を定義する必要があります。

```diff

-   setUp()
+   setUp(): void

-   tearDown()
+   tearDown(): void

```

### その他の仕様変更

### その他削除された関数・機能
