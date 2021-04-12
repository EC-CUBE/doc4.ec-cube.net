---
title: ec-cube.co ネーミングルール
keywords: co ec-cube.co クラウド版 ネーミングルール
tags: [co, ec-cube.co]
permalink: co/co_naming_rules
folder: co
---


---

※ [shopname]：申込みの際に指定したショップ名です。

| | | |
|-|-|-|
|リポジトリ名|eccube.co-[shopname]-customize||
|ブランチ|master->本番環境, develop->テスト環境|※ スタンダードプランのみ|
|namespace(本番環境)|[shopname]||
|namespace(テスト環境)|stg-[shopname]|※ スタンダードプランのみ|
|ショップURL(本番環境)|[shopname].ec-cuube.shop||
|ショップURL(テスト環境)|stg-[shopname].ec-cuube.shop|※ スタンダードプランのみ|
