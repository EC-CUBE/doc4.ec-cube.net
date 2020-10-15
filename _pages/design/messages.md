---
title: メッセージのカスタマイズ
keywords: design
tags: [design]
permalink: design_messages
description: メッセージのカスタマイズについて説明します。
---

## メッセージファイルの配置場所

- メッセージファイルの標準ディレクトリ  
`src/Eccube/Resource/locale/`

項目名やエラーメッセージは以下のファイルに集約されています。

- 日本語  
  `src/Eccube/Resource/locale/messages.ja.yaml`  
  `src/Eccube/Resource/locale/validators.ja.yaml` 

- 英語  
  `src/Eccube/Resource/locale/messages.en.yaml`  
  `src/Eccube/Resource/locale/validators.en.yaml`  

## メッセージカスタマイズ時のファイル配置

- メッセージファイルのカスタマイズ時ディレクトリ  
`app/Customize/Resource/locale/` 

このディレクトリはメッセージ定義を利用するときに適用されるためのディレクトリとなります。  
メッセージ定義はこのディレクトリ配下に保存されています。   
変更・追加したいメッセージに応じて以下のようなファイルに追加します。  

- 日本語  
  `app/Customize/Resource/locale/messages.ja.yaml`  
  `app/Customize/Resource/locale/validators.ja.yaml`   

- 英語  
  `app/Customize/Resource/locale/messages.en.yaml`    
  `app/Customize/Resource/locale/validators.en.yaml`  

 


## メッセージの変更・追加

メッセージファイルはハッシュのyamlファイルです。 ハッシュのキーはメッセージのID、値は翻訳された文字列を表します。  
以下は`src/Eccube/Resource/locale/messages.ja.yaml`の一部です。

```
#====================================================================================
# 共通
#====================================================================================

common.select: 選択してください
common.select__pref: 都道府県を選択
common.select__unspecified: 指定なし
common.select__all_products: 全ての商品

```  

メッセージの変更・追加したい場合は`app/Customize/Resource/locale/messages.ja.yaml`に変更・追加分を以下のように記述します。


```
#====================================================================================
# 変更
#====================================================================================
common.select: [上書きする文字列]

#====================================================================================
# 追加
#====================================================================================
[新しいメッセージのID]: [定義したい文字列]


``` 




### 4.0.3以前のバージョンについて

4.0.3以前の場合`app/Customize/Resource/locale/`のpathが追加されてないため  
`app/config/eccube/packages/translation.yaml`ファイルには以下のように1行追加する必要があります。

```
framework:
    default_locale: '%locale%'
    translator:
        paths:
            - '%kernel.project_dir%/src/Eccube/Resource/locale/'
            - '%kernel.project_dir%/app/Customize/Resource/locale/'
        fallbacks:
            - '%locale%'
```


