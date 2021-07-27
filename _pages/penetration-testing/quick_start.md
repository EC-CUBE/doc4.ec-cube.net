---
title: Quick Start
permalink: /penetration-testing/quick_start
---

**Attention!** 意図しない外部サイトへの攻撃を防ぐため、 OWASP ZAP は必ず **プロテクトモード** で使用してください
![プロテクトモードを使用すること](/images/penetration-testing/quick_start_protect_mode.png)
{: .notice--danger}

1. [docker-compose を使用して EC-CUBE をインストールします](https://doc4.ec-cube.net/quickstart/docker_compose_install)
1. テスト用のデータを生成しておきます
    ```shell
    ## APP_ENV: dev に設定
    sed -i.bak -e 's/APP_ENV: "prod"/APP_ENV: "dev"/g' ./docker-compose.yml
    docker-compose up -d # 変更を反映
    ## customer を1件生成
    docker-compose -f docker-compose.yml -f docker-compose.owaspzap.yml exec ec-cube bin/console eccube:fixtures:generate --products=0 --customers=1 --orders=0
    ## メールアドレスを zap_user@example.com に変更
    docker-compose -f docker-compose.yml -f docker-compose.owaspzap.yml exec ec-cube bin/console doctrine:query:sql "UPDATE dtb_customer SET email = 'zap_user@example.com' WHERE id = 1;"
    ## ZAP でテストする場合は APP_ENV: prod に設定しておく
    sed -i.bak -e 's/APP_ENV: "dev"/APP_ENV: "prod"/g' ./docker-compose.yml
    docker-compose up -d # 変更を反映
    ```
1. OWASP ZAP コンテナを起動します
    ```shell
    docker-compose -f docker-compose.yml -f docker-compose.owaspzap.yml up -d zap
    ```
    - *アドオンをアップデートするため、少し時間がかかります*
    - 起動してから、 Firefox 以外のブラウザで `http://localhost:8081/zap/` へアクセスすると、OWASP ZAP の管理画面が表示されます

    **Note:** OWASP ZAP の起動直後は、画面表示が小さく、一部のツールバーボタンが隠れています。全画面表示にすると使いやすいです。
    ![全画面表示](/images/penetration-testing/quick_start_fullwindow.png)
    {: .notice--info}
1. Firefox を起動し、設定→ネットワーク設定→接続設定からプロキシーの設定をします
   - **手動でプロキシーを設定する** を選択
   - HTTPプロキシー: localhost, ポート: 8090
   - **このプロキシーを FTP と HTTPS でも使用する** にチェックを入れる
1. Firefox に SSL ルート CA 証明書をインポートします
   - ローカルの `path/to/ec-cube/zap/owasp_zap_root_ca.cer` に証明書が生成されています
   - 設定→プライバシーとセキュリティ→証明書→証明書を表示から証明書マネージャーを表示
   - 認証局証明書→読み込むをクリックし、 `path/to/ec-cube/zap/owasp_zap_root_ca.cer` を選択
   - **この認証局によるウェブサイトの識別を信頼する** にチェックを入れ、 OK をクリック、設定を閉じます
1. Firefox で `https://ec-cube/` へアクセスし、プロキシー経由で EC-CUBE にアクセスできるのを確認します。
1. コンテキストをインポートします。
    ```shell
    ## 管理画面用
    docker-compose -f docker-compose.yml -f docker-compose.owaspzap.yml exec zap zap-cli -p 8090 context import /zap/wrk/admin.context
    ## フロント(ログイン用)
    docker-compose -f docker-compose.yml -f docker-compose.owaspzap.yml exec zap zap-cli -p 8090 context import /zap/wrk/front_login.context
    ## フロント(ゲスト用)
    docker-compose -f docker-compose.yml -f docker-compose.owaspzap.yml exec zap zap-cli -p 8090 context import /zap/wrk/front_guest.context
    ```
   **Note:** *複数のコンテキストを同時にインポートすると、セッションが競合してログインできなくなる場合があるため注意*
   {: .notice--warning}
1. OWASP ZAP のツールバーにある [Forced User Mode On/Off ボタン](https://www.zaproxy.org/docs/desktop/ui/tltoolbar/#--forced-user-mode-on--off) を ON にすると、OWASP ZAP の自動ログインが有効になり、ユーザーログイン中のテストが有効になります
   ![Forced User Mode On/Off ボタン](/images/penetration-testing/quick_start_forceusermode.png)
1. テストを実施します
   1. Firefox でページを巡回(手動探索)します
   1. 手動探索して検出された URL に対して動的スキャンを実施します
   1. アラートの検出を確認します


## 参考

- [DockerでOWASP ZAPを使う](https://pc.atsuhiro-me.net/entry/2019/08/19/011324)
- [Docker版OWASP ZAPを動かしてみる](https://qiita.com/koujimatsuda11/items/83558cd62c20141ebdda)
- [テスティングガイド](https://owasp.org/www-pdf-archive/OTGv3Japanese.pdf)
