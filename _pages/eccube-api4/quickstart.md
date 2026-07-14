---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/quickstart

---

## GitHubからインストール（開発者向け）

1. EC-CUBE（4.0.5以降）をcloneします。
    ```sh
    git clone https://github.com/EC-CUBE/ec-cube.git
    cd ec-cube
    composer install
    ```

1. DATABASE_URL と DATABASE_SERVER_VERSION を適宜変更。*(実際の環境に合わせること)*
    ```sh
    ## for PostgreSQL
    sed -i.bak -e 's/DATABASE_URL=sqlite:\/\/\/var\/eccube.db/DATABASE_URL=postgres:\/\/postgres:password@127.0.0.1\/eccubedb/g' ./.env
    sed -i.bak -e 's/DATABASE_SERVER_VERSION=3/DATABASE_SERVER_VERSION=9/g' ./.env
    ```

    ```sh
    ## for MySQL
    sed -i.bak -e 's/DATABASE_URL=sqlite:\/\/\/var\/eccube.db/DATABASE_URL=mysql:\/\/root:password@127.0.0.1\/eccubedb/g' ./.env
    sed -i.bak -e 's/DATABASE_SERVER_VERSION=3/DATABASE_SERVER_VERSION=5.7/g' ./.env
    ```

1. EC-CUBE4 をインストールします。
    ```sh
    bin/console eccube:install --no-interaction
    ```

1. EC-CUBEオーナーズストアのモックサーバーをセットアップします。
    ``` sh
    # プラグインの保管ディレクトリを作成
    mkdir ${PWD}/repos
    # mockサーバを起動。ここでは9999をポート番号に設定していますが、必要に応じて変更してください
    docker run -d --rm -v ${PWD}/repos:/repos -e MOCK_REPO_DIR=/repos -p 9999:8080 eccube/mock-package-api
    # mockサーバを参照するように環境変数を定義
    echo ECCUBE_PACKAGE_API_URL=http://127.0.0.1:9999 >> .env
    ```

1. 認証キーを設定します。
    ```sh
    bin/console doctrine:query:sql "update dtb_base_info set authentication_key='dummy'"
    ```

1. プラグインのパッケージを配置します。
    ``` sh
    cd repos
    git clone https://github.com/EC-CUBE/eccube-api4.git
    cd eccube-api4
    tar cvzf ../Api-1.0.0.tgz *
    cd ../../
    ```

1. プラグインをインストールします。
    ```sh
    bin/console eccube:composer:require ec-cube/Api42
    bin/console eccube:plugin:enable --code=Api42
    ```
    - 管理画面→オーナーズストア→プラグイン→ **プラグインを探す** からでもプラグインをインストールできます。

1. ビルトインウェブサーバーを起動
    ```sh
    bin/console server:run
    ```

API プラグインの開発のため Git リポジトリで置き換える場合は以下のとおり。
*プラグインをアンインストールすると、 Git リポジトリごと削除されてしまうため注意すること*

```sh
cd app/Plugin/

rm -rf Api
git clone https://github.com/EC-CUBE/eccube-api4.git
mv eccube-api4 Api
```

