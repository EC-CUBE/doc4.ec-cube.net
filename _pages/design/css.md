---
title: CSSの利用
keywords: design 
tags: [design]
permalink: design_css

summary: CSSの編集方法
description: EC-CUBEでCSSを編集する方法について説明します。
---

## CSSの利用について

EC-CUBEでCSSを編集する方法は以下の2つです。

1. [管理画面のCSS管理から編集する](#css_admin)
2. [style.cssを直に編集する](#css_style)

トップページのメインビジュアルのスライドなど一部CSSはtwigファイル内に記述されています。<br>
EC-CUBE管理画面の[コンテンツ管理] -> [ページ管理]のTOPページで編集可能です。


## 管理画面のCSS管理から編集する {#css_admin}

EC-CUBE管理画面の[コンテンツ管理] -> [CSS管理]からCSSを記述できます。

- [CSS管理]に記述したコードは、以下のディレクトリのcustomize.cssに反映されます。<br>

```text
[html]
 └─ [user_data]
     └─ [assets]
         └─ [css]
             └─ customize.css
```
  
  管理画面からの編集（customize.css）の記述は、style.cssより優先されます。


## style.cssを直に編集する {#css_style}

EC-CUBEのCSSは、以下ディレクトリ内のstyle.cssにまとまっています。<br>
CSSメンテナンスの観点から、この後ご紹介するSassの利用もご検討ください。

- style.cssは、以下のディレクトリに格納されています。

```text
[html]
 └─ [template]
     └─ [default]
            └─[assets]
                 ├─ [css]
                 │    ├─ style.css     # 読み込まれているCSS
                 │    └─ style.min.css # 軽量版CSS
                 └─ [scss]
                      ├─...
```


## スタイルガイドについて

EC-CUBEでは、CSSやHTMLの設計指針やコーディングルールを確認できるよう、 `スタイルガイド` を用意しています。
詳しくは以下を参照ください。

- [フロント画面のスタイルガイド](https://github.com/EC-CUBE/Eccube-Styleguide){:target="_blank"}
- [管理画面のスタイルガイド](https://github.com/EC-CUBE/Eccube-Styleguide-Admin){:target="_blank"}
