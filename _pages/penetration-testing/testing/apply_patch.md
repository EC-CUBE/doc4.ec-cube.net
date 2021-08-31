---
title: テストが止まらないようにするための設定
permalink: /penetration-testing/testing/apply_patch
---
例えば、テスト中の動作によって、セキュリティテストを実行中のユーザーが削除されてしまったり、ページのアクセス権が変更されてしまい、アクセスできなくなってしまう可能性があります。
これを防いで、安定してテストできるようにするため、EC-CUBE本体側に修正を入れます。

[パッチファイル全体はこちら](/patches/testing.patch)

**Note:** これらの修正の他、各種削除機能のテストをする場合は、削除処理がロールバックされるように該当処理の `EntityManager::flush()` をコメントアウトする必要があります。
{: .notice--warning}

## 管理画面

### ログアウト防止

ログアウトすると、セッションが切断され CSRF トークンが変更されてしまい、テストに失敗するため、ログアウトしないように設定する

``` diff
diff --git a/app/config/eccube/packages/security.yaml b/app/config/eccube/packages/security.yaml
index 2ad4e1a608..8da71094d0 100644
--- a/app/config/eccube/packages/security.yaml
+++ b/app/config/eccube/packages/security.yaml
@@ -34,9 +34,9 @@ security:
                 use_forward: false
                 success_handler: eccube.security.success_handler
                 failure_handler: eccube.security.failure_handler
-            logout:
-                path: admin_logout
-                target: admin_login
+            # logout:
+            #     path: admin_logout
+            #     target: admin_login
         customer:
             pattern: ^/
             anonymous: true

```

### 権限管理の更新防止

権限機能の拒否URLに `/` が登録されてしまい、管理画面にアクセスできなくなるのを防ぎます

```diff
diff --git a/src/Eccube/Controller/Admin/Setting/System/AuthorityController.php b/src/Eccube/Controller/Admin/Setting/System/AuthorityController.php
index 2f69bd5f62..4d2f4fdde1 100644
--- a/src/Eccube/Controller/Admin/Setting/System/AuthorityController.php
+++ b/src/Eccube/Controller/Admin/Setting/System/AuthorityController.php
@@ -103,7 +103,7 @@ class AuthorityController extends AbstractController
                     }
                 }
             }
-            $this->entityManager->flush();
+            // $this->entityManager->flush();

             $event = new EventArgs(
                 [

```

### マスタデータの更新防止

マスタデータが不用意に更新され、各種機能が動作しなくなるのを防ぎます。

```diff
diff --git a/src/Eccube/Controller/Admin/Setting/System/MasterdataController.php b/src/Eccube/Controller/Admin/Setting/System/MasterdataController.php
index 9b661031ab..a517b9a6ff 100644
--- a/src/Eccube/Controller/Admin/Setting/System/MasterdataController.php
+++ b/src/Eccube/Controller/Admin/Setting/System/MasterdataController.php
@@ -162,7 +162,7 @@ class MasterdataController extends AbstractController
                 }

                 try {
-                    $this->entityManager->flush();
+                    // $this->entityManager->flush();

                     $event = new EventArgs(
                         [

```


### 管理画面ユーザーの更新防止

テストを実施中のユーザーのパスワードやユーザーIDの変更を防ぎます

```diff
diff --git a/src/Eccube/Controller/Admin/Setting/System/MemberController.php b/src/Eccube/Controller/Admin/Setting/System/MemberController.php
index d1f042f67a..0ce6235b77 100644
--- a/src/Eccube/Controller/Admin/Setting/System/MemberController.php
+++ b/src/Eccube/Controller/Admin/Setting/System/MemberController.php
@@ -188,7 +188,7 @@ class MemberController extends AbstractController
                 $Member->setPassword($encodedPassword);
             }

-            $this->memberRepository->save($Member);
+            // $this->memberRepository->save($Member);

             $event = new EventArgs(
                 [

```

### セキュリティ管理の更新防止

セキュリティ管理の内容が変更され、管理画面にアクセスできなくなるのを防ぎます

```diff
diff --git a/src/Eccube/Controller/Admin/Setting/System/SecurityController.php b/src/Eccube/Controller/Admin/Setting/System/SecurityController.php
index d48b4f36b2..a658bbd33b 100644
--- a/src/Eccube/Controller/Admin/Setting/System/SecurityController.php
+++ b/src/Eccube/Controller/Admin/Setting/System/SecurityController.php
@@ -71,28 +71,28 @@ class SecurityController extends AbstractController
                 'ECCUBE_FORCE_SSL' => $data['force_ssl'] ? 'true' : 'false',
             ]);

-            file_put_contents($envFile, $env);
+            // file_put_contents($envFile, $env);

-            // 管理画面URLの更新. 変更されている場合はログアウトし再ログインさ
せる.
-            $adminRoute = $this->eccubeConfig['eccube_admin_route'];
-            if ($adminRoute !== $data['admin_route_dir']) {
-                $env = StringUtil::replaceOrAddEnv($env, [
-                    'ECCUBE_ADMIN_ROUTE' => $data['admin_route_dir'],
-                ]);
+            // // 管理画面URLの更新. 変更されている場合はログアウトし再ログインさせる.
+            // $adminRoute = $this->eccubeConfig['eccube_admin_route'];
+            // if ($adminRoute !== $data['admin_route_dir']) {
+            //     $env = StringUtil::replaceOrAddEnv($env, [
+            //         'ECCUBE_ADMIN_ROUTE' => $data['admin_route_dir'],
+            //     ]);

-                file_put_contents($envFile, $env);
+            //     file_put_contents($envFile, $env);

-                $this->addSuccess('admin.setting.system.security.admin_url_changed', 'admin');
+            //     $this->addSuccess('admin.setting.system.security.admin_url_changed', 'admin');

-                // ログアウト
-                $this->tokenStorage->setToken(null);
+            //     // ログアウト
+            //     $this->tokenStorage->setToken(null);

-                // キャッシュの削除
-                $cacheUtil->clearCache();
+            //     // キャッシュの削除
+            //     $cacheUtil->clearCache();

-                // 管理者画面へ再ログイン
-                return $this->redirect($request->getBaseUrl().'/'.$data['admin_route_dir']);
-            }
+            //     // 管理者画面へ再ログイン
+            //     return $this->redirect($request->getBaseUrl().'/'.$data['admin_route_dir']);
+            // }

             $this->addSuccess('admin.common.save_complete', 'admin');

```

## フロント画面

### 会員情報変更防止

会員情報が変更され、再ログインできなくなるのを防止します

``` diff
diff --git a/src/Eccube/Controller/Mypage/ChangeController.php b/src/Eccube/Controller/Mypage/ChangeController.php
index 70121e110c..1846b6fbe6 100644
--- a/src/Eccube/Controller/Mypage/ChangeController.php
+++ b/src/Eccube/Controller/Mypage/ChangeController.php
@@ -97,7 +97,7 @@ class ChangeController extends AbstractController
                     $encoder->encodePassword($Customer->getPassword(), $Customer->getSalt())
                 );
             }
-            $this->entityManager->flush();
+            // $this->entityManager->flush();

             log_info('会員編集完了');

```

### お届け先登録制限防止

お届け先上限数に抵触してテストが失敗するのを防止します

``` diff
diff --git a/src/Eccube/Controller/Mypage/DeliveryController.php b/src/Eccube/Controller/Mypage/DeliveryController.php
index af8dd653a6..803f3123f2 100644
--- a/src/Eccube/Controller/Mypage/DeliveryController.php
+++ b/src/Eccube/Controller/Mypage/DeliveryController.php
@@ -77,7 +77,7 @@ class DeliveryController extends AbstractController
             $addressCurrNum = count($Customer->getCustomerAddresses());
             $addressMax = $this->eccubeConfig['eccube_deliv_addr_max'];
             if ($addressCurrNum >= $addressMax) {
-                throw new NotFoundHttpException();
+                // throw new NotFoundHttpException();
             }
             $CustomerAddress = new CustomerAddress();
             $CustomerAddress->setCustomer($Customer);
```

### お届け先情報削除防止

お届け先情報が削除され、商品購入が失敗しないようにします

```diff
diff --git a/src/Eccube/Controller/Mypage/DeliveryController.php b/src/Eccube/Controller/Mypage/DeliveryController.php
index f3ecbc1ed0..7bd7290c1a 100644
--- a/src/Eccube/Controller/Mypage/DeliveryController.php
+++ b/src/Eccube/Controller/Mypage/DeliveryController.php
@@ -170,7 +170,7 @@ class DeliveryController extends AbstractController
             throw new BadRequestHttpException();
         }

-        $this->customerAddressRepository->delete($CustomerAddress);
+        // $this->customerAddressRepository->delete($CustomerAddress);

         $event = new EventArgs(
             [

```

### お気に入り追加防止

お気に入り追加(`/products/add_favorite/<id>`)のテストが失敗しないようにします
*お気に入り削除防止のパッチと同時には使用できません*

``` diff
diff --git a/src/Eccube/Repository/CustomerFavoriteProductRepository.php b/src/Eccube/Repository/CustomerFavoriteProductRepository.php
index 24430036a4..cff5db5a64 100644
--- a/src/Eccube/Repository/CustomerFavoriteProductRepository.php
+++ b/src/Eccube/Repository/CustomerFavoriteProductRepository.php
@@ -45,7 +45,7 @@ class CustomerFavoriteProductRepository extends AbstractRepository

             $em = $this->getEntityManager();
             $em->persist($CustomerFavoriteProduct);
-            $em->flush();
+            // $em->flush();
         }
     }
```

### お気に入り削除防止

お気に入りが削除され、お気に入り関連のテストが失敗しないようにします
*お気に入り追加防止のパッチと同時には使用できません*

```diff
diff --git a/src/Eccube/Controller/Mypage/MypageController.php b/src/Eccube/Controller/Mypage/MypageController.php
index d3abe49efd..b02a841954 100644
--- a/src/Eccube/Controller/Mypage/MypageController.php
+++ b/src/Eccube/Controller/Mypage/MypageController.php
@@ -362,7 +362,7 @@ class MypageController extends AbstractController
         $CustomerFavoriteProduct = $this->customerFavoriteProductRepository->findOneBy(['Customer' => $Customer, 'Product' => $Product]);

         if ($CustomerFavoriteProduct) {
-            $this->customerFavoriteProductRepository->delete($CustomerFavoriteProduct);
+            // $this->customerFavoriteProductRepository->delete($CustomerFavoriteProduct);
         } else {
             throw new BadRequestHttpException();
         }

```

**Note:** sequence アドオンを使用することで、登録→削除までの一連の流れをテストできる可能性
{: .notice--info}

### 会員の退会防止

テスト中の会員が退会してしまい、テストが停止しないようにします

```diff
diff --git a/src/Eccube/Controller/Mypage/WithdrawController.php b/src/Eccube/Controller/Mypage/WithdrawController.php
index 796a0b943e..940709df9c 100644
--- a/src/Eccube/Controller/Mypage/WithdrawController.php
+++ b/src/Eccube/Controller/Mypage/WithdrawController.php
@@ -121,8 +121,8 @@ class WithdrawController extends AbstractController

                     // 退会ステータスに変更
                     $CustomerStatus = $this->customerStatusRepository->find(CustomerStatus::WITHDRAWING);
-                    $Customer->setStatus($CustomerStatus);
-                    $Customer->setEmail(StringUtil::random(60).'@dummy.dummy');
+                    //$Customer->setStatus($CustomerStatus);
+                    //$Customer->setEmail(StringUtil::random(60).'@dummy.dummy');

                     $this->entityManager->flush();

@@ -140,11 +140,11 @@ class WithdrawController extends AbstractController
                     $this->mailService->sendCustomerWithdrawMail($Customer, $email);

                     // カートと受注のセッションを削除
-                    $this->cartService->clear();
-                    $this->orderHelper->removeSession();
+                    // $this->cartService->clear();
+                    // $this->orderHelper->removeSession();

                     // ログアウト
-                    $this->tokenStorage->setToken(null);
+                    // $this->tokenStorage->setToken(null);

                     log_info('ログアウト完了');

```

### 仮会員登録防止

``` diff
diff --git a/src/Eccube/Controller/EntryController.php b/src/Eccube/Controller/EntryController.php
index 076ac0a991..30a089994a 100644
--- a/src/Eccube/Controller/EntryController.php
+++ b/src/Eccube/Controller/EntryController.php
@@ -180,7 +180,7 @@ class EntryController extends AbstractController
                         ->setPoint(0);

                     $this->entityManager->persist($Customer);
-                    $this->entityManager->flush();
+                    // $this->entityManager->flush();

                     log_info('会員登録完了');

```

### 本会員登録防止

```
@@ -293,7 +293,7 @@ class EntryController extends AbstractController
         $CustomerStatus = $this->customerStatusRepository->find(CustomerStatus::REGULAR);
         $Customer->setStatus($CustomerStatus);
         $this->entityManager->persist($Customer);
-        $this->entityManager->flush();
+        // $this->entityManager->flush();

         log_info('本会員登録完了');

```

### 注文完了時のカート削除

注文完了時にカートが削除されないようにします。

*注文確認画面から注文完了画面への遷移は `/shopping/checkout` へ CSRF トークンを POST するのみですので、あまり有用ではないかもしれません。*

``` diff
diff --git a/src/Eccube/Controller/ShoppingController.php b/src/Eccube/Controller/ShoppingController.php
index 8e99094d73..e8e744f39a 100644
--- a/src/Eccube/Controller/ShoppingController.php
+++ b/src/Eccube/Controller/ShoppingController.php
@@ -386,7 +386,7 @@ class ShoppingController extends AbstractShoppingController
                     return $response;
                 }

-                $this->entityManager->flush();
+                //$this->entityManager->flush();

                 log_info('[注文処理] 注文処理が完了しました.', [$Order->getId()]);
             } catch (ShoppingException $e) {
@@ -409,7 +409,7 @@ class ShoppingController extends AbstractShoppingController

             // カート削除
             log_info('[注文処理] カートをクリアします.', [$Order->getId()]);
-            $this->cartService->clear();
+            //$this->cartService->clear();

             // 受注IDをセッションにセット
             $this->session->set(OrderHelper::SESSION_ORDER_ID, $Order->getId());
@@ -417,7 +417,7 @@ class ShoppingController extends AbstractShoppingController
             // メール送信
             log_info('[注文処理] 注文メールの送信を行います.', [$Order->getId()]);
             $this->mailService->sendOrderMail($Order);
-            $this->entityManager->flush();
+            //$this->entityManager->flush();

             log_info('[注文処理] 注文処理が完了しました. 購入完了画面へ遷移します.', [$Order->getId()]);

@@ -463,7 +463,7 @@ class ShoppingController extends AbstractShoppingController
         }

         log_info('[注文完了] 購入フローのセッションをクリアします. ');
-        $this->orderHelper->removeSession();
+        //$this->orderHelper->removeSession();

         $hasNextCart = !empty($this->cartService->getCarts());

```

### クーポンプラグインの利用制限解除

何回でも繰り返しクーポンを利用できるようにします

```
diff --git a/Controller/CouponShoppingController.php b/Controller/CouponShoppingController.php
index 7551217..091bbd7 100644
--- a/Controller/CouponShoppingController.php
+++ b/Controller/CouponShoppingController.php
@@ -159,7 +159,7 @@ class CouponShoppingController extends AbstractController
                     $form->get('coupon_cd')->addError(new FormError(trans('plugin_coupon.front.shopping.sameuser')));
                     $error = true;
                 }
-
+                $error = false;
                 // ----------------------------------
                 // 値引き項目追加 / 合計金額上書き
                 // ----------------------------------
```
