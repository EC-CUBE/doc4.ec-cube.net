---
title: CSSの利用
keywords: design 
tags: [design]
permalink: design_css

summary: CSSの編集方法
---

## CSSの利用について

EC-CUBEでは、CSSを編集する方法。

1. [管理画面のCSS管理から編集する](#管理画面のCSS管理から編集する)
2. [style.cssを直に編集する](#style.cssを直に編集する)

トップページのメインビジュアルのスライドなど一部CSSはtwigファイル内に記述されています。<br>
EC-CUBE管理画面の[コンテンツ管理] -> [ページ管理]のTOPページで編集可能です。


## 管理画面のCSS管理から編集する

EC-CUBE管理画面の[コンテンツ管理] -> [CSS管理]からCSSを記述できます。

- [CSS管理]に記述したコードは、以下のディレクトリのcustomize.cssに反映されます。<br>

```
[html]
 └─ [user_data]
     └─ [assets]
         └─ [css]
             └─ customize.css
```
  
  管理画面からの編集（customize.css）の記述は、style.cssより優先されます。


## style.cssを直に編集する

EC-CUBEのCSSは、以下ディレクトリ内のstyle.cssにまとまっています。<br>
CSSメンテナンスの観点から、この後ご紹介するSassの利用もご検討ください。

- style.cssは、以下のディレクトリに格納されています。

```
[html]
 └─ [template]
     └─ [default]
            └─[assets]
                 ├─ [css]
                 │    ├─ style.css     # 読み込まれているCSS
                 │    └─ style.min.css # 軽量版CSS
                 └─ [sass]
                      ├─...
```


## スタイルガイドについて

EC-CUBEでは、CSSやHTMLの設計指針やコーディングルールを確認できるよう、 `スタイルガイド` を用意しています。
詳しくは以下を参照ください。

- [フロント画面のスタイルガイド](https://github.com/EC-CUBE/Eccube-Styleguide){:target="_blank"}
- [管理画面のスタイルガイド](https://github.com/EC-CUBE/Eccube-Styleguide-Admin){:target="_blank"}
