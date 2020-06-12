---
title: オーナーズストアとの通信をmockする
keywords: plugin mock プラグイン オーナーズストア
tags: [plugin, mock, package-api]
permalink: plugin_mock_package_api

---

オーナーズストアのmockサーバをたて、プラグインのダウンロードをオーナーズストア経由でのダウンロードのように行う手順を解説します。
オーナーズストア申請前のプラグインの検証等に活用してください。

### requirements

mockサーバを利用するために、dockerが必要になります。

適宜インストールをしてください。

### mockサーバの実行手順

mockサーバを実行するための手順は以下のとおりです。

```
// ec-cubeのルートディレクトリに移動
$ cd /path/to/ec-cube

// プラグインの保管ディレクトリを作成
$ mkdir ${PWD}/repos

// mockサーバを起動。ここでは9999をポート番号に設定していますが、必要に応じて変更してください
docker run -d --rm -v ${PWD}/repos:/repos -e MOCK_REPO_DIR=/repos -p 9999:8080 eccube/mock-package-api

// mockサーバを参照するように環境変数を定義
echo ECCUBE_PACKAGE_API_URL=http://127.0.0.1:9999 >> .env

// 認証キーを設定
psql eccube_db -h 127.0.0.1 -U postgres -c "update dtb_base_info set authentication_key='test';"

// reposディレクトリにプラグインを設置。拡張子はtgzに変更してください
cp /path/to/SamplePlugin.tar.gz repos/SamplePlugin.tgz
```

以上の手順を行うと、「プラグインを探す」でreposディレクトリに配置したプラグインが表示されます。

![プラグインを探す](./images/plugin/mock-server.png)

