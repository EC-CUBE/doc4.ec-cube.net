# EC-CUBE 4開発ドキュメント

[EC-CUBE 4開発ドキュメント](https://doc4.ec-cube.net/)のリポジトリです。

EC-CUBE 4 の仕様や手順、開発Tipsに関するドキュメントを掲載しています。

修正や追記、新規ドキュメントの作成をいただく場合、

本レポジトリへPullRequestをお送りください。


## 開発協力に関して

コードの提供・追加、修正・変更その他「EC-CUBE」への開発の御協力（Issue投稿、PullRequest投稿など、GitHub上での活動）を行っていただく場合には、
[EC-CUBEのコピーライトポリシー](https://github.com/EC-CUBE/ec-cube/wiki/EC-CUBE%E3%81%AE%E3%82%B3%E3%83%94%E3%83%BC%E3%83%A9%E3%82%A4%E3%83%88%E3%83%9D%E3%83%AA%E3%82%B7%E3%83%BC)をご理解いただき、ご了承いただく必要がございます。

PullRequestを送信する際は、EC-CUBEのコピーライトポリシーに同意したものとみなします。

## 本ドキュメントサイトの構成ツールについて

EC-CUBE 4.0 開発者向けドキュメントは[github pages](https://pages.github.com/)でホスティングされています。

また、[Jekyll](http://jekyllrb-ja.github.io/)の[Minimal Mistakes Jekyll theme](https://mmistakes.github.io/minimal-mistakes/)というテーマを利用しています。

## PullRequestの送信方法

Githubアカウントを作成し、自身のリポジトリよりPullRequestを作成してください。

### doc4.ec-cube.netをForkする

doc4.ec-cube.netのリポジトリをご自身のGithubリポジトリにForkします。

### 任意のディレクトリにクローンする

Forkしたご自身のリポジトリからソースを、`git clone` コマンドで自身のPCにコピーします。

```
$ git clone https://github.com/[ご自身のアカウント名]/doc4.ec-cube.net.git
```

### リモートリポジトリに本家のリポジトリを登録する

本家のリポジトリの名前を`upstream`（任意）で登録します。

```
$ cd doc4.ec-cube.net/
$ git remote add upstream https://github.com/EC-CUBE/doc4.ec-cube.net.git
```

### ローカルのブランチを最新し、修正用のブランチを作成する
```
$ git pull upstream master
$ git checkout -b [任意のブランチ名]
```

### ドキュメント編集について

#### 本文の編集

_pages/以下のディレクトリにある.mdファイルを変更することで、ページの編集が可能です。

#### サイドバーの編集

_data/navigation.ymlに設定項目を追加します。

#### 設定ファイル

_config.ymlはサイト全体に適用されている設定ファイルです。

### 自身のリポジトリに修正内容を反映する

```
$ git add [修正したファイル]
$ git commit -m "[コメント]"
$ git push origin [ブランチ名]
```

その後、本家のリポジトリに自身のGithubリポジトリよりPullRequestを作成してください。



## 修正したドキュメントをローカル環境で確認するには

ローカル開発環境を構築することにより、

ドキュメントを修正した場合際に、ローカルPCで変更箇所を確認することができます。

### Dockerを利用する

[Docker Compose](http://docs.docker.jp/compose/toc.html) がインストールされていればより簡単な方法でドキュメントを生成できます。
コマンドを実行後、 ブラウザで `http://localhost:4000` にアクセスしてください。

```bash
# ディレクトリ移動
$ cd doc4.ec-cube.net

# サーバ起動します。
# * 起動するまでに多少時間がかかります。ご注意ください。
# * マークダウンファイルを編集すれば数秒後にHTMLの再生成が行われます。
$ docker-compose up
```

Windows、Macの環境で動作確認済みです。

### ローカルのRuby環境を利用する

#### 前提条件

1. ローカル環境にruby(バージョン：2.4.0以上)がインストールされている必要があります。
2. Windows環境の場合、Git Bash等のターミナルを利用して下さい。
3. ご自身のGithubアカウントが必要になります。

※ Rubyのバージョン確認方法

```
$ ruby -v
ruby 2.4.5p335 (2018-10-18 revision 65137) [x64-mingw32]
```

#### gem（rubyのライブラリ）のインストールを行う

`bundle install`により、gemfile.lockを元にgemのインストールを行います。

```
$ bundle install
```

※ Windows環境では、gemfile.lockが更新されてしまいますが、

git管理から除外（コミット対象から除外する）するように下さい。

```
eventmachine (1.2.7-x64-mingw32)
```
#### ローカルサーバーでサイトを立ち上げる

以下のコマンドでサイトが立ち上がります。

```
$ bundle exec jekyll serve
（省略）
Server address: http://localhost:4000
Server running... press ctrl-c to stop.
```

http://localhost:4000 にブラウザのURLでアクセスすると、
EC-CUBE 4開発ドキュメントのページが表示されます。
