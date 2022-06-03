---
title: 開発者向け 開発環境構築スタートアップ
keywords: install Docker docker-composer WSL Windows development VisualStudioCode VSCode devcontainer startup
tags: [quickstart, install, docker, docker-compose, wsl, vscode]
permalink: quickstart/dev-startup-v2
folder: quickstart
---


---



{: .notice--info}
開発環境構築の一例の紹介です。そのため、開発にあたって必須ではない設定やツールの指定が含まれています。

{: .notice--warning}
2022.06 現在、Mac(M1)でのDockerを用いた開発環境構築は非推奨です。

{: .notice--warning}
2022.06 現在、Mac(Intel)でのDockerを用いた開発環境構築はパフォーマンス上の課題があります。[Symfony/Cli](https://qiita.com/nanasess/items/de9f5450717cc8ede51a){:target="_blank"} や [MAMP](/quickstart/gui_mac_install) を用いた環境構築もご検討ください。

{: .notice--warning}
[EC-CUBE4.0.5以下のバージョンをお使いの場合はこちらをご覧ください。](/quickstart/install_docker-compose_405orlower)


## 各種ツールの準備



### docker, docker-compose 実行環境のセットアップ

ホストPCで作業します。

#### Windows

##### WSL2 の インストール

```sh
> wsl --install

# 対話的に入力を求められるWSL(Linux)環境のユーザー・パスワードは任意のものを設定してください
```

- 確認
  - VERSION=2 のUbuntu 環境が作成されていること
  - VERSION=1 の場合、[WSL2へのアップグレード](https://docs.microsoft.com/ja-jp/windows/wsl/install#upgrade-version-from-wsl-1-to-wsl-2){:target="_blank"} を行う

```sh
> wsl -l -v
NAME      STATE           VERSION
* Ubuntu    Stopped         2
```

(任意設定)初期設定ではWSLがホストのメモリを消費し過ぎてしまう場合があるため、利用するメモリサイズの制限を行う。

- Windows上のファイル `C:\User\[WindowsUserName]\.wslconfig` を編集する(存在しない場合は作成する)

```ini
[wsl2]
memory=6GB
swap=0
```

##### [Docker Desktop](https://www.docker.com/products/docker-desktop/){:target="_blank"} のインストール
- WSL2をバックエンドに起動している事を確認する
  - 参考：[Docker Desktop for Windows user manual - General](https://docs.docker.com/desktop/windows/#general){:target="_blank"}

#### Mac

##### [Docker Desktop](https://www.docker.com/products/docker-desktop/){:target="_blank"} のインストール

- メモリ利用量上限がデフォルトでは 2GB に設定されているので、必要に応じて変更する 
  - 参考：[Docker Desktop for Mac user manual - Resources](https://docs.docker.com/desktop/mac/#resources){:target="_blank"}

### Git のセットアップ

#### Windows

##### Git for Windows のセットアップ

ホストPCに [Git for Windows](https://gitforwindows.org/){:target="_blank"} をインストールします。

###### 設定

WSLにログインします。

```sh
> wsl
```

WSLのターミナルでGitの設定を行います。

```sh
# name, email の設定
git config --global user.name "Your Name"
git config --global user.email "yourname@example.com"
# autocrlf 無効化
git config --global core.autocrlf false
# Windows側の資格情報マネージャー(GCM)を参照出来るようにする
git config --global credential.helper "/mnt/c/Program\ Files/Git/mingw64/libexec/git-core/git-credential-manager-core.exe"
```

#### Mac

##### Git のインストール

ホストPCにGitをインストールします。

```sh
brew install git
```

###### 設定

ホストPCのターミナルでGitの設定を行います。

```sh
# name, email の設定
git config --global user.name "Your Name"
git config --global user.email "yourname@example.com"
# autocrlf 無効化
git config --global core.autocrlf false
```


### Visual Studio Code のセットアップ
1. [Visual Studio Code](https://azure.microsoft.com/ja-jp/products/visual-studio-code/){:target="_blank"} のインストール
1. 拡張機能をインストール
  - 「[Remote - Development](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.vscode-remote-extensionpack){:target="_blank"}」extension のインストール

## ワークスペースの準備

### ECCUBE4ソースコードの取得

Windowsの場合**WSL**内、Macの場合はホストPCで作業します。


{: .notice--info}
Windows + DockerDesktop(WSL2 Backend) を利用する場合、ワークスペースをWSLのファイルシステム内に作成することでより良いパフォーマンスが得られます。[Docker-docs-ja 20.10 ドキュメント - ベストプラクティス](https://docs.docker.jp/docker-for-windows/wsl.html#wsl-bestpractices){:target="_blank"}


```sh
mkdir -p ~/Project
cd ~/Project
git clone https://github.com/EC-CUBE/ec-cube.git

cd ec-cube

# [ワークスペース場所確認(Windows+WSLユーザ向け)]
pwd
# /home/[WslUserName]/Project/ec-cube であればOK。
# /mnt/c/Users/[WindowsUserName]/Project .... のような形式の場合、Windowsファイルシステム上にワークスペースが出来てしまっている
```

- WSLファイルシステム上のファイルは`\\wsl$\Ubuntu\` というパスでWindowsエクスプローラから参照する事が出来ます。
- 例として、WSLファイルシステム上の`/home/[WslUserName]/Project/ec-cube` に作成したワークスペースをWindowsエクスプローラで開く場合、以下のようにパスを入力します
  - `\\wsl$\Ubuntu\home\[WslUserName]\Project\ec-cube`

### Visual Studio Code でワークスペースを開く

Windowsの場合**WSL**内、Macの場合はホストPCで作業します。

Visual Studio Codeでワークスペースを開きます。


```sh
cd ~/Project/ec-cube
code .
```

## devcontainer で開発をはじめる

Visual Studio Codeの「Remote - Development」extensiton の機能の一つ(devcontainer) を用いてコンテナ起動・接続を行います。

### devcontainer 設定の調整

初期状態では、「ホストのファイルをコンテナ内に同期する開発用設定」等を記述した`docker-compose.dev.yml`が読み込まれません。

`.devcontainer/devcontainer.json`を編集し、devcontainer起動時の読込対象に追加します。

```json
{
    "name": "eccube",
    "dockerComposeFile": [
        "../docker-compose.yml",
        "../docker-compose.dev.yml", // ←この行を追加
    ],
    "service": "ec-cube",
    "workspaceFolder": "/var/www/html",

    ...
}
```


#### 共有対象フォルダの調整

初期状態では、一部(vendor, var, node_modules)を除いたワークスペース全体のファイルをコンテナ内に共有するよう`docker-compose.dev.yml` にて設定されています。

しかしMacにおいてはファイルシステムの関係上、ワークスペース全体を共有すると十分なパフォーマンスが得られない場合があります。

対策として、共有する対象をapp, src, html 等の開発に頻繁に利用するフォルダに限定することでパフォーマンスの向上が見込める場合があります。

`docker-compose.dev.yml`のservices.ec-cube.volumes の内容を以下のように調整します。

```yml
version: '3'

services:
  ec-cube:
    volumes:
      # ↓コメントアウト
      # - ".:/var/www/html:cached" 
      # ↓追記
      - ".:/var/www/html/app
      - ".:/var/www/html/html
      - ".:/var/www/html/src
```


{: .notice--warning}
共有対象外のファイル(ワークスペース直下のファイル(composer.json, .env 等))はコンテナ内で編集してもホストに反映されなくなります。また、それらの編集をホスト側で行った場合も、コンテナへの反映に再度Dockerfileを用いたビルドが必要になります。

{: .notice--warning}
Mac等で上記調整を行っても十分なパフォーマンスを得られない場合は、[Symfony/Cli](https://qiita.com/nanasess/items/de9f5450717cc8ede51a){:target="_blank"} や [MAMP](/quickstart/gui_mac_install) を用いた環境構築もご検討ください。



### データベースの選択

初期状態では SQLite3 を使用します。

MySQL または Postgres をDBとして利用する場合は、`devcontainer/.devcontainer.json` に `docker-compose.mysql.yml` または `docker-compose.pgsql.yml` を読込対象に追加します。

```json
{
    "name": "eccube",
    "dockerComposeFile": [
        "../docker-compose.yml",
        "../docker-compose.mysql.yml", // またはdocker-compose.pgsql.yml。追加しない場合はSQLite3が利用される
        "../docker-compose.dev.yml", 
    ],
    "service": "ec-cube",
    "workspaceFolder": "/var/www/html",

    ...
}
```


### devcontainer の起動

1. Visual Studio Codeのコマンドパレットを開く
  - [View] - [Command Palette] (Ctrl + Shift + P)

2. 「Remote-Containers: Reopen in Container」を入力・選択する

3. 初回は各種コンテナイメージのダウンロードとビルドのため、数分待つ

4. エラーなく起動したら、サイドバーのRemote Explorer でコンテナの起動を確認する

devcontainerでコンテナに接続している場合、Visual Studio Code のウィンドウタイトルに **[Dev Container: eccube]** と表示されます。

以後、基本的に作業はこのウィンドウで行います。

#### Git 設定確認

[Dev Container: eccube] 内のターミナルで作業します。
- [Terminal] - [New Terminal] (Ctrl + Shift + @)

devcontainer 内のターミナルで、ホストのGitグローバル設定が反映されている事を確認します。

```sh
git config -l --show-origin
```

### 初回作業          
Visual Studio Code の**[Dev Container: eccube]** ウィンドウ内のターミナルで作業します。
- [Terminal] - [New Terminal] (Ctrl + Shift + @)

#### 依存ライブラリのインストール

```sh
# 依存ライブラリインストール
composer install --no-scripts --no-plugins
# 依存ライブラリの所有者をwww-dataに変更する。プラグインインストール等でwww-dataユーザとして管理画面からcomposer installを行う場合があるため。
chown -R www-data: vendor
```

#### インストールスクリプト実行

```sh
# DB作成・初期データ投入・Symfonyキャッシュ生成等
su www-data -s /bin/sh -c 'composer run-script installer-scripts && composer run-script auto-scripts'
```

### 確認

ブラウザで [http://localhost:8080](http://localhost:8080){:target="_blank"} を開き、ECCUBE4トップページが正常に表示される事を確認します。

### devcontainer の停止

- Visual Studio Code の devcontainer [Dev Container: eccube] のウィンドウを閉じる
- Windowsの場合**WSL**内、Macの場合はホストPCのターミナルで以下を実行する
  - ```sh
    docker-compose down --remove-orphans
    ```
 

### トラブルシューティング

- TableNotFoundException が発生する
  - -> インストールスクリプトを実行していない可能性があります。[インストールスクリプト実行](#インストールスクリプト実行) を行ってみてください。

- 「Composer is not installed.」と表示される
  - -> 依存ライブラリのインストールが実行されていない可能性があります。[依存ライブラリのインストール](#依存ライブラリのインストール) を行ってみてください。

- devcontainer 起動時にエラー「Bind for 0.0.0.0:8080 failed: port is alread
y allocated」 が発生する
  - 既に別のアプリケーションでホストの8080番ポートが使用されています。`docker-compose.yml` の `services.eccube.ports` の `- 8080:80` の左部分を別のポートに変更してみてください。