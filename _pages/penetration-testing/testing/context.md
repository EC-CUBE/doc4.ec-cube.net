---
title: コンテキストの分類
permalink: /penetration-testing/testing/context
---
OWASP ZAP には、ログインが必要なページをテストするための自動ログイン機能があり、認証パターンをコンテキストごとに設定できます。
このため、コンテキストは HTTP Session の利用方法に応じて、3種類に分けます。

- 管理画面
- フロント(ゲスト)
- フロント(ログイン)

**Note:** *これらのコンテキストを複数同時に使用すると、セッションが競合してログインできなくなる場合があるため注意してください*
{: .notice--warning}

コンテキストごとに、以下のような設定ができます。

- テスト対象のURL設定
- テストを除外するURL設定
- クエリストリングや、POST の接続子(& や = など)設定
- 利用している OS やミドルウェアの設定
- 認証パターンの設定
- 認証ユーザーの設定
- HTTP Session の管理方法の設定
- アラートフィルターの設定

コンテキストの種類に応じて、上記を設定済みのファイルを用意していますので、適宜インポートして利用してください。

```shell
## 管理画面用
docker-compose -f docker-compose.yml -f docker-compose-owaspzap.yml exec zap zap-cli -p 8090 context import /zap/wrk/admin.context
## フロント(ログイン用)
docker-compose -f docker-compose.yml -f docker-compose-owaspzap.yml exec zap zap-cli -p 8090 context import /zap/wrk/front_login.context
## フロント(ゲスト用)
docker-compose -f docker-compose.yml -f docker-compose-owaspzap.yml exec zap zap-cli -p 8090 context import /zap/wrk/front_guest.context
```

このコンテキストごとにテストを実施し、それぞれテスト結果のレポートを評価します。

**Note:** コンテキストごとに、OWASP ZAP のセッションを分けて保存しておくと便利です。
{: .notice--info}
