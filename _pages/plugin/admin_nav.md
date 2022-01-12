---
title: 管理画面ナビの拡張
keywords: plugin admin nav プラグイン 管理画面 ナビ
tags: [plugin, admin, nav]
permalink: plugin_admin_nav

---

管理画面にプラグインのメニューを追加します。
以下のようにEccubeNavを実装すると, メニューの上書き/追加ができます。
追加の場合は対象メニュー内の最下部に追加されます。
構造は本体のナビ定義も参考にしてください。
プラグインの場合、有効時のみ表示されます。
本体の管理画面ナビは `/app/config/eccube/packages/eccube_nav.yaml` で定義されています。

```php
class Nav implements EccubeNav
{
    public static function getNav()
    {
        return [
            'product' => [
                'children' => [
                    'hoge' => [
                        'name' => '商品管理の子（追加）',
                        'url' => 'admin_homepage',
                    ],
                ],
            ],
            'piyo' => [
                'name' => '1階層メニュー（追加）',
                'icon' => 'fa-cube',
                'children' => [
                    'piyopiyo1' => [
                        'name' => '2階層メニュー（子なし）',
                        'url' => 'admin_homepage',
                    ],
                    'piyopiyo2' => [
                        'name' => '2階層メニュー（子あり）',
                        'children' => [
                            'piyopiyopiyo1' => [
                                'name' => '3階層メニュー1',
                                'url' => 'admin_homepage',
                            ],
                            'piyopiyopiyo2' => [
                                'name' => '3階層メニュー2',
                                'url' => 'admin_homepage',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
```
