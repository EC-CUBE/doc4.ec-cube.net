---
title: ec-cube.co Git管理機能の仕様
keywords: co ec-cube.co クラウド版 Git管理機能の仕様
tags: [co, ec-cube.co]
permalink: co/co_git
folder: co
---


---

スタンダードプランの場合のGit管理機能の仕様は以下になります。

|                                               |Git管理対象   |管理画面<br>Plugin利用による影響 |管理画面からの操作 |
|-----------------------------------------------|-------------|----------------------------|----------------|
| \|\-\- app                                  　|             |                            |                |
| \|&emsp;&emsp;\|\-\- Customize                | o           |                            |                |
| \|&emsp;&emsp;\|\-\- DoctrineMigrations       | x           |                            |                |
| \|&emsp;&emsp;\|\-\- Plugin                   | x           | o                          |                |
| \|&emsp;&emsp;\|\-\- PluginData               | x           | o                          |                |
| \|&emsp;&emsp;\|\-\- config                   | x           |                            |                |
| \|&emsp;&emsp;\|\-\- proxy                    | x           |                            |                |
| \|&emsp;&emsp;`\-\- template                  | o           | o                          | o               |
| `\-\- html                                    |             |                            |                |
|&emsp;&emsp;\|\-\- plugin            　　　　   |  o          | o                          |               |
|&emsp;&emsp;\|\-\- template           　　　　  |  o          | o                          | o              |
|&emsp;&emsp;\|\-\- upload             　　　 　  |  x       　 |                            |                |
|&emsp;&emsp;\|&emsp;&emsp;\|\-\- save_image    |  x          |                            |  o              |
|&emsp;&emsp;\|&emsp;&emsp;`\-\- temp_image     |  x          |                            |  o              |
|&emsp;&emsp;`\-\- user_data        　　　　　    |  x          |                            | o              |
|&emsp;&emsp;&emsp;&emsp;`-- assets     　      |  x          |                            | o              |

## 表の見方 

1. Git管理対象：Gitによるソースコードやディレクトリの反映が可能なリソース
1. Plugin利用による影響：<br>PluginのInstall/UnInstall/enable/disable/実行時の操作、などによりソースコードが書き換えられる可能性のあるリソース
1. 管理画面からの操作：管理画面でなんからの更新が行われるリソース（ページ/テンプレート管理など）

以下はEC-CUBE本体コードとしてのディレクトリであるため対象外

- DoctrineMigrations
- config
- proxy

「Plugin利用による影響」「管理画面からの操作」について  

- gitからの反映以外の操作により生成・変更されたファイルもgit管理されます。
- その際、管理画面側の変更が正としてcommitされます。
