---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/authZ_code_grant

---
## Authorization Code Flow での認可

### API クライアント の登録方法

1. プラグインを有効化すると、 **管理画面→設定→API管理→OAuth管理** に API クライアント一覧画面が表示されます。
1. **新規登録** ボタンをクリックすると、登録画面が表示されます。
1. **リダイレクトURI** に `https://<ec-cubeのホスト名>/` を入力します。
   - 上記の設定はテスト用の一例ですので、お使いの API クライアントにあわせて設定してください。
1. **登録** ボタンをクリックすると、API クライアントが登録されます。

### アクセストークンを発行する

ここでは、参考までに curl コマンドを使用して、アクセストークンを発行します。

1. 以下のURLにブラウザでアクセスします。`client_id` などの各項目は、前項で登録した API クライアントのものを使用してください。
  - `https://<ec-cubeのホスト名>/<管理画面URL>/authorize?response_type=code&client_id=client_id>&redirect_uri=<リダイレクトURI>&scope=<スコープ>&state=<ランダム文字列>`
  - ※スコープは、登録したスコープのうち、要求したいスコープを空白区切りで設定してください
    ```
      readのみを要求：scope=read
      read/writeを要求：scope=read write
      ※mutationを行う場合、read/writeの要求が必要です。
    ```
1. 管理画面のログイン画面が表示されますので、ログイン(認証)します。
1. API クライアントの認可画面が表示されますので、内容を確認し、「許可」をクリックします。
1. リダイレクトURIにリダイレクトされますので、 `code` の値をコピーしておきます。
1. curl コマンドでトークンエンドポイントにアクセスします。
  - Authorization code の有効期限内(10分)にアクセスしてください。
  - `client_id` などの各項目は、前項で登録した API クライアントのものを使用してください。
    ```sh
    curl --location --request POST 'https://<ec-cubeのホスト名>/token' \
        --header 'Content-Type: application/x-www-form-urlencoded' \
        --data-urlencode 'grant_type=authorization_code' \
        --data-urlencode 'client_id=<クライアントID>' \
        --data-urlencode 'client_secret=<クライアントシークレット>' \
        --data-urlencode 'redirect_uri=<リダイレクトURL>' \
        --data-urlencode 'code=<4 で取得した code の値>'
    ```
1. API クライアントが認可されると、以下のようなアクセストークンが発行されます。
    ```json
    {
      "token_type": "Bearer",
      "expires_in": 3600,
      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImIwODc0MmY1NzJjZjM3OGFiMjk5NmIwYWVjOTFhODNiMWUwZWVlMTg4OTEyMDUxN2Y0ZWVjMmJhNDVkNTFhMzdkOGU4MDNiNDQ2ZmEyOTU4In0.eyJhdWQiOiJlYmQ3NDZlM2E0MjcxNGE2M2YyYjI0N2JmOWI0MjUwNiIsImp0aSI6ImIwODc0MmY1NzJjZjM3OGFiMjk5NmIwYWVjOTFhODNiMWUwZWVlMTg4OTEyMDUxN2Y0ZWVjMmJhNDVkNTFhMzdkOGU4MDNiNDQ2ZmEyOTU4IiwiaWF0IjoxNTgyNzc5NzM4LCJuYmYiOjE1ODI3Nzk3MzgsImV4cCI6MTU4Mjc4MzMzOCwic3ViIjoiYWRtaW4iLCJzY29wZXMiOlsicmVhZCJdfQ.gTbfzr2nzy-wUmYleXlFq1gs-wN7oH8l5nfEsfq5QAZjkl4K4__uLlh2GpStmjveYqY-rxP7Qy7mUBXgIFH3tl0Tnwg52GH9_ftAhz6ZzPilymUzuXtmJ3aj4GZ4Ctm62GtcM1psbzP444BnY9QYuejpQT3tV0VS1enrV8ZkAPKkWvCcOyGLCabfPZ626rThMxMC0I7Mwc-sHAooZ4ebsDUbAQnXj2mwc5zWUpv7r8AsLXnUaMZoAXSk9NQnZvqn7VXo4EkQJkdArT0_QPoPZvFxHrAI5lMWTojMXCZyQMH6cV4OllKHqluij16NA9dGjG73kETyhzbgsm-8e0Hxww",
      "refresh_token": "def502003895e9eb8526f5160b756233895390044561b7de0d67a7a5ae1cef188ed549d95184fb3c824b50e8c6afbe065336e5c18691a750793d1dc8b3d4176f536b1dad6f5c38585133ef0ae44ceac721c65b33b9a8f78c40662112c548acfb3cd4da8b0733c79ac68c22c560f39ef5b4edb33f2ed52579608a3d80559eec37874637afd60a37f53ed5902bc869cca0ce15e09028fccfc27fd60bae8f28b9a98ce068b53b4094d19de000823f6955f9d80b2925e1340932166bdf5014fc083f9b858d7dd39b7707242eb1465b989e65160748fffef0074bc151bc95c59a6134102b18b0349c86e86035632fb235cecd23528f91ad79a599a7186aaad2c7ddca4884401a679212a757beee84f8bb6f05b03d29542091134fd0a41f2356d84726addf03546f383ac93b861bf01a2a1358a94ea856c9a16242c0c896a6feb76e1aa097b4156883368529eae157c46849c4e62e983976d625916f20280004179af27f609dbd7411d7cf25fec08acc9ee60dfe8efb8d7123f30be9c1c05d5ee362a0c70630de7dc0e0d6dbf9b223"
    }
    ```

