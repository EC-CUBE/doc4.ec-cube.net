---
title: 受注ステータスのカスタマイズ
description: Symfony Workflow を利用した受注ステータス遷移時の処理の追加方法を説明します。
keywords: core カスタマイズ 受注ステータス OrderStatus
tags: [core, OrderStatus]
permalink: customize_order_state_machine
folder: customize
---

## 受注ステータスの拡張 [#3325](https://github.com/EC-CUBE/ec-cube/pull/3325){:target="_blank"}

```mermaid
stateDiagram-v2
    [*] --> 注文処理中

    state 注文処理中 {
        direction lr
        exit<br/>/<br/>減らす(在庫・利用ポイント)
    }
    
    注文処理中 --> ♢（選択）: 商品を購入する
    
    state ♢（選択）<<choice>>
    ♢（選択）--> 新規受付: [支払方法 <> 決済プラグイン]<br/>/<br/>メール送信する
    ♢（選択）--> 決済処理中: [支払方法 = 決済プラグイン]

    決済処理中 --> 新規受付: 決済完了/メール送信する
    決済処理中 --> 注文処理中: 決済画面から<br/>注文確認画面に戻る<br/>/<br/>戻す(在庫・利用ポイント)

    %% 終了点1: 決済エラー用
    決済処理中 --> 決済処理エラー: 離脱<br/>/<br/>決済エラー通知<br/>/<br/>手動で戻す<br/>(在庫・利用ポイント)
    決済処理エラー --> ◎（終了1）

    state 新規受付 {
        direction lr
        do/購入日を登録
    }

    新規受付 --> 入金済み: 入金確認<br/>[支払方法 <br/>=<br/>銀振orコンビニ前払い]
    新規受付 --> 対応中: 梱包作業

    state 入金済み {
        direction lr
        do/入金日を登録
    }

    入金済み --> 対応中: 梱包作業

    %% キャンセルフロー
    state キャンセル合流 <<fork>>
    新規受付 --> キャンセル合流: キャンセル
    入金済み --> キャンセル合流: キャンセル
    対応中 --> キャンセル合流: キャンセル

    キャンセル合流 --> 注文取り消し
    state 注文取り消し {
        direction lr
        do/戻す(在庫・利用ポイント)
    }
    note right of 注文取り消し
        操作ミスに起因する<br/>イレギュラー処理実装するか<br/>要検討
    end note

    注文取り消し --> ◎（終了2） : 発送前キャンセル

    %% 発送フロー
    state 発送合流 <<fork>>
    新規受付 --> 発送合流: 発送済みにする <br/>[すべての出荷に発送日が<br/>登録されている]<br/>/<br/>出荷に発送日を登録
    入金済み --> 発送合流: 発送済みにする <br/>[すべての出荷に発送日が<br/>登録されている]<br/>/<br/>出荷に発送日を登録
    対応中 --> 発送合流: 発送済みにする <br/>[すべての出荷に発送日が<br/>登録されている]<br/>/<br/>出荷に発送日を登録

    発送合流 --> 発送済み
    state 発送済み {
        direction lr
        do/加算ポイントを付与
    }
    note right of 発送済み
        複数配送の場合、<br/>すべての関連する出荷に<br/>発送日が登録されたら<br/>発送済みとする
    end note

    %% 終了点3: お届け完了用
    発送済み --> ◎（終了3） : 商品お届け

    %% 返品フロー（ここを修正）
    発送済み --> 返品: クレーム等

    state 返品 {
        direction lr
        do/戻し(利用・加算ポイント)
    }
    note right of 返品
        操作ミスに起因する<br/>イレギュラー処理実装するか<br/>要検討
    end note

    返品 --> 発送済み: 返品取り消し
    返品 --> ◎（終了4） : 商品返品
    
```

[受注対応状況の流れ](/spec_order)も合わせてご確認ください。

### 基本の拡張方法

[Symfony Workflow Component](https://symfony.com/doc/current/components/workflow.html){:target="_blank"}を利用して実装しています。

ステータス遷移時に行う処理を追加するには、ステータス遷移時のイベントを実装します。
ステータスの遷移は[app/config/eccube/packages/order_state_machine.php](https://github.com/EC-CUBE/ec-cube/blob/4.3/app/config/eccube/packages/order_state_machine.php){:target="_blank"}に定義しています。

イベントを実装することで受注の遷移時に任意の処理を追加できます。

| 遷移元                     | 遷移先     | イベント                                        |
|------------------------------|------------|-------------------------------------------------|
| 新規受付                   | 入金済み   | `workflow.order.transition.pay`                 |
| 新規受付, 入金済み         | 対応中     | `workflow.order.transition.packing`             |
| 新規受付, 対応中, 入金済み | キャンセル | `workflow.order.transition.cancel`              |
| キャンセル                 | 対応中     | `workflow.order.transition.back_to_in_progress` |
| 新規受付, 対応中, 入金済み | 発送済み   | `workflow.order.transition.ship`                |
| 発送済み                   | 返品       | `workflow.order.transition.return`              |
| 返品                       | 発送済み   | `workflow.order.transition.cancel_return`       |

- 例) 返品時の処理を追加したいとき
    - `workflow.order.transition.return` イベントを受け取る `EventSubscriberInterface` を実装します。

```php
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Eccube\Entity\Order;
use Symfony\Component\Workflow\Event\Event;

class SampleTransitionListener implements EventSubscriberInterface
{
    /**
     * 返品時の処理.
     *
     * @param Event $event
     */
    public function onReturn(Event $event): void
    {
        /* @var Order $Order */
        $Order = $event->getSubject()->getOrder();
        .... /* 処理を実装する */
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return ['workflow.order.transition.return' => 'onReturn'];
    }
}
```

### 参考

EC-CUBE のデフォルトのイベントは [src/Eccube/Service/OrderStateMachine.php](https://github.com/EC-CUBE/ec-cube/blob/4.3/src/Eccube/Service/OrderStateMachine.php){:target="_blank"} に実装されています。

[Using Events](https://symfony.com/doc/current/workflow/usage.html#using-events){:target="_blank"}
