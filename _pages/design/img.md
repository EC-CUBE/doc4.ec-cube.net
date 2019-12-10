---
title: 画像管理について
keywords: design img customize
tags: [design]
permalink: design_img
summary: 画像管理や呼び出し方などの説明
---

## 画像について
デザインカスタマイズの際に、画像を追加したい場合<br>
ディレクトリに直接追加する方法と、管理画面からアップロードする方法があります。

## ディレクトリに直接追加する方法

### 画像ディレクトリのパス
`ECCUBEROOT/html/template/default/assets/img/`<br>
※EC-CUBEがインストールされているディレクトリを ECCUBEROOT とします。

デザインテンプレートを反映していた際には、<br>
default部分以下ディレクトリがデザインテンプレート固有のディレクトリとなります。<br>

例）商品番号XX4001というデザインテンプレートを適用した場合<br>
`ECCUBEROOT/html/template/XX4001/assets/img/`


## 画像へのリンク方法
画像を呼び出したい場合は、twig形式で以下の様に書きます。
```twig
{% raw %}{{ asset('assets/img/ディレクトリ名/画像名') }}{% endraw %}
```

例）topディレクトリの中のhoge.jpgを表示したい場合
```twig
{% raw %}<img src="{{ asset('assets/img/top/hoge.jpg') }}" alt="hoge">{% endraw %}
```

以下の書き方も可能です。※ただしデザインテンプレートを切り替えた際に表示されなくなります。
```html
<img src="html/template/defult/assets/img/top/hoge.jpg" alt="hoge">
```

## 管理画面から画像追加する方法
ec-cube.coやサーバーにアップロードする方法が難しい場合でも、<br>管理画面から画像ファイルを追加する事が可能です。

### 画像のアップロード
[コンテンツ管理] -> [ファイル管理]からアップロード可能です。

画像以外のファイルもアップロードが可能となるので、<br>
アップロードする際にはassetsフォルダ内にimgフォルダを追加することをお勧めします。

![フォルダを追加してimgフォルダを表示した図](./images/design/design-img-01.png)

ファイルを追加より、ファイル選択しアップロードボタンを押すと追加が可能です。<br>
※ファイル選択時に、複数選択（ShiftキーやCtrlキー）すると、複数を同時にアップロードが可能です。

![ファイルを追加した図](./images/design/design-img-02.png)



### 管理画面からアップした画像ディレクトリのパス
アップロードした画像は、user_data内に格納されます。<br>
`ECCUBEROOT/html/user_data/assets/作成したフォルダ/アップした画像`<br>
※EC-CUBEがインストールされているディレクトリを ECCUBEROOT とします。

### 管理画面からアップした画像へのリンク方法
アップロードした画像は、ファイル一覧に追加されています。<br>
一覧の![ファイルを追加した図](./images/design/design-img-04.png)コピーアイコンをクリックすることで、画像へのリンクが表示され、同時にコピーされた状態となります。

![ファイルを追加した図](./images/design/design-img-03.png)


例）imgフォルダ内のhoge.pngを表示したい場合
```html
<img src="/html/user_data/assets/img/hoge.png" alt="hoge">
```

以下の書き方も可能です。
```twig
{% raw %}<img src="{{ asset('assets/img/hoge.png'),'user_data' }}" alt="hoge">{% endraw %}
```


## 【番外】商品管理>商品登録よりアップした画像について
管理画面より、商品登録の際にアップした画像については、また別のディレクトリに保存されます。<br>
こちらについては、直接追加することはお勧めできません。

### 商品登録からアップした画像ディレクトリのパス
`ECCUBEROOT/html/upload/saveimage/`<br>
※EC-CUBEがインストールされているディレクトリを ECCUBEROOT とします。

### 商品登録からアップした画像へのリンク方法
画像を呼び出したい場合は、twig形式で以下の様に書きます。
```twig
{% raw %}{{ asset('画像名', 'save_image') }}{% endraw %}
```
商品登録時にアップした画像は名前が変更されるので、商品詳細ページにて表示された画像名をソースコードより確認ください。

例）商品画像のhoge-1.jpgを表示したい場合
```twig
{% raw %}<img src="{{ asset('hoge-1.jpg', 'save_image') }}" alt="hoge">{% endraw %}
```