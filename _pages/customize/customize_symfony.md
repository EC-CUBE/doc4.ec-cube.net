---
title: Symfonyの機能を使ったカスタマイズ
description: イベントリスナーやコマンド、Bundle など Symfony の拡張機構を使ったカスタマイズ方法を説明します。
keywords: core カスタマイズ Symfony
tags: [core, symfony]
permalink: customize_symfony
folder: customize

---

## 概要

EC-CUBEは、SymfonyやDoctrineをベースに開発されています。
そのため、SymfonyやDoctrineが提供している拡張機構を利用することができます。

ここでは、代表的な拡張機構とその実装方法を紹介します。

## Symfony Event

Symfonyのイベントシステムを利用することができます。

### hello worldを表示するイベントリスナーを作成する

`app/Customize/EventListener`配下に`HelloListener.php`を作成します。

```php
<?php

namespace Customize\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class HelloListener implements EventSubscriberInterface
{
    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $response->setContent($response->getContent().'hello world');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onResponse',
        ];
    }
}
```

作成後、画面を表示(どのページでも表示されます)し、`hello world`が表示されていれば成功です。

表示されない場合は、`bin/console cache:clear --no-warmup`でキャッシュを削除してください。
また、`bin/console debug:event-dispatcher`で登録されているイベントリスナーを確認できます。

イベントに関する詳細は以下を参照してください。

- [The HttpKernel Component](https://symfony.com/doc/current/components/http_kernel.html){:target="_blank"}
- [Events and Event Listeners](https://symfony.com/doc/current/event_dispatcher.html){:target="_blank"}
- [Built-in Symfony Events](https://symfony.com/doc/current/reference/events.html){:target="_blank"}

## Command

`bin/console`から実行できるコンソールコマンドを作成することが出来ます。

### hello worldを表示するコマンドを作成する

`app/Customize/Command`配下に`HelloCommand.php`を作成します。

```php
<?php

namespace Customize\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

// コマンド名
#[AsCommand(name: 'acme:hello')]
class HelloCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // hello worldを表示
        $io->success('hello world');

        return Command::SUCCESS;
    }
}
```

- `#[AsCommand]`属性の`name`はコマンド名を表します。
- `execute()`は終了コードを返す必要があります。正常終了時は`Command::SUCCESS`を返します。
- `$io->success('hello world')`で、hello worldを表示します。

作成後、`bin/console`で実行することができます。

```bash

$ bin/console acme:hello

 [OK] hello world

```

※ コマンドが認識されない場合は、`bin/console cache:clear --no-warmup`でキャッシュを削除してください。

Commandに関する詳細は以下を参照してください。

- [Console Commands](https://symfony.com/doc/current/console.html){:target="_blank"}

## Doctrine Event

Doctrineのイベントシステムを利用することができます。

### ショップ名にようこそを付与するイベントリスナーを作成する

`app/Customize/Doctrine/EventSubscriber`配下に`HelloEventSubscriber.php`を作成します。

```php
<?php

namespace Customize\Doctrine\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Eccube\Entity\BaseInfo;

#[AsDoctrineListener(event: Events::postLoad)]
class HelloEventSubscriber
{
    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof BaseInfo) {
            $shopName = $entity->getShopName();
            if (!str_contains($shopName, 'ようこそ')) {
                $entity->setShopName('ようこそ '.$shopName.' へ');
            }
        }
    }
}
```

作成後、トップページを開き、`ようこそ [ショップ名] へ`が表示されていれば成功です。

表示されない場合は、`bin/console cache:clear --no-warmup`でキャッシュを削除してください。

### ログを出力するイベントリスナーを作成する

`app/Customize/Doctrine/EventSubscriber`配下に`LogEventSubscriber.php`を作成します。

```php
<?php

namespace Customize\Doctrine\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Eccube\Entity\BaseInfo;
use Psr\Log\LoggerInterface;

#[AsDoctrineListener(event: Events::postLoad)]
class LogEventSubscriber
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // BaseInfoがロードされたらログを出力
        if ($entity instanceof BaseInfo) {
            $this->logger->info('Shop info loaded: '.$entity->getShopName(), [
                'id' => $entity->getId(),
                'time' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
```

作成後、トップページを開きます。
その後、`/var/www/html/var/log/`のログファイルを確認すると、`Shop info loaded・・・`のログが出力されます。

表示されない場合は、`bin/console cache:clear --no-warmup`でキャッシュを削除してください。

イベントに関する詳細は以下を参照してください。

- [The Event System](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html){:target="_blank"}
- [Doctrine Event Listeners and Subscribers](https://symfony.com/doc/current/doctrine/event_listeners_subscribers.html){:target="_blank"}

※ [Doctrine Event Listeners and Subscribers](https://symfony.com/doc/current/doctrine/event_listeners_subscribers.html){:target="_blank"}では、`services.yaml`での設定方法が記載されていますが、EC-CUBEはDoctrineのイベントリスナーをコンテナへ自動登録します。そのため、`services.yaml`での設定は不要です。

## SymfonyのBundleを利用する

EC-CUBEへSymfony Bundle を直接導入するかどうかは、要件に応じて判断が必要です。
EC-CUBEのプラグインだけでは実現が難しい場合は、Symfony Bundle での利用を検討してください。

#### プラグインで Symfony Bundle を導入する場合
[Web API プラグイン](https://github.com/EC-CUBE/eccube-api4){:target="_blank"}の実装を参考にしてください。
- composer.json

`composer.json` に利用するBundleを記載します。
```json
{
  "name": "ec-cube/api42",
  "version": "4.3.2",
  "description": "Web API",
  "type": "eccube-plugin",
  "require": {
    "ec-cube/plugin-installer": "^2.0",
    "league/oauth2-server-bundle": "^0.5",
    "nyholm/psr7": "^1.2",
    "php-http/message-factory": "*",
    "webonyx/graphql-php": "^14.0"
  },
  "extra": {
    "code": "Api42",
    "entity-namespaces": ["League\\Bundle\\OAuth2ServerBundle\\Model"]
  }
}
```

- Bundle/ApiBundle.php

Bundleのファイルが必要です。
```php
namespace Plugin\Api42\Bundle;

use Plugin\Api42\DependencyInjection\ApiExtension;
use Plugin\Api42\DependencyInjection\Compiler\ApiCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ApiCompilerPass());
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ApiExtension();
    }
}
```

- Resource/config/bundles.php

bundles.phpを追加し、利用するBundleを登録してください。
```php
return [
    Plugin\Api42\Bundle\ApiBundle::class => ['all' => true],
    League\Bundle\OAuth2ServerBundle\LeagueOAuth2ServerBundle::class => ['all' => true]
];
```

- Controller/Admin/OAuthController.php

必要なBundleのクラスを利用し、実装してください。
```php
・・・
use League\Bundle\OAuth2ServerBundle\Manager\AccessTokenManagerInterface;
use League\Bundle\OAuth2ServerBundle\Manager\ClientFilter;
use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
・・・
```

#### CustomizeディレクトリからSymfony Bundleをインストール [#6141](https://github.com/EC-CUBE/ec-cube/pull/6141){:target="_blank"}

EC-CUBE 4.3 から、Customizeディレクトリ配下に Symfony Bundle を配置して利用できるようになりました。

```php
<?php

namespace Customize\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CustomizeBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        var_dump('hello world.');
    }
}

```

app/Customize/Resource/config/bundles.php
```php
<?php

return [
    \Customize\Bundle\CustomizeBundle::class => ['all' => true],
];
```