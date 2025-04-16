---
title: EC-CUBE4.0系(Composer v1)利用時の注意点
keywords: plugin EC-CUBE4.0 Composer1
tags: [plugin, eccube, composer]
permalink: plugin_eccube40

---

EC-CUBE4.0系で利用している Composer v1 のメタデータが 2025年8月1日以降廃止されるというアナウンスがありました。

[https://blog.packagist.com/shutting-down-packagist-org-support-for-composer-1-x/](https://blog.packagist.com/shutting-down-packagist-org-support-for-composer-1-x/){:target="_blank"}

EC-CUBE4.0系はプラグインの管理に Composer v1 を利用しており、プラグインインストール/有効化/無効化/削除/アップデートに影響があります。

廃止されるのは、 Composer v1 のメタデータ(パッケージのバージョン情報や、ダウンロードURLなど)へのアクセスのみですので、通常は依存関係を更新しようとしないかぎりは影響ありません。

composer.lock ファイルにダウンロードURLは記録されていますので、composer install によるパッケージのインストールは従来どおり可能です。

しかしながらEC-CUBE4.0系は歴史的な経緯もあり、プラグインインストール/有効化/無効化/削除/アップデートをした際に、本来不要な Composer v1 メタデータへのアクセスが必要になる場合があります。

## 対応方法

### composer.json の修正

1. require-dev セクションを削除します。
   以下のような require-dev セクションを削除しておきます。このセクションは発時にのみ利用するため、削除しても問題ありません。

   ``` json

    "require-dev": {
        "bheller/images-generator": "^1.0",
        "captbaritone/mailcatcher-codeception-module": "^1.2",
        "codeception/codeception": "~2.4.5",
        "dama/doctrine-test-bundle": "^5.0",
        "fzaninotto/faker": "^1.7",
        "mikey179/vfsstream": "^1.6",
        "phpstan/phpstan": "^0.12",
        "phpunit/phpunit": "^6.5",
        "symfony/browser-kit": "^3.4",
        "symfony/phpunit-bridge": "^3.4"
    },
    ```

2. repositories セクションに // ここから // ここまで の設定を追加します。
   eccube-plugin-installer の参照先を GitHub に設定します。また予期せぬエラーを防ぐために packagist.org へのアクセスを無効化します。
   
   // ここから // ここまで は追加時には削除してください。構文エラーになってしまいます

   ``` json
    "repositories": {
        "eccube": {
            "type": "composer",
            "url": "https://package-api.ec-cube.net",
            "options": {
                "http": {
                    "header": ["X-ECCUBE-KEY: <認証キーの文字列>"]
                }
            }
        }
        // ここから
        ,
        "packagist.org": false,
        "eccube/plugin-installer": {
            "type": "vcs",
            "url": "https://github.com/EC-CUBE/eccube-plugin-installer",
            "no-api": true
        }
        // ここまで
    }
   ```

### composer install コマンドの実行

次にssh を利用して composer install --no-scripts --no-plugins コマンドを実行しておきます。vendor 以下の整合性を維持するために必要です。—no-dev オプションを付与せず、require-dev のパッケージもインストールしておくことがポイントです。
(composer.json の require-dev は削除済みですが、composer.lock の情報を参照して依存関係を更新しようとする場合があるため)

```
composer install --no-scripts --no-plugins
```

composer.json の修正 とcomposer install コマンドの実行が完了すれば、依存関係の更新を伴わないプラグインのインストール/有効化/無効化/削除/アップデートができるようになります

## 注意事項

### 他のパッケージと依存関係のあるプラグインの場合

[Web APIプラグイン](https://www.ec-cube.net/products/detail.php?product_id=2121)など、他のパッケージと依存関係のあるプラグインの場合は、Composer v1 メタデータアクセス廃止の影響を受けてしまいます。

他のパッケージと依存関係のある場合でも、 composer.json に依存先のGitHub リポジトリを記載することで対応可能です。

以下のように、app/Plugin/<プラグインコード>/composer.json の require セクションに ec-cube/plugin-installer 以外のパッケージが含まれている場合は、他のパッケージに依存関係があります。

```
"require": {
    "ec-cube/plugin-installer": "~0.0.6 || ^2.0",
    "trikoder/oauth2-bundle": "^2.1",
    "nyholm/psr7": "^1.2",
    "webonyx/graphql-php": "^14.0"
  },
```

**対応方法**

例として [Web APIプラグイン](https://www.ec-cube.net/products/detail.php?product_id=2121){:target="_blank"} の場合は、repositories セクションにGitHubの設定を追加します。

依存関係のあるパッケージすべてを追加する必要があります。

***// ここから // ここまで** は追加時には削除してください。構文エラーになってしまいます*

```
    "repositories": {
        "eccube": {
            "type": "composer",
            "url": "https://package-api.ec-cube.net",
            "options": {
                "http": {
                    "header": ["X-ECCUBE-KEY: <認証キー>"]
                }
            }
        },
        "packagist.org": false,
        "eccube/plugin-installer": {
            "type": "vcs",
            "url": "https://github.com/EC-CUBE/eccube-plugin-installer",
            "no-api": true
        }
        // ここから
        ,
        "trikoder/oauth2-bundle": {
            "type": "vcs",
            "url": "https://github.com/trikoder/oauth2-bundle",
            "no-api": true
        },
        "nyholm/psr7": {
            "type": "vcs",
            "url": "https://github.com/Nyholm/psr7",
            "no-api": true
        },
        "webonyx/graphql-php": {
            "type": "vcs",
            "url": "https://github.com/webonyx/graphql-php",
            "no-api": true
        },
        "php-http/message-factory": {
            "type": "vcs",
            "url": "https://github.com/php-http/message-factory",
            "no-api": true
        },
        "psr/http-message": {
            "type": "vcs",
            "url": "https://github.com/php-fig/http-message",
            "no-api": true
        },
        "psr/http-factory": {
            "type": "vcs",
            "url": "https://github.com/php-fig/http-factory",
            "no-api": true
        },
        "league/oauth2-server": {
            "type": "vcs",
            "url": "https://github.com/thephpleague/oauth2-server",
            "no-api": true
        },
        "symfony/psr-http-message-bridge": {
            "type": "vcs",
            "url": "https://github.com/symfony/psr-http-message-bridge",
            "no-api": true
        },
        "league/event": {
            "type": "vcs",
            "url": "https://github.com/thephpleague/event",
            "no-api": true
        },
        "lcobucci/jwt": {
            "type": "vcs",
            "url": "https://github.com/lcobucci/jwt",
            "no-api": true
        },
        "defuse/php-encryption": {
            "type": "vcs",
            "url": "https://github.com/defuse/php-encryption",
            "no-api": true
        },
        "paragonie/random_compat": {
            "type": "vcs",
            "url": "https://github.com/paragonie/random_compat",
            "no-api": true
        }
        // ここまで
    }
```

### コマンドラインでのプラグインインストール/削除/アップデートを推奨

EC-CUBE4.0系で利用している Composer v1 は、大変多くのリソースが必要なため、レンタルサーバーのような共有環境や、CPUやメモリの利用が限られた環境では、EC-CUBE管理画面からのプラグインインストール/削除/アップデートに失敗するケースが多々あります。

一見、正常に動作しているように見えても、composer.json のパッケージ一覧と、 app/Plugin 以下のファイル、 dtb_plugin のデータに不整合が発生している場合が多いです。

EC-CUBE管理画面よりもコマンドラインを使用した方が遥かに安定していますので、コマンドラインでのプラグイン操作を推奨します

[https://qiita.com/nanasess/items/791c9ec98f69ada93ea0#コマンドラインでのプラグインインストール](https://qiita.com/nanasess/items/791c9ec98f69ada93ea0#%E3%82%B3%E3%83%9E%E3%83%B3%E3%83%89%E3%83%A9%E3%82%A4%E3%83%B3%E3%81%A7%E3%81%AE%E3%83%97%E3%83%A9%E3%82%B0%E3%82%A4%E3%83%B3%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB){:target="_blank"}

### EC-CUBEのバージョンアップを推奨

Composer を通じた関連ライブラリのアップデートができなくなるため、EC-CUBE4.3以降へのバージョンアップを推奨します。

EC-CUBE4.3へのバージョンアップが難しい場合は、可能なかぎりEC-CUBE4.1以降へのバージョンアップを推奨します。EC-CUBE4.0と4.1はプラグインの互換性もあり、比較的少ない工数でのバージョンアップが可能です

### トラブルシューティング

**Q) 管理画面からプラグインをアップデートしようとしたところエラーになりました。**

A) 何らかの原因でプラグインの不整合が発生している可能性があります。エラーの内容を確認の上、コマンドラインでのアップデートをお試しください。

- [packagist.org](http://packagist.org){:target="_blank"} のメタデータにアクセスしようとして発生するエラー
    
    ```diff
    Your requirements could not be resolved to an installable set of packages.
    
      Problem 1
        - ec-cube/Api 2.1.2 requires nyholm/psr7 ^1.2 -> no matching package found.
        - ec-cube/Api 2.1.1 requires nyholm/psr7 ^1.2 -> no matching package found.
        - ec-cube/Api 2.1.4 requires nyholm/psr7 ^1.2 -> no matching package found.
        - ec-cube/Api 2.1.3 requires nyholm/psr7 ^1.2 -> no matching package found.
        - ec-cube/Api 2.1.0 requires nyholm/psr7 ^1.2 -> no matching package found.
        - Installation request for ec-cube/api ^2.1 -> satisfiable by ec-cube/Api[2.1.0, 2.1.3, 2.1.4, 2.1.1, 2.1.2].
    ```
    
    no matching package found. といった、パッケージが見つからないというエラーが発生した場合は、 Composer v1 メタデータアクセス廃止の影響を受けている可能性が高いです。[こちら](/plugin_eccube40#%E4%BB%96%E3%81%AE%E3%83%91%E3%83%83%E3%82%B1%E3%83%BC%E3%82%B8%E3%81%A8%E4%BE%9D%E5%AD%98%E9%96%A2%E4%BF%82%E3%81%AE%E3%81%82%E3%82%8B%E3%83%97%E3%83%A9%E3%82%B0%E3%82%A4%E3%83%B3%E3%81%AE%E5%A0%B4%E5%90%88) を参照して、依存パッケージを composer.json に追加してください
    

- 何らかの原因で依存ライブラリの更新に失敗するエラー
    
    ```
    [21.1MiB/8.61s] Package operations: 0 installs, 0 updates, 42 removals
    [21.1MiB/8.61s]   - Removing webmozart/assert (1.6.0)
    
    In Filesystem.php line 217:
                                                                                   
      Could not delete /var/www/html/vendor/webmozart/assert/CHANGELOG.md: The ty  
      pe "datetimetz" was implicitly marked as commented due to the configuration  
      . This is deprecated and will be removed in DoctrineBundle 2.0. Either set   
      the "commented" attribute in the configuration to "false" or mark the type   
      as commented in "Eccube\Doctrine\DBAL\Types\UTCDateTimeTzType::requiresSQLC  
      ommentHint()."
    ```
    
    エラーログに `In Filesystem.php line 217 Could not delete ～` と記録されるエラーが発生する場合があります。( Could not delete 以降は関係のない deprecated 等のメッセージが表示される)
    
    これは Composer v1 での通信ができないことで、予期せぬエラーが発生したケースです。
    
    データの不整合も発生していますので、コマンドラインでのアップデートをお願いいたします。
    

**Q) 依存関係のあるパッケージのGitHub はどうやって探したらよいですか？**

A) [packagist.org](http://packagist.org){:target="_blank"} の該当パッケージページの画面右側 detail セクションにリンクがあります

### Composer v1 のインストール方法

https://getcomposer.org/download/ にも記載されていますが、 2025年8月以降、v1 のセットアップ方法が廃止される可能性がありますので、v1 を直接ダウンロードする方法を記載します

1. EC-CUBEのインストールディレクトリへ移動
    
    ```
    cd path/to/ec-cube
    ```
    
2. composer.phar のダウンロード
    
    ```
    curl -O https://getcomposer.org/download/1.10.27/composer.phar
    ```
    
3. composer にリネーム
    
    ```
    mv composer.phar composer
    ```
    

以下のコマンドを実行して、composer のロゴが表示されればインストール成功です。

```
php composer list

   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
Composer version 1.10.27 2023-09-29 10:50:23

Usage:
  command [options] [arguments]
...(省略)
```

本記事中の `composer` コマンドは `php composer` として実行してください。

レンタルサーバーなどで comannd not found といったエラーが表示された場合は、通常とは異なる場所に PHP がインストールされている可能性があります。

サーバーのドキュメントを参照し、以下のように PHP のパスを直接指定してみてください

```
# エックスサーバーの例
/usr/bin/php7.4 composer list
```


