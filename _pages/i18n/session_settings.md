---
title: セッション情報保存方法の変更
keywords: session_handler
permalink: session_handler_settings
folder: i18n

---

## 概要

初期設定ではファイルシステム上にセッションデータが保存されますが、複数台のWebサーバーで稼働させる場合などにセッション情報の保存方法を変更することができます。
EC-CUBEではSameSite Cookie対応のために独自のセッションハンドラー(`SameSiteNoneCompatSessionHandler`)を利用しています。セッション情報の保存方法を変更するには `SameSiteNoneCompatSessionHandler` 内で利用するセッションハンドラーを変更します。


### RDBMSに保存する

#### 設定例
EC-CUBEのアプリケーションで利用しているデータベースと同じものを利用するには以下の設定を追加します。

```
services:
  db_session_handler:
    class: Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler
    public: false
    arguments:
      - '%env(DATABASE_URL)%'

  Eccube\Session\Storage\Handler\SameSiteNoneCompatSessionHandler:
    arguments:
      - '@db_session_handler'
```

#### テーブルの作成
設定を有効化するには予めセッションを保存するテーブルを作成しておく必要があります。RDBMSに合わせて以下のDDLを実行してテーブルを作成します。

- PostgreSQLの場合

    ```
    CREATE TABLE sessions (
        sess_id VARCHAR(128) NOT NULL PRIMARY KEY,
        sess_data BYTEA NOT NULL,
        sess_time INTEGER NOT NULL,
        sess_lifetime INTEGER NOT NULL
    );
    ```
    https://symfony.com/doc/3.4/doctrine/pdo_session_storage.html#postgresql


- MySQLの場合

    ```
    CREATE TABLE `sessions` (
        `sess_id` VARCHAR(128) NOT NULL PRIMARY KEY,
        `sess_data` BLOB NOT NULL,
        `sess_time` INTEGER UNSIGNED NOT NULL,
        `sess_lifetime` MEDIUMINT NOT NULL
    ) COLLATE utf8_bin, ENGINE = InnoDB;
    ```
    https://symfony.com/doc/3.4/doctrine/pdo_session_storage.html#mysql


### Memcacheに保存する

#### 必須ライブラリ
- [memcached 拡張モジュール](https://www.php.net/manual/ja/book.memcached.php)

#### 設定例

Memcacheがlocalhostのポート11211番で稼働しているとき以下のような設定になります。

```
services:
  memcached:
    class: Memcached
    public: false
    arguments: ['sess']
    calls:
      - [ addServer, [ 'localhost', '11211' ]]

  memcached_session_handler:
    class: Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler
    public: false
    arguments: [ '@memcached', { prefix: 'sess', expiretime: '3600' }]

  Eccube\Session\Storage\Handler\SameSiteNoneCompatSessionHandler:
    arguments:
      - '@memcached_session_handler'
```

### MongoDBに保存する

#### 必須ライブラリ
- [MongoDB driver 拡張モジュール](https://www.php.net/manual/ja/set.mongodb.php)
- [mongodb/mongodb パッケージ](https://github.com/mongodb/mongo-php-library)

#### 設定

MongoDBがlocalhostのポート27017番で稼働しているとき以下のような設定になります。

```
services:
    mongo_client:
        class: MongoDB\Client
        arguments: ['mongodb://user:password@localhost:27017']
    mongodb_session_handler:
        class: Symfony\Component\HttpFoundation\Session\Storage\Handler\MongoDbSessionHandler
        arguments: ['@mongo_client', { 'database': 'session_db', 'collection': 'sessions' }]

    Eccube\Session\Storage\Handler\SameSiteNoneCompatSessionHandler:
        arguments:
            - '@mongodb_session_handler'
```
