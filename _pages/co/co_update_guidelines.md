---
title: ec-cube.co アップデート指針
keywords: co ec-cube.co クラウド版 アップデート指針
tags: [co, ec-cube.co]
permalink: co/co_update_guidelines
folder: co
---


---

## coアップデート指針

- [co/masterブランチ](https://github.com/EC-CUBE/ec-cube/tree/co/master){:target="_blank"}でソースコード管理されています。
- ec-cubeのpr取り込み基準と同様です。
  - 既存機能の仕様変更
  - フックポイントの呼び出しタイミングの変更
  - フックポイントのパラメータの削除・データ型の変更
  - twigファイルに渡しているパラメータの削除・データ型の変更
  - Serviceクラスの公開関数の、引数の削除・データ型の変更
  - 入出力ファイル(CSVなど)のフォーマット変更
- メンテナンスのスケジュールは以下を予定しております。
    - 週次メンテナンス(週1木曜9:00〜10時、無停止)：EC-CUBEのアップデートの取り込み
    - 月次メンテナンス(月1木曜9:00〜10時、停止を伴う場合がある)：GKEノード等、インフラ・ミドルウェア更新)