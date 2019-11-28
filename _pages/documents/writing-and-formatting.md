---
title: ドキュメントの追加・書き方
description: EC-CUBE4系開発ドキュメントにページを追加する場合の、markdownの書き方について説明します。
tags: [howto]
permalink: documents/writing-and-formatting
---

## ページの追加方法

[Jekyll](http://jekyllrb-ja.github.io/){:target="_blank"}を利用してページの自動生成を行っています。  
[開発ドキュメントのソースコード](https://github.com/EC-CUBE/doc4.ec-cube.net/){:target="_blank"}にある `_pages` ディレクトリの中にmarkdownファイル(.md)を置くと、自動的にページが追加されます。

### markdownヘッダー部分の書き方

ページ生成時に利用するヘッダー情報です。各ページに必ず記載する必要があります。

```yaml
---
title: テストページ
description: xxxxを行う場合のツール導入方法と実装についてのポイントをまとめました。
permalink: test
tags: [quickstart, install]
---
```

`title` ：h1タグのコンテンツになります。  
`description` ：シェアした場合に `og:description` になります。  
`permalink` ：ページのURLになります。hoge/testのような書き方も可能。  
`tags` ：タグ付けができます。

### 左のメニューに追加する場合

`_data/navigation.yml` に追加して下さい。小カテゴリの階層は第２階層まで作ることができます。

```yaml
docs:
  - title: 大カテゴリ
    output: web, pdf
    children:
      - title: コンテンツ
        url: /quickstart_requirement
        output: web, pdf
      - title: 小カテゴリ
        output: web, pdf
        sub_items:
        - title: コンテンツ
          url: /quickstart_update
          output: web, pdf
```

## ページの書き方

- markdownで記載して下さい。
- 改行(brタグ)は行末に改行２つ必要です。
- 外部へのハイパーリンクの場合は `{:target="_blank"}` を記述してください。
- 「```php」と書くことでシンタックスハイライトを指定することができます。
- テンプレートで用意されているUtility/Helpersを利用することができます。
    - [Utility](https://mmistakes.github.io/minimal-mistakes/docs/utility-classes/){:target="_blank"}
    - [Helpers](https://mmistakes.github.io/minimal-mistakes/docs/helpers/){:target="_blank"}

### Utilityサンプル

{: .notice--info}
補足情報などを囲って読みやすくすることができます。

[ボタンリンクも作れます](#link){: .btn .btn--primary}
