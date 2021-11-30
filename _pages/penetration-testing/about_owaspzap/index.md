---
title: OWASP ZAP について
permalink: /penetration-testing/about_owaspzap
---
**OWASP Zed Attack Proxy(ZAP)** は The **O**pen **W**eb **A**pplication **S**ecurity **P**roject(OWASP) が開発したオープンソースのWebセキュリティ検査ツールです。
国際的なボランティアの献身的なチームによって、活発に開発されています。

Official: [https://www.zaproxy.org](https://www.zaproxy.org){:target="_blank"}

## OWASP ZAP でのテストの流れ

基本的なテストは、以下の手順で実施します。

1. 巡回 - Local Proxies を使用して、すべての機能を巡回します。
1. スパイダー - **スパイダー** を使用して、隠れていたURLを検出します。
1. 強制ブラウズ - 参照されていないファイルやディレクトリを検出します。
1. 動的スキャン - 実際に攻撃を行ない、潜在的な脆弱性を検出します。
1. 手動テスト - より多くの脆弱性を見つけるには、OWASP ZAP の様々な機能を使用して手動テストを実施します。

**Note:** 詳しくは、 OWASP ZAP のヘルプにある、ユーザーガイドをご覧ください。
{: .notice--info}
