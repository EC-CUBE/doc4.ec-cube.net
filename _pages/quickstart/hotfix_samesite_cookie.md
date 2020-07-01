---
layout: single
title: SameSite Cookie 対応
keywords: samesite
tags: [quickstart, getting_started]
permalink: hotfix_samesite_cookie
summary : EC-CUBE4系でのSameSite Cookieの暫定対応方法について
---

2020/06/17 EC-CUBE 4.0.4 で SameSite Cookie の新仕様に対応しましたのでページを更新しました。

## 概要

2020年7月14日から Chrome 80 以降のバージョンで、他サイトからEC-CUBEで構築されたサイトに遷移する場合に、条件によってはCookieが送信されなくなり、決済が完了しない等の現象が発生する可能性があります。
EC-CUBE 4.0.4 でこちらの仕様に対応しましたので、 EC-CUBE 4.0.3 以下をご利用中の方はアップデートをお願いします。
Hot-fixパッチを適用済みの場合も EC-CUBE 4.0.4 へアップデートしていただくことで対応が可能ですが、アップデート時に不要なファイルが残ってしまわないようにご注意ください。

### 関連情報
- [Google Developers Japan: 新しい Cookie 設定 SameSite=None; Secure の準備を始めましょう](https://developers-jp.googleblog.com/2019/11/cookie-samesitenone-secure.html)
- [SameSite Updates - The Chromium Projects](https://www.chromium.org/updates/same-site)
- [Chrome80のSameSiteの影響で3Dセキュア等を利用する場合に購入に失敗する #4457](https://github.com/EC-CUBE/ec-cube/issues/4457)

## Hot-fixパッチを手動で切り戻す場合

1.  `app/config/eccube/packages/framework.yaml` の以下の変更を切り戻す
    - [https://github.com/EC-CUBE/ec-cube/commit/93c120dbd4dc34f6d9883b6336fb3c31f013ae54](https://github.com/EC-CUBE/ec-cube/commit/93c120dbd4dc34f6d9883b6336fb3c31f013ae54)
1. 以下の2ファイルを削除する
    - [src/Eccube/EventListener/SameSiteCookieHotfixListener.php](https://raw.githubusercontent.com/kiy0taka/ec-cube/2ef44a5e5e0741abfd2f04a259a05b315bd07808/src/Eccube/EventListener/SameSiteCookieHotfixListener.php)
    - [src/Eccube/Resource/template/default/error_samesite.twig](https://raw.githubusercontent.com/kiy0taka/ec-cube/2ef44a5e5e0741abfd2f04a259a05b315bd07808/src/Eccube/Resource/template/default/error_samesite.twig)
1. 以下のいずれかの方法でキャッシュをクリアする
    - コマンドからキャッシュをクリアする
        ```
        $ bin/console cache:clear --no-warmup
        ```
    - 管理画面の「コンテンツ管理」-「キャッシュ管理」画面にて「キャッシュ削除」を実行する
