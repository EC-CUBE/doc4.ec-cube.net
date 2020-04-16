---
layout: single
title: SameSite Cookie 対応
keywords: samesite
tags: [quickstart, getting_started]
permalink: hotfix_samesite_cookie
summary : EC-CUBE4系でのSameSite Cookieの暫定対応方法について
---


## 概要
- 2020年2月にリリースされた Chrome 80 より、他サイトからEC-CUBEで構築されたサイトに遷移する場合に、条件によってはEC-CUBEのCookieが送信されなくなり、決済が完了しない等の現象が発生します。この問題に対応するために以下のHot-fixパッチを適用して頂く必要があります。

### 関連情報
- [Google Developers Japan: 新しい Cookie 設定 SameSite=None; Secure の準備を始めましょう](https://developers-jp.googleblog.com/2019/11/cookie-samesitenone-secure.html)
- [SameSite Updates - The Chromium Projects](https://www.chromium.org/updates/same-site)
- [Chrome80のSameSiteの影響で3Dセキュア等を利用する場合に購入に失敗する #4457](https://github.com/EC-CUBE/ec-cube/issues/4457)

## Hot-fixパッチ

### __注意事項__
- SSL未対応のサイトではこのパッチを適用すると正しく動作しなくなります。SSL未対応のサイトはSSLに対応後、このパッチを適用してください。
- ローカル開発環境ではこのパッチを適用しないことをおすすめします。適用する場合はローカル環境でもSSLに対応させる必要があります。
- 今後、SameSite Cookieの設定を環境変数等で設定できるように検討していきます。詳しくは、[こちらのIssue #4457](https://github.com/EC-CUBE/ec-cube/issues/4457) を参照ください。

### 修正内容
このパッチにより以下の修正が適用されます。

- 他サイトからPOSTされたときにもセッションキーを保持したCookieが送信されるように セッションキーのCookieに `SammeSite=None` を設定する。
- `SammeSite=None` を設定したCookieを正しく扱えない一部のブラウザーでは決済を利用できないため、以下のようなバージョンアップ促すエラー画面を表示する。


    ![SammeSite=Noneサポート外のブラウザー対応画面](./images/hotfix_samesite_error_page.png)

### 適用方法

1.  `app/config/eccube/packages/framework.yaml` に以下の変更を適用する
    - [https://github.com/EC-CUBE/ec-cube/commit/93c120dbd4dc34f6d9883b6336fb3c31f013ae54](https://github.com/EC-CUBE/ec-cube/commit/93c120dbd4dc34f6d9883b6336fb3c31f013ae54)
1. 以下の2ファイルを配置する
    - [src/Eccube/EventListener/SameSiteCookieHotfixListener.php](https://raw.githubusercontent.com/kiy0taka/ec-cube/2ef44a5e5e0741abfd2f04a259a05b315bd07808/src/Eccube/EventListener/SameSiteCookieHotfixListener.php)
    - [src/Eccube/Resource/template/default/error_samesite.twig](https://raw.githubusercontent.com/kiy0taka/ec-cube/2ef44a5e5e0741abfd2f04a259a05b315bd07808/src/Eccube/Resource/template/default/error_samesite.twig)
1. 以下のいずれかの方法でキャッシュをクリアする
    - コマンドからキャッシュをクリアする
        ```
        $ bin/console cache:clear --no-warmup
        ```
    - 管理画面の「コンテンツ管理」-「キャッシュ管理」画面にて「キャッシュ削除」を実行する
