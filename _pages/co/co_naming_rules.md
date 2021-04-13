---
title: ec-cube.co ネーミングルール
keywords: co ec-cube.co クラウド版 ネーミングルール
tags: [co, ec-cube.co]
permalink: co/co_naming_rules
folder: co
---


---

※ [shopid]：申込みの際に指定したショップIDです(xxx.ec-cube.coのxxx部)。

| | | |
|-|-|-|
|リポジトリ名|eccube.co-[shopid]-customize||
|ブランチ|master->本番環境, develop->テスト環境|※ スタンダードプランのみ|
|namespace(本番環境)|[shopid]||
|namespace(テスト環境)|stg-[shopid]|※ スタンダードプランのみ|
|ショップURL(本番環境)|[shopid].ec-cuube.shop||
|ショップURL(テスト環境)|stg-[shopid].ec-cuube.shop|※ スタンダードプランのみ|
