---
title: ec-cube.co アップデート指針
keywords: co ec-cube.co クラウド版 アップデート指針
tags: [co, ec-cube.co]
permalink: co/co_update_guidelines
folder: co
---


---

## 適用されるソースコード

ec-cube.co(4.0/4.1/4.2)で利用されているEC-CUBEのソースコードは [co/masterブランチ](https://github.com/EC-CUBE/ec-cube/tree/co/master){:target="_blank"}、[co/4.1ブランチ](https://github.com/EC-CUBE/ec-cube/tree/co/4.1){:target="_blank"} 、[co/4.2ブランチ](https://github.com/EC-CUBE/ec-cube/tree/co/4.2){:target="_blank"} で公開されています。  
[4.2ブランチ](https://github.com/EC-CUBE/ec-cube/tree/4.2){:target="_blank"} のHEADが [co/4.2ブランチ](https://github.com/EC-CUBE/ec-cube/tree/co/4.2){:target="_blank"} に適宜マージされ、週次メンテナンスのタイミングで取り込まれます。

現在ec-cube.co(4.2版)に適用されているEC-CUBEのソースコードは、[co/4.2-YYYYMMDD](https://github.com/EC-CUBE/ec-cube/tags){:target="_blank"} をご確認ください。
現在ec-cube.co(4.1版)に適用されているEC-CUBEのソースコードは、[co/4.1-YYYYMMDD](https://github.com/EC-CUBE/ec-cube/tags){:target="_blank"} をご確認ください。  
現在ec-cube.co(4.0版)に適用されているEC-CUBEのソースコードは、[co/YYYYMMDD](https://github.com/EC-CUBE/ec-cube/tags){:target="_blank"} をご確認ください。

## アップデート指針

EC-CUBEのPull Request取り込み基準と同様、互換性の保持を優先します。

Pull Request互換性保持チェックリスト

- 既存機能の仕様変更
- フックポイントの呼び出しタイミングの変更
- フックポイントのパラメータの削除・データ型の変更
- twigファイルに渡しているパラメータの削除・データ型の変更
- Serviceクラスの公開関数の、引数の削除・データ型の変更
- 入出力ファイル(CSVなど)のフォーマット変更

## 反映タイミング

週次メンテナンスのタイミングでアップデートが行われます。

- 週次メンテナンス(毎週木曜9:00〜10時、無停止でのメンテナンス)：EC-CUBEのアップデートを実施
- 月次メンテナンス(毎月木曜9:00〜10時、停止を伴う場合がある)：GKEノード等、インフラ・ミドルウェアの更新を実施
