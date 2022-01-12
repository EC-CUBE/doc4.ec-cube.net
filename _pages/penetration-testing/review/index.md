---
title: テスト評価
permalink: /penetration-testing/review
---
## レポート出力

レポートのメニューから、各種レポートを出力できます。
基本的には、 HTML形式で出力します。

[Faraday](https://faradaysec.com){:target="_blank"} などの、脆弱性管理ツールに取り込んで確認することもできます。

## セキュリティアラートの評価方法

セキュリティアラートを検出した場合、単に危険度を評価するというよりも、ビジネスに与える影響度を評価することが重要です。

具体的な評価方法については、主に **[共通脆弱性評価システムCVSS](https://www.ipa.go.jp/security/vuln/CVSS.html){:target="_blank"}** が用いられます。

**Note:** [CVSS計算ツール](https://www.first.org/cvss/calculator/3.0){:target="_blank"} が公開されていますので、こちらもご活用ください<br />
[https://www.first.org/cvss/calculator/3.0](https://www.first.org/cvss/calculator/3.0){:target="_blank"}
{: .notice--info}

詳細は、独立行政法人 情報処理推進機構(IPA) による [共通脆弱性評価システムCVSS](https://www.ipa.go.jp/security/vuln/CVSS.html){:target="_blank"} のページをご覧ください。

## 誤検知かどうかの判定

アラートが検出されても、必ずしも脆弱性があるわけではなく、誤検知である可能性もあります。

誤検知される理由は様々ですが、アプリケーションの特性が原因の場合が多いです。

- レポートに記録されているリクエストを、実際に送信してみて再現するか
- 再現した場合、実際に脅威を与えるかどうか

がポイントです。

特に、 **High** のアラートが検出された場合は、慎重な確認が必要です。
判断に迷う場合は専門家に判断を仰ぐと良いでしょう

XSS が検出され、レスポンスの送信も再現するが、実際には実行されることない誤検知の例
![XSS positive false](/images/penetration-testing/review_positive_false.png)
{: .notice}
