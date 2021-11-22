---
title: EC-CUBE Penetration Testing with OWASP ZAP
permalink: /penetration-testing
---
## はじめに

**Attention!!**
このツールは、サイトを実際に攻撃し、脆弱性が無いかを確認するツールです。
必ずローカル環境の Docker でのみ使用し、稼動中のサイトには決して使用しないでください。
意図せずデータが更新されたり、削除される場合があります。<br />
**テストは自己責任で実施し、株式会社イーシーキューブ及び、関連する開発コミュニティは一切の責任を負いかねますのであらかじめご了承ください。**
{: .notice--danger}

手っ取り早くテスト環境を作成したい方のために [Quick Start](/penetration-testing/quick_start) をご用意しました。
{: .notice--success}

現在、このテストの自動化を進めています。試してみたい方は [こちらの README](https://github.com/EC-CUBE/ec-cube/tree/4.1/zap/selenium/ci/TypeScript#automated-security-tests-with-owasp-zap) をご覧ください。
{: .notice--success}

このドキュメントは、EC-CUBE のリリース前に実施しているセキュリティテストを横展開し、より多くのユーザーに利用してもらうことを目的に作成しています。
単なる手順書のみではなく、テストに対する考え方、改善方法なども含まれています。

ここで使用する OWASP ZAP は、とても強力なツールですが、 十分にテストするためには、EC-CUBE 特有の問題を乗り越える必要があります。
テストの結果、セキュリティの欠陥が発見されなかったとしても、テストが不十分で検出されなかった可能性もあります。

テスト結果のみを過信せず、専門家への調査依頼も併用すると、より強固なセキュリティ環境が構築できます。

## イントロダクション

- [対象の読者](/penetration-testing/introduction#%E5%AF%BE%E8%B1%A1%E3%81%AE%E8%AA%AD%E8%80%85)

### セキュリティテストについて

- [セキュリティテストの種類](/penetration-testing/introduction/type)
- [ウェブアプリケーション侵入テストについて](/penetration-testing/introduction/penetration-test)
- [ウェブアプリケーション侵入テストの構成](/penetration-testing/introduction/layout)

### OWASP ZAP について

- [OWASP ZAP でのテストの流れ](/penetration-testing/about_owaspzap#owasp-zap-%E3%81%A7%E3%81%AE%E3%83%86%E3%82%B9%E3%83%88%E3%81%AE%E6%B5%81%E3%82%8C)

## テスト計画

- [テストの背景、目的](/penetration-testing/planning/purpose)
- [テスト対象](/penetration-testing/planning/target)
- [テスト戦略](/penetration-testing/planning/strategy)
- [スケジューリングの目安](/penetration-testing/planning/scheduling)
- [テスト結果の評価方法](/penetration-testing/review)

## テストの実施

- [環境構築](/penetration-testing/testing/settings)
- [テストの実施における注意事項](/penetration-testing/testing/attention)
- [テストが止まらないようにするための修正](/penetration-testing/testing/apply_patch)
- [コンテキストの分類](/penetration-testing/testing/context)
- [手動探索の仕方](/penetration-testing/testing/manual_explore)
- [動的スキャンの仕方](/penetration-testing/testing/active_scan)
- [テスト対象のURL一覧](/penetration-testing/testing/manual_inspection_urls)

## テスト評価

- [レポート出力](/penetration-testing/review#%E3%83%AC%E3%83%9D%E3%83%BC%E3%83%88%E5%87%BA%E5%8A%9B)
- [セキュリティアラートの評価方法](/penetration-testing/review#%E3%82%BB%E3%82%AD%E3%83%A5%E3%83%AA%E3%83%86%E3%82%A3%E3%82%A2%E3%83%A9%E3%83%BC%E3%83%88%E3%81%AE%E8%A9%95%E4%BE%A1%E6%96%B9%E6%B3%95)
- [誤検知かどうかの判定](/penetration-testing/review#%E8%AA%A4%E6%A4%9C%E7%9F%A5%E3%81%8B%E3%81%A9%E3%81%86%E3%81%8B%E3%81%AE%E5%88%A4%E5%AE%9A)

## セキュリティの改善

- [セキュリティホールを発見した場合](/penetration-testing/improvement#%E3%82%BB%E3%82%AD%E3%83%A5%E3%83%AA%E3%83%86%E3%82%A3%E3%83%9B%E3%83%BC%E3%83%AB%E3%82%92%E7%99%BA%E8%A6%8B%E3%81%97%E3%81%9F%E5%A0%B4%E5%90%88)
- [脆弱性の報告方法](/penetration-testing/improvement#%E8%84%86%E5%BC%B1%E6%80%A7%E3%81%AE%E5%A0%B1%E5%91%8A%E6%96%B9%E6%B3%95)
- [EC-CUBE本体への反映](/penetration-testing/improvement#ec-cube%E6%9C%AC%E4%BD%93%E3%81%B8%E3%81%AE%E5%8F%8D%E6%98%A0)
- [テスト方法の改善](/penetration-testing/improvement#%E3%83%86%E3%82%B9%E3%83%88%E6%96%B9%E6%B3%95%E3%81%AE%E6%94%B9%E5%96%84)
