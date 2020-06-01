---
title: CSSとSassの利用
keywords: design 
tags: [design]
permalink: design_css
summary: CSSとSassの編集方法
---

## CSSの利用について

EC-CUBEでは、CSSを編集する方法として３種類あります。

1. [管理画面のCSS管理から編集する](#管理画面のCSS管理から編集する)
2. [style.cssを直に編集する](#style.cssを直に編集する)
3. [Sassを利用して編集する](#Sassを利用して編集する)

トップページのメインビジュアルのスライドなど一部CSSはtwigファイル内に記述されています。<br>
EC-CUBE管理画面の[コンテンツ管理] -> [ページ管理]のTOPページで編集可能です。


## 管理画面のCSS管理から編集する

EC-CUBE管理画面の[コンテンツ管理] -> [CSS管理]からCSSを記述できます。

- [CSS管理]に記述したコードは、以下のディレクトリのcustomize.cssに反映されます。<br>
  `html/user_data/assets/css/customize.css`<br>
  管理画面からの編集（customize.css）の記述は、style.cssより優先されます。


## style.cssを直に編集する

EC-CUBEのCSSは、以下ディレクトリ内のstyle.cssにまとまっています。<br>
CSSメンテナンスの観点から、この後ご紹介するSassの利用もご検討ください。

- style.cssは、以下のディレクトリに格納されています。
  `html/template/default/assets/css/style.css`


## Sassを利用して編集する

EC-CUBEのCSSは、[Sass](http://sass-lang.com){:target="_blank"} を使用して記述されています。

- Sass のソースコードは、以下のディレクトリに格納されています。<br>
  `html/template/{admin,default}/assets/scss` にあります。

### scssの各ディレクトリとファイルについて

scssディレクトリ内はメンテナンスしやすいように構成されております。<br>
コンポーネント設計及びCSSの記述方針についてはFLOCSSルールを採用しております。<br>
[スタイルガイド](https://eccube4-styleguide.herokuapp.com/){:target="_blank"} も合わせてご参照ください。

- component<br>
  見出しやボタンなど、ベースとなる最小モジュール（部品）が格納されています。<br>
  [スタイルガイド](https://eccube4-styleguide.herokuapp.com/){:target="_blank"} 1-9までの項目

- project<br>
  ヘッダーやフッター、トップページで使用されているモジュール（部品）が格納されています。<br>
  [スタイルガイド](https://eccube4-styleguide.herokuapp.com/){:target="_blank"} 11-22までの項目

- mixins <br>
  再利用や複数の場所で呼び出しするスタイルの設定が格納されています。

- sections<br>
  componentやprojectのCSSクラスを上書きする際にご利用ください。<br>
  ご利用の際はstyle.scssに以下のコードを追記ください。

```css
@import "sections/component";
@import "sections/projects";
```

- style.scss<br>
  各ディレクトリのCSSが `@import` で読み込まれているscssファイルです。<br>
  こちらのstyle.scssが、style.cssやstyle.min.cssに変換されます。

### Sassのビルド

EC-CUBE内に組み込まれている [Gulp3](https://gulpjs.com/){:target="_blank"} を使用したビルド方法をご紹介いたします。<br>
前提として [公式サイト](https://nodejs.org/ja/){:target="_blank"} より、 Node.js をインストールしておいてください。

- Node.js<br>
  GulpはNode.jsをベースに作られているので、Gulp動かす為に必要となります。<br>
  ※最新のNode.jsでは、[Gulpのタスク実行時にエラーが出る場合](https://qiita.com/KKKarin/items/bbb424fd93ef523a741a)があります。

- Gulp3<br>
  SassファイルをCSSに変換に利用されます。

### パッケージ版のEC-CUBE4.0.3でのSassビルド方法

公式サイトからダウンロードしたEC-CUBE4.0.3でのビルド方法になります。<br>
Github上のEC-CUBEをダウンロードされた方は、コマンドなどが変更となっていますので [#4353](https://github.com/EC-CUBE/ec-cube/pull/4353){:target="_blank"} をご参照ください。

package.json、gulpfile.jpファイルが格納されているEC-CUBEのディレクトリパスへ移動します

```shell
cd path/to/eccube_root #path/to/eccube_rootの部分はEC-CUBEのディレクトリパスをご指定ください
```

初回のみ、installを実行ください。node_modulesディレクトリが生成されます。

```shell
npm install # 初回のみ
```

以下のコマンドでscssをCSSに変換します。

```shell
npm run build # Sass のビルド CSS出力
```

変換されたcssは `html/template/{admin,default}/assets/css/` に出力されます。<br>
style.cssとstyle.min.css（改行など省略した軽量版）が出力されます。


### デフォルトテンプレート以外を適用している場合の注意点

テンプレートをdefault以外に変更されている場合、gulpfile.jsの値を変更する必要があります。

`eccube_root/gulpfile.js` 内の以下のdefaultの部分を変更してください

```js
const srcPattern = [
    'admin',
    'default' //defaultの部分を適用中のテンプレート名に変更
];
```

デザインテンプレートによっては、Sassの導入・格納場所が異なる場合がありますので、<br>
ご購入されたデザインテンプレートのマニュアルなどをご参照ください。



## スタイルガイドについて

EC-CUBEでは、CSSやHTMLの設計指針やコーディングルールを確認できるよう、 `スタイルガイド` を用意しています。
詳しくは以下を参照ください。

- [フロント画面のスタイルガイド](https://github.com/EC-CUBE/Eccube-Styleguide){:target="_blank"}
- [管理画面のスタイルガイド](https://github.com/EC-CUBE/Eccube-Styleguide-Admin){:target="_blank"}
