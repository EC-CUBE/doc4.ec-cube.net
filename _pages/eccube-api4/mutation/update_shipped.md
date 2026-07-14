---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/mutation/update_shipped

---

## 出荷ステータスの更新

UpdateShipped で出荷処理ができます。
出荷処理と同時に、出荷日、出荷業者、お問い合わせ番号、出荷用メモ欄の更新と、出荷完了メールの送信が可能です。
受注に紐づく全ての出荷情報が出荷済みになると受注ステータスが出荷済みになります。

### スキーマ

```graphql
type Mutation {
  updateShipped(
    """出荷ID"""
    id: ID!

    """出荷日（ Y-m-d\TH:i:sP 形式で指定可能、未指定の場合は実行日時）"""
    shipping_date: DateTime

    """出荷業者"""
    shipping_delivery_name: String

    """お問い合わせ番号"""
    tracking_number: String

    """出荷用メモ欄"""
    note: String

    """出荷完了メール送信フラグ（送信する場合は true を指定）"""
    is_send_mail: Boolean = false
  ): Shipping
}
```

### 実行例

#### Query

```graphql
mutation UpdateShipped(
    $id: ID!,
    $shipping_date: DateTime,
    $shipping_delivery_name: String,
    $tracking_number: String,
    $note: String,
    $is_send_mail: Boolean
){
  updateShipped (
    id: $id,
    shipping_date: $shipping_date
    shipping_delivery_name: $shipping_delivery_name
    tracking_number: $tracking_number
    note: $note
    is_send_mail: $is_send_mail
  ) {
    id
    shipping_date
    shipping_delivery_name
    tracking_number
    note
    mail_send_date
  }
}
```

#### Variables

```json
{
	"id": 95,
	"shipping_date": "2020-05-18T12:57:08+09:00",
	"shipping_delivery_name": "テスト配送業者",
	"tracking_number": "tracking_number_0123",
	"note": "Note Update!",
	"is_send_mail": true
}
```

#### レスポンス

```json
{
  "data": {
    "updateShipped": {
      "id": "95",
      "shipping_date": "2020-05-18T03:57:08+00:00",
      "shipping_delivery_name": "テスト配送業者",
      "tracking_number": "tracking_number_0123",
      "note": "Note Update!",
      "mail_send_date": "2020-08-04T03:24:44+00:00",
    }
  }
}
```
