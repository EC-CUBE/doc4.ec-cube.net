---
title: 設定ファイル
keywords: config file 
tags: [spec, getting_started]
sidebar: home_sidebar
permalink: spec_config
summary: EC-CUBEに用意されている各種設定ファイルの利用方法を説明します。
---

# {{ page.title }}


EC-CUBE3ではアプリケーションで利用可能な設定ファイルが用意されており、  
データベースの接続情報やメールに関する情報などが Yaml 形式で記述されています。  


## 設定ファイルの配置と設定の変更方法

設定ファイルは以下のディレクトリ配置されます。  

- デフォルト設定ファイル
`ECCUBEROOT/src/Eccube/Resource/config`

- インストール後の設定ファイル
`ECCUBEROOT/app/config/eccube`

インストール時に画面上に入力された内容が設定ファイルに書き込まれ、  
`ECCUBEROOT/app/config/eccube` に保存されます。書き込み対象の設定ファイルは、 

`config.yml` `database.yml` `mail.yml` `path.yml`  

上記以外の設定ファイルも`ECCUBEROOT/src/Eccube/Resource/config`から`ECCUBEROOT/app/config/eccube`へコピーし、  
distの拡張子を外して`xxxx.yml`にすると利用できます。  

デフォルト設定ファイルの内容は変更したくないが、設定ファイルの値を変更したいという時はコピーしてご利用ください。  

設定ファイルが読み込まれる順番は、  

1. `ECCUBEROOT/app/config/eccube`にあるymlファイル
1. `ECCUBEROOT/src/Eccube/Resource/config`にあるyml.distファイル

の順番で読み込まれます。  


### 設定ファイルのコメント方法

設定ファイルの内容を無効にするためにコメントアウトしたい場合、  
各設定内容の前に`#`をつければコメントアウト状態になり読み込まれません。  

但しコメントアウトする場合、以下の注意が必要です。  

`ECCUBEROOT/app/config/eccube/config.yml`の内容にある`cookie_lifetime`をコメントアウトをしようとして、  

```
# cookie_lifetime: 0
```

とコメントアウトを有効にしても、  
`ECCUBEROOT/src/Eccube/Resource/config/config.yml.dist`にある  

```
cookie_lifetime: 0
```

`config.yml.dist`ファイルの内容がコメントアウトされていなければその値が有効になるため、  
確実にコメントアウトをしたい場合、両方のファイルの設定内容をコメントアウトするようにしてください。  

### PHP array 形式の設定ファイル

EC-CUBE 3.0.11 より、 PHP array 形式の設定ファイルも使用可能です。  
`html/index.php` にある 、 `Eccube\Application::getInstance()` のパラメータ引数を `output_config_php = true` に設定することで、Yaml 形式の設定ファイルを元に PHP array 形式の設定ファイルが出力されます。  

PHP array 形式の設定ファイルが存在する場合は、 Yaml 形式の設定ファイルより優先されます。  
PHP array 形式の設定ファイルを使用すると、 Yaml ファイルを Parse する必要がないため、高速化が期待できます。  

PHP5.4以降の場合、short array syntax で、可読性のよい Config PHP ファイルを使用できます。  


```php
<?php
// app/config/eccube/config.php
return [
    'auth_magic' => 'tmdKGUaT8iaB2ROAhpL0oKLXOGbe4Uut',
    'password_hash_algos' => 'sha256',
    'shop_name' => 'EC-CUBE3',
    'force_ssl' => null,
    'admin_allow_host' => [],
    'cookie_lifetime' => 0,
    'locale' => 'ja',
    'timezonee' => 'Asia/Tokyo',
    'eccube_install' => 1
];

```

### 設定ファイルの内容を呼び出す

設定ファイルの内容をアプリケーションから呼び出す方法は、

- Controller等から使用する場合

```php
public function index(Application $app)
{

    $app['config']['admin_route']

}
```

- Twigから使用する場合

```twig
{% raw %}<img src="{{ app.config.image_save_urlpath }}/画像ファイル名">{% endraw %}
```
と記述する事で利用可能です。


## 設定ファイルの説明
用意されている設定ファイルの説明です。

### config.yml
auth_magicやtimezoneの設定など EC-CUBE 3のアプリケーションに関わる設定が記述されます。

### constant.yml
アプリケーションで利用する定数が記述されます。現状では不要な定数も定義されていますが、将来のバージョンでは不要な定数は削除予定です。

### database.yml
データベースの接続情報が記述されます。インストール画面で入力された内容が設定されます。

### database.yml.sqlite3
インストール時にデータベースをSQLiteを選択すると利用される設定ファイルであり、SQLiteに関わる内容が記述されます。

### database.yml.sqlite3-in-memory

SQLite をインメモリで使用するための設定です。ユニットテストで使用されます。

### doctrine_cache.yml
Doctrineをキャッシュする際に利用する設定ファイルであり、使用するドライバが記述されます。  
index_dev.php 使用時は `driver: array` が使用されます  

- filesystem の場合の設定例

```yml
## /path/to/ec-cube/app/config/eccube/doctrine_cache.yml
doctrine_cache:
  metadata_cache:
    driver: filesystem
    path: /path/to/ec-cube/app/cache/doctrine/metadata
    host:
    port:
    password:
  query_cache:
    driver: filesystem
    path: /path/to/ec-cube/app/cache/doctrine/query
    host:
    port:
    password:
  result_cache:
    driver: filesystem
    path: /path/to/ec-cube/app/cache/doctrine/query
    host:
    port:
    password:
    lifetime: 3600
    clear_cache: true
  hydration_cache:
    driver: filesystem
    path: /path/to/ec-cube/app/cache/doctrine/query
    host:
    port:
    password:
```

Doctrineのキャッシュ設定の詳細は、[doctrine_cacheの設定](/spec_doctrine-cache) を御確認ください。


### http_cache.yml
Symfony2の機能であるHTTPキャッシュを利用する際の設定ファイルであり、キャッシュ有効期限や対象URLが記述されます。

```yml
http_cache:
    enabled: false (trueにすると有効)
    age: 10 (magageのことであり単位は秒)
    # フロントでキャッシュを適用させる画面のrouteを設定
    route:
        - homepage
        - product_list
        - block_category
        - block_news
        - block_search_product
        - help_about
        - help_guide
        - help_privacy
        - help_tradelaw
        - help_agreement
```

httpキャッシュ利用時の注意点は以下のとおりです。

- index_dev.phpの時はhttpキャッシュはされない
- 管理画面はprivateでキャッシュする
- キャッシュ方法はetagを利用してキャッシュされるが、ロードバランサーを使用していた場合、毎回更新されてしまう
- ageに0を設定するとetagの内容が変更されない限りキャッシュされている
- ageに-1が設定されるとキャッシュされずに常に更新されている
- ageを設定すると、コンテンツの内容が変更されてもageの秒数が経過するまで反映されない
- Tokenを利用している画面にはhttpキャッシュを適用させない

### log.yml
EC-CUBEが出力するログファイルの設定が記述されます。

```yml
log:
    prefix: site_ (ログファイル名のprefix)
    suffix:
    format: Y-m-d (ログファイル名に付与される)
    action_level: INFO (ログ出力レベル)
    log_level: INFO (ログ出力レベル)
    max_files: 90 (ログファイル最大出力数)
    exclude_keys: (ログにrequestの内容を出力する際に不要となるキー)
        - password
        - app
```

### mail.yml
メール送信時に利用する内容が記述されます。

### nav.yml
管理画面で表示されている左メニューを表示するための内容が記述されます。

### path.yml
サイトの絶対パスやURLからの相対パスが記述されます。

### session_handler.yml
セッションハンドラを指定する設定ファイルです。  
セッションの保存先を、デフォルトのファイルからmemcachedやredis等に変更することができます。  

```yml
session_handler:
    enabled: true/false (他のセッションハンドラに切り替える場合, true)
    save_handler: redis/memcache/memcached (使用するハンドラ名)
    save_path: 127.0.0.1:6379 (各ハンドラの設定)
```

セッションハンドラの設定の詳細は、[セッションハンドラの設定](/spec_session-handler) を御確認ください。
