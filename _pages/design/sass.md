---
title: Sass(scss)の利用方法
keywords: design 
tags: [design]
permalink: design_sass

summary: Sass(scss)の編集方法
---

EC-CUBEのCSSは、[Sass(scss)](http://sass-lang.com){:target="_blank"} を使用して記述されています。

1. [scssのディレクリ格納場所](#scssのディレクリ格納場所)
1. [scssの各ディレクトリとファイルについて](#scssの各ディレクトリとファイルについて)
1. [Sass(scss)のビルド方法](#sass_build)


## Sass(scss)のディレクリ格納場所
- Sass のソースコードは、以下のディレクトリに格納されています。

```
[html]
 └─ [template]
     └─ [default] # 管理画面はadminになります
         └─ [assets]
             ├─ [css]
             └─ [scss]
```

## scssの各ディレクトリとファイルについて

scssディレクトリ内はメンテナンスしやすいように構成されております。<br>
コンポーネント設計及びCSSの記述方針についてはFLOCSSルールを採用しております。<br>
[スタイルガイド](https://github.com/EC-CUBE/Eccube-Styleguide){:target="_blank"} も合わせてご参照ください。

```
[assets]
 ├─ [css]
 │    ├─ style.css     # 読み込まれているCSS ←ビルドして書き出される先
 │    └─ style.min.css # 軽量版CSS ←ビルドして書き出される先
 └─ [sass]
      ├─ [component]   # ベースとなる最小モジュール（部品）が格納
      ├─ [project]     # トップページやヘッダーやフッターなど格納
      ├─ [mixins]      # 再利用スタイルの設定が格納
      ├─ [sections]    # 上書き用
      └─ style.scss    # 各scssファイルがimportで読み込まれているファイル
```

- component<br>
  見出しやボタンなど、ベースとなる最小モジュール（部品）が格納されています。<br>

- project<br>
  ヘッダーやフッター、トップページで使用されているモジュール（部品）が格納されています。<br>

- mixins <br>
  再利用や複数の場所で呼び出しするスタイルの設定が格納されています。

- sections<br>
  componentやprojectのCSSクラスを上書きする際にご利用ください。<br>
  ご利用の際はstyle.scssに以下のコードを追記ください。

```css
@import "sections/components";
@import "sections/projects";
```

- style.scss<br>
  各ディレクトリのCSSが `@import` で読み込まれているscssファイルです。<br>
  こちらのstyle.scssが、style.cssやstyle.min.cssに変換されます。
  
  
### スタイルガイドについて

EC-CUBEでは、CSSやHTMLの設計指針やコーディングルールを確認できるよう、 `スタイルガイド` を用意しています。
詳しくは以下を参照ください。

- [フロント画面のスタイルガイド](https://github.com/EC-CUBE/Eccube-Styleguide){:target="_blank"}
- [管理画面のスタイルガイド](https://github.com/EC-CUBE/Eccube-Styleguide-Admin){:target="_blank"} 




## Sass(scss)のビルド方法 {#sass_build}

1. [ビルド環境の準備](#ビルド環境の準備)
1. [EC-CUBE4.0.3までのビルド方法](#build_eccube403)
1. [EC-CUBE4.0.4でのビルド方法](#build_eccube404)


## ビルド環境の準備

EC-CUBE内に組み込まれている [Gulp](https://gulpjs.com/){:target="_blank"} を使用したビルド方法をご紹介いたします。<br>
前提として [Node.js公式サイト](https://nodejs.org/ja/){:target="_blank"} より、 Node.js をインストールが必要です。

- Node.js<br>
  GulpはNode.jsをベースに作られているので、Gulp動かす為に必要となります。<br>
  ※最新のNode.jsでは、[Gulpのタスク実行時にエラーが出る場合](https://qiita.com/KKKarin/items/bbb424fd93ef523a741a)があります。

- Gulp<br>
  SassファイルをCSSに変換に利用されます。<br>
  EC-CUBE4.0.3まではGulp3、4.0.4からはGulp4が導入


**1.Node.js をPCへインストールしてください。**<br>

[Node.js公式サイト](https://nodejs.org/ja/){:target="_blank"} よりダウンロードしPCへインストールしてください。<br>
Node.jsがインストールされているかは、以下のコマンドで確認できます。
```shell
node -v
```

**2.ec-cube_rootディレクトリへ移動**<br>
package.json、gulpfile.jpファイルが格納されているroot階層へ移動します。

```shell
cd path/to/eccube_root # path/to/eccube_rootの部分はEC-CUBEのディレクトリパスをご指定ください
```

**3.node_modulesディレクトリが生成します。**<br>

下記コマンドを実行して、上記移動したディレクトリにnode_modulesが生成されている事を確認してください。

```shell
npm install # node_modulesディレクトリが生成
```


## EC-CUBE4.0.3でのビルド方法 {#build_eccube403}

以下のコマンドでscssをCSSに変換します。

```shell
npm run build # scssをstyle.cssとstyle.min.cssに書き出します 
```

変換されたcssは `html/template/{admin,default}/assets/css/` に出力されます。<br>
style.cssとstyle.min.css（改行など省略した軽量版）が出力されます。


### EC-CUBE4.0.3までデフォルトテンプレート以外を適用している場合の注意点

テンプレートをdefault以外に変更されている場合、gulpfile.jsの値を変更する必要があります。<br>
`eccube_root/gulpfile.js` 内の以下のdefaultの部分を変更してください
```
[ec-cube_root]
 └─ gulpfile.js # srcPatternの設定を変更
```

```js
const srcPattern = [
    'admin',
    'default' //defaultの部分を適用中のテンプレート名に変更
];
```
※EC-CUBE4.0.4からは上記設定は必要なくなりました。<br>
※デザインテンプレートによっては、Sassの導入・格納場所が異なる場合がありますので、<br>
ご購入されたデザインテンプレートのマニュアルなどをご参照ください。


## EC-CUBE4.0.4以降でのビルド方法 {#build_eccube404}

4.0.4から新たに、自動ビルドであるwatch機能が追加されました。<br>
[【注意】Windowsの方は一部コードの修正が必要になります](#win_sass)

設定ファイルは `gulp/config.js` にありますので先に設定をしておいてください。

```shell
npm run build # scssをstyle.cssとstyle.min.cssに書き出します 
```
```shell
npm run watch # scssの更新を監視、自動でstyle.cssとstyle.min.cssに書き出します
```
```shell
npm run start # 監視、自動書き出し＆ブラウザ自動更新
```

**watchやstartの監視を停止する方法:「Ctrl」+「C」のショートカットキー**


変換されたcssは `html/template/{admin,default}/assets/css/` に出力されます。<br>
style.cssとstyle.min.css（改行など省略した軽量版）が出力されます。



### 【注意】Windowsの方は一部コードの修正が必要になります {#win_sass}

4.0.4パッケージ版のWindows環境でSassビルド時にstyle.cssが正しく書き出されない不具合あり、<br>
Gulpfile.jsの以下の２箇所を変更お願いします。※GitHubの最新コードは修正済み

```
[ec-cube_root]
 └─ gulpfile.js # Sassのビルド設定が書かれているファイルを一部変更してください。
```
gulpfile.jsの90行目と115行目の２箇所のコードを変更してください。
```js
path.dirname = path.dirname.replace('/scss', '/css') # 変更前コード
```

```js
path.dirname = path.dirname.replace(/scss$/, 'css') # 変更後コード
```
