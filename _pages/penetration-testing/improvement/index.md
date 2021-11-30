---
title: セキュリティの改善
permalink: /penetration-testing/improvement
---
## セキュリティホールを発見した場合

ウェブサイトのセキュリティホールを発見した場合、脆弱性の情報はウェブサイトの運営者にとっても、ウェブサイト利用者にとっても脅威になりうるため、情報の取り扱いに注意が必要です。
脆弱性情報は、当事者以外に流出しないよう厳重に管理してください。

何より、他人に被害を生じさせないことが重要です。
安全に対策されるまで、発見者と運営者が協調し、誠意を持って対応しましょう。

**Note:** [脆弱性の発見・報告に関する勉強会 資料](https://www.ipa.go.jp/security/vuln/report/notice/handling_20190926.html){:target="_blank"} が参考になります。
{: .notice--info}

## 脆弱性の報告方法

[IPA 情報処理推進機構による脆弱性関連情報の届出受付窓口](https://www.ipa.go.jp/security/vuln/report/){:target="_blank"} または、 株式会社イーシーキューブ(support@ec-cube.net) までご連絡ください。

## EC-CUBE本体への反映

EC-CUBE本体に脆弱性が発見された場合、 IPA や、脆弱性情報調整機関である JPCERT/CC と連携し、安全に対策されます。

EC-CUBE利用店舗、利用者に被害を生じさせないよう、脆弱性情報は厳重に管理されます。

EC-CUBE本体へ反映された脆弱性情報は、 **[EC-CUBE脆弱性リスト](https://www.ec-cube.net/info/weakness/){:target="_blank"}** にまとめられています。

**Note:** EC-CUBE2系、3系、4系の各バージョン最新版には、すべて脆弱性対策が実施されています。
旧バージョンをご利用の場合は、未適用の脆弱性が無いようくれぐれもご注意ください。
{: .notice--info}

## テスト方法の改善

今のところ、 OWASP ZAP を使用したペネトレーションテストは、手作業の部分も多く、EC-CUBE及びセキュリティに関する専門的な知識と、多大なマンパワーが必要です。

今後は、 Selenium や GitHub Actions を活用した自動化を進めていく予定です。

テストの改善提案がありましたら、 [GitHub の EC-CUBEリポジトリ](https://github.com/EC-CUBE/ec-cube){:target="_blank"} までぜひお寄せください。


