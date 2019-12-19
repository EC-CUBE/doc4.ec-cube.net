---
title: デザインテンプレートの基礎
keywords: design template search
tags: [design]
permalink: design_template
summary: デザインテンプレートの基本ルールについて説明します。
---

## デフォルトのテンプレートファイルの配置ディレクトリ

本体の標準のTwigファイルは以下のディレクトリに配置されています。

```
.
└── src
    └── Eccube
        └── Resource
            └── template             # デフォルトのテンプレートの配置ディレクトリ
                ├── admin            ## 「管理」画面に関するテンプレート
                ├── default          ## 「フロント」画面に関するテンプレート
                └── install          ## 「インストール」画面に関するテンプレート
```

## カスタマイズしたテンプレートファイルの配置ディレクトリ

**デザインをカスタマイズする場合、デフォルトのテンプレートファイルの変更を推奨しません。**  
( バージョンアップ時に上書きされ、変更が戻ってしまうことがあるため。 )

カスタマイズしたテンプレートファイルは以下のディレクトリに配置してください。  
これらのテンプレートファイルは、デフォルトのテンプレートファイルに優先して利用されます。( **後述** )  

- `[template_code]` とは
    - 「フロント」画面のテンプレートファイル配置ディレクトリです。
    - インストール直後は `default` という値になっています。
    - 管理画面より、任意の値を選択可能です。


```
.
└── app
     └── template                # カスタマイズしたテンプレートの配置ディレクトリ
         ├── admin               ## 「管理」画面に関するテンプレート
         └── [template_code]     ## 「フロント」画面に関するテンプレート
```

## テンプレートの読み出し順序

テンプレートファイルが呼び出される順序は、以下の通りです。

- 「フロント」画面

```
.
├── app
│   └── template
│       ├── admin
│       └── [template_code] (2)
└── src
    └── Eccube
        └── Resource
            └── template
                ├── admin
                └── default  (1)
```

- 「管理」画面

```
.
├── app
│   └── template
│       ├── admin           (2)
│       └── [template_code]
└── src
    └── Eccube
        └── Resource
            └── template
                ├── admin    (1)
                └── default
```

先にカスタマイズしたテンプレートが存在するのか確認し、存在しなければデフォルトのテンプレートを呼び出します。

### 読み出し順序の例

* フロントの例
template_codeが「MyDesign」のデザインテンプレートを利用しており、Controllerで `@Template("TemplateDir/template_name.twig")` とアノテーション定義されている場合

```
 1. app/template/MyDesign/TemplateDir/template_name.twig
 2. src/Eccube/Resource/template/default/TemplateDir/template_name.twig
 3. app/Plugin/[plugin_code]/Resource/template/TemplateDir/template_name.twig
```

```
.
├── app
│   ├── Plugin
│   │   └── [plugin_code]
│   │       └── Resource
│   │           └── template
│   │               └── TemplateDir
│   │                   └── template_name.twig     (3)
│   └── template
│       └── MyDesign
│           └── TemplateDir
│               └── template_name.twig              (1)
└── src
    └── Eccube
        └── Resource
            └── template
                └── default
                    └── TemplateDir
                        └── template_name.twig       (2)

```
という順番で表示されます。

* 管理画面の例  
`@Template("@admin/Product/index.twig")` 商品マスターのテンプレートをapp/template/admin配下に配置した場合、以下の順序で呼び出されます。

```
 1. app/template/admin/Product/index.twig
 2. src/Eccube/Resource/template/admin/Product/index.twig
 3. app/Plugin/[plugin_code]/Resource/template/admin/Product/index.twig
```

```
.
├── app
│   ├── Plugin
│   │   └── [plugin_code]
│   │       └── Resource
│   │           └── template
│   │               └── admin
│   │                   └── Product
│   │                       └── index.twig     (3)
│   └── template
│       └── admin
│           └── Product
│               └── index.twig                  (1)
└── src
    └── Eccube
        └── Resource
            └── template
                └── admin
                    └── Product
                        └── index.twig           (2)

```

## 管理画面からデザイン編集した時のテンプレートファイルの挙動(ページ編集、ブロック編集)

* ページ詳細  
デフォルトの場合、 `ECCUBEROOT/src/Eccube/Resource/template/default` 配下の該当するファイルが表示されます。  
ページ詳細で修正を行った場合、新たに `ECCUBEROOT/app/template/default/` 配下に保存され、以降は `ECCUBEROOT/app/template/default/` 配下のファイルを修正するようになります。

* ブロック編集  
デフォルトの場合、 `ECCUBEROOT/src/Eccube/Resource/template/default/Block` 配下の該当するファイルが表示されます。  
ブロックを新規登録したり編集されたりすると、 `ECCUBEROOT/app/template/default/Block` 配下に保存され、以降は `ECCUBEROOT/app/template/default/Block` 配下のファイルを修正するようになります。
