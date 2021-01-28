---
title: ウェブアプリケーション侵入テストの構成
permalink: /penetration-testing/introduction/layout
---
docker-compose を使用し、 EC-CUBE と Docker 版 OWASP ZAP を連携します。
Docker 版 OWASP ZAP では、 Webswing 経由で OWASP ZAP の管理画面へアクセスします。

![Webswing](/images/penetration-testing/introduction_layout_webswing.png)

環境に依存しにくく、 docker-compose で基本的な設定を準備できるため、誰でも同じ環境を構築しやすいのがメリットです。

セッションを保存しておけば、後から Windows 版や Mac 版など、スタンドアロン版の OWASP ZAP で保存したセッションを開いて、スキャン結果を確認することが可能です。

## docker-compose の構成

![docker-compose の構成](https://www.plantuml.com/plantuml/proxy?fmt=svg&src=https://raw.githubusercontent.com/EC-CUBE/doc4.ec-cube.net/master/uml/introduction_layout.puml)

### メリット

- ローカル環境で完結するため、外部に迷惑をかけることなく安全
  - 動的スキャンではターゲットに対して実際に攻撃を仕掛ける。プロテクトモードでは、攻撃が外部に漏れることは無い
  - ただし、標準モード、攻撃モードを使用した場合は、外部サービスにリクエストするため注意
- 実際に脆弱性を再現させてテストしても外部に漏れることなく安全
- docker-compose コマンドのみで初期設定が完了するため、構築が簡単
- 誰がやっても同じような環境を構築しやすい

### デメリット

- 実際に運用しているWebサーバーの設定など、運用環境に関するテストはできない
- 動作中は、セッション情報など数十GBのディスク容量を消費するため、リソースの少ないローカル環境では厳しい
- スタンドアロン版の OWASP ZAP に比べて動作が遅い

