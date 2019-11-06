# EC-CUBE 4開発ドキュメント

github page of the [https://doc4.ec-cube.net/](https://doc4.ec-cube.net/)

EC-CUBE 4 の仕様や手順、開発Tipsに関するドキュメントを掲載しています。
修正や追記、新規ドキュメントの作成をいただく場合、本レポジトリへPullRequestをお送りください。


## 開発協力に関して

コードの提供・追加、修正・変更その他「EC-CUBE」への開発の御協力（Issue投稿、PullRequest投稿など、GitHub上での活動）を行っていただく場合には、
[EC-CUBEのコピーライトポリシー](https://github.com/EC-CUBE/ec-cube/wiki/EC-CUBE%E3%81%AE%E3%82%B3%E3%83%94%E3%83%BC%E3%83%A9%E3%82%A4%E3%83%88%E3%83%9D%E3%83%AA%E3%82%B7%E3%83%BC)をご理解いただき、ご了承いただく必要がございます。
PullRequestを送信する際は、EC-CUBEのコピーライトポリシーに同意したものとみなします。

## 本ドキュメントサイトの構成について

### ツール

**[Jekyll](http://jekyllrb-ja.github.io/)** を利用して *マークダウン形式のプレーンテキスト* から *HTML* を生成しています。

github pageはJekyll Documentation themeを使っています。

### 生成ドキュメントを確認するには

Windows環境の方は以下のURLを参考に環境を作成してください。

* [jekyll 2.5.3をWindows環境にインストール - Qiita](https://qiita.com/chihiro-adachi/items/99a82c902b4c8467aa4c){:target="_blank"}

[Docker Compose](http://docs.docker.jp/compose/toc.html) がインストールされていればより簡単な方法でドキュメントを生成できます。  
コマンドを実行後、 ブラウザで `http://localhost:4000` にアクセスしてください。

```bash
# ディレクトリ移動
$ cd doc4.ec-cube.net

# サーバ起動します。マークダウンファイルを編集すれば数秒後にHTMLの再生成が行われます。
$ docker-compose up -d
```

---

Build the site to see the instructions for using it. Or just go here: [http://idratherbewriting.com/documentation-theme-jekyll/](http://idratherbewriting.com/documentation-theme-jekyll/)

---

## ローカル開発環境の構築

EC-CUBE 4開発ドキュメントはローカル開発環境を構築することにより、
ドキュメントを修正した場合にローカルPCで確認することができます。
Windows、Macの環境で動作確認済みです。


### 前提条件

1. ローカル環境にruby(バージョン：2.4.0以上)がインストールされている必要があります。
2. Windows環境の場合、Git Bash等のターミナルを利用して下さい。
3. ご自身のGithubアカウントが必要になります。

※ Rubyのバージョン確認方法

```
$ ruby -v
ruby 2.4.5p335 (2018-10-18 revision 65137) [x64-mingw32]
```

### 開発環境構築手順


#### doc4.ec-cube.netをForkする

doc4.ec-cube.netのリポジトリをご自身のGithubリポジトリにForkします。

#### 任意のディレクトリにクローンする

Forkしたご自身のリポジトリからソースを、`git clone` でローカルにコピーします。
```
$ git clone https://github.com/[ご自身のアカウント名]/doc4.ec-cube.net.git
```

#### リモートリポジトリに本家のリポジトリを登録する

本家のリポジトリの名前を`upstream`（任意）で登録します。

```
$ cd doc4.ec-cube.net/
$ git remote add upstream https://github.com/EC-CUBE/doc4.ec-cube.net.git
```

#### gem（rubyのライブラリ）のインストールを行う

`bundle install`により、gemfile.lockを元にgemのインストールを行います
。

```
$ bundle install
```

※ Windows環境では、gemfile.lockが更新されてしまいますが、git管理から無視するように下さい。（コミット対象から除外する）

```
eventmachine (1.2.7-x64-mingw32)
```
#### ローカルサーバーでサイトを立ち上げる

以下のコマンドでサイトが立ち上がります。

```
$ bundle exec jekyll serve
（省略）
Server address: http://127.0.0.1:4005
Server running... press ctrl-c to stop.
```

http://127.0.0.1:4005 にブラウザのURLでアクセスすると、
EC-CUBE 4開発ドキュメントのページが表示されます。
