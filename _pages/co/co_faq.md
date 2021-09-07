---
title: ec-cube.co FAQ
keywords: co ec-cube.co クラウド版 FAQ
tags: [co, ec-cube.co]
permalink: co/co_faq
folder: co
---


---

よくお問い合わせいただく事柄をまとめました。

<section class="p-faq">

  <section class="p-db">
    <h2 class="p-faq__title" id="DB" style="padding-top:0;">DB</h2>
    <p class="p-faq__question">DBクライアントツールを使用して、直接ec-cube.coのDBにアクセスはできますか？</p>
    <div class="p-faq__answer">
    <p>
    セキュリティや運用の都合上、接続は不可とさせていただいております。<br>
    ご了承ください。
    </p>
    <p>スタンダードプランをご契約いただいた後は、カスタマイズ領域を使用して実現が可能です。</p>
    </div>

    <p class="p-faq__question">DBのスキーマ変更は可能でしょうか？</p>
    <div class="p-faq__answer">
    <p>トライアル環境では不可となります。ご了承ください。</p>
    <p>スタンダードプランをご契約いただいた後は、カスタマイズ領域を使用して実現が可能です。<br />
    詳しくは <a href="https://doc4.ec-cube.net/customize_entity#%E5%9F%BA%E6%9C%AC%E3%81%AE%E6%8B%A1%E5%BC%B5%E6%96%B9%E6%B3%95" target="_blank">Entityのカスタマイズ 基本の拡張方法</a> をご確認ください。</p>
    </div>

    <p class="p-faq__question">対応DBはPostgreSQLのみでしょうか？</p>
    <div class="p-faq__answer">
    <p>はい。PostgreSQLのみとなります。</p>
    </div>


  </section>

  <section class="p-dl">
    <h2 id="ダウンロード版との違い" class="p-faq__title">ダウンロード版との違い</h2>
    <p class="p-faq__question">ec-cube.coとダウンロード版はどのような点が異なるのでしょうか？</p>
    <div class="p-faq__answer">
      <p>本体機能はダウンロード版のEC-CUBE4系がベースになっており、同じです。<br />
ダウンロード版同様、EC-CUBE4系対応の各種決済を含めたプラグイン、デザインテンプレートの利用が可能です。<br />
また、スタンダードプランをご契約いただいた後はカスタマイズディレクトリを使用した機能拡張が可能となります。</p>
    </div>
  </section>

  <section class="p-ga">
    <h2 class="p-faq__title" id="google-analyticsアクセス解析">Google Analytics(アクセス解析)</h2>
    <p class="p-faq__question">Google Analyticsを設定したいのですが、どうすればいいでしょうか？</p>
    <div class="p-faq__answer">
      <p><a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a> のプラグインを使用していただくことで実現可能です。</p>
      <ul>
        <li><a href="https://www.ec-cube.net/products/detail.php?product_id=1715" target="_blank">【EC-CUBE4対応】Google Analytics eコマース/拡張eコマース対応プラグイン</a></li>
        <li><a href="https://www.ec-cube.net/products/detail.php?product_id=1722" target="_blank">Google Analytics Eコマース/User-ID対応プラグイン(4.0系)</a></li>
      </ul>
    </div>
  </section>

  <section class="p-api">
    <h2 class="p-faq__title" id="web-api">Web API</h2>
    <p class="p-faq__question">Web APIは使用できますか？WebAPIでどんなことが実現できますか？</p>
    <div class="p-faq__answer">
      <p><a href="https://www.ec-cube.net/products/detail.php?product_id=2121" target="_blank">EC-CUBE 4.0 Web API プラグイン</a> を使用していただくことで実現可能です。<br />
詳しくは<a href="https://doc.ec-cube.net/eccube-api4/" target="_blank">開発ドキュメント</a> をご参照ください。</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="アクセス制限">アクセス制限</h2>
    <p class="p-faq__question">サイトへアクセス制限をかけたいのですが、どうすればいいでしょうか？</p>
    <div class="p-faq__answer">
    <p>目的により実現方法が異なります。</p>
      <ul>
      <li>会員限定サイトとして運用したい
        <ul>
          <li><a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a> のプラグインを使用していただくことで実現可能です。<br />
    「クローズド」「特定会員」「会員登録」などのキーワードで検索していただきご検討ください。</li>
        </ul>
      </li>
      <li>管理画面にアクセス制限をかけて安全に運用したい
        <ul>
          <li>管理画面 ＞ 設定 ＞ システム設定 ＞ セキュリティ管理でIP制限の設定ができます。</li>
        </ul>
      </li>
      <li>.htaccessファイルで設定し、サイト全体にアクセス制限をかけたい
        <ul>
          <li>ご自身での設定は不可となります。<br />
    Basic認証を希望される場合は<a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> からご依頼ください。<br />
    その際、以下の状況を共有いただけますとスムーズになります。ご協力お願いいたします。
            <ul>
              <li>対象のサイトのURL</li>
              <li>問い合わせ内容：Basic認証設定を希望</li>
            </ul>
          </li>
        </ul>

        <p>※Basic認証の設定範囲は、フロント・管理画面別々ではなく両方ともに設定となります。</p>
      </li>
    </ul>
    </div>
 
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="アップロード制限">アップロード制限</h2>
    <p class="p-faq__question">ファイルアップロードの制限はありますか？</p>
    <div class="p-faq__answer">
      <p>はい。1ファイル毎のアップロードには10Mの制限がございます。</p>
    </div>
    <p class="p-faq__question">制限の最大値の変更、もしくはカスタマイズ対応可能でしょうか？</p>
    <div class="p-faq__answer">
      <p>変更やカスタマイズは不可となります。ご了承ください。</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="エラー動作不良">エラー/動作不良</h2>
    <p class="p-faq__question">画面を操作したら不安定だったりエラーが発生するのですが、どうしたらいいでしょうか？</p>
    <div class="p-faq__answer">
      <p><a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> からご相談ください。<br />
      その際、以下の状況を共有いただけますとスムーズになります。ご協力お願いいたします。</p>
      <ul>
        <li>調査対象のサイトのURL</li>
        <li>どのような事象が発生しているか、どのような操作を行うと発生するか</li>
        <li>いつ発生したか</li>
        <li>現在は解消しているか、それとも継続中か</li>
        <li>事象が発生する前に行った変更など</li>
      </ul>

      <p>※プラグインインストール・アンインストール後に不安定となりご相談いただくことが、一概には言えませんがございます。</p>
      </div>

    <p class="p-faq__question">特に何もした覚えがないのですが、管理画面の特定のメニューが突然表示されなくなりました。</p>
    <div class="p-faq__answer">
          <p>キャッシュ不整合によるものだと考えられます。<br />
      管理画面のキャッシュ管理からキャッシュ削除をお試しください。</p>

      <p>解消しない場合は <a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> からご相談ください。<br />
      その際、以下の状況を共有いただけますと調査がスムーズに行えます。ご協力お願いいたします。</p>
      <ul>
        <li>調査対象のサイトのURL</li>
        <li>どのような事象が発生しているか、どのような操作を行うと発生するか</li>
        <li>いつ発生したか</li>
        <li>現在は解消しているか、それとも継続中か</li>
        <li>事象が発生する前に行った変更など</li>
      </ul>
    </div>
  </section>


  <section class="p-db">
    <h2 class="p-faq__title" id="カスタマイズ">カスタマイズ</h2>
    <p class="p-faq__question">合計金額が一定金額を超えた場合は送料を無料にしたいのですが、どうしたらいいでしょうか？</p>
    <div class="p-faq__answer">
      <p>管理画面 ＞ 設定 ＞ 基本設定 ＞ 送料設定で、送料無料条件を設定してください。</p>
    </div>
    <p class="p-faq__question">商品ごとに配送元・送料を設定したいのですが、どうしたらいいでしょうか？</p>
    <div class="p-faq__answer">
     <p><a href="https://www.shiro8.net/manual4/v40x/product/product_new.html" target="_blank">販売種別機能</a> がございますので、そちらをご使用ください。</p>
    </div>
    <p class="p-faq__question">配送方法に「店舗受取」を設定したいのですが、どうしたらいいでしょうか？</p>
    <div class="p-faq__answer">
   <p>本体のコアコード変更が必要となるため、実現は不可となります。 
ただし以下でカスタマイズいただくことで実現は可能です。</p>

<ol>
  <li>
    <p>配送方法の設定<br />
管理画面 ＞ 設定 ＞ 店舗設定 ＞ 配送方法設定 の新規作成で「店舗受取」を作成し、標準機能で「お届け時間」と表示される項目に時間ではなく店舗名等を登録します。</p>
  </li>
  <li>
    <p>管理画面からのコード変更<br />
標準機能としては配送方法の選択画面で「お届け日」「お届け時間」が表示されるので、「店舗受取」を選択された場合は、「お届け日」の項目を非表示、「お届け時間」の文言を【受取店舗】などの文言に変更する様、管理画面 ＞ コンテンツ管理 ＞JavaScript管理 でif文で制御を入れます。</p>
  </li>
</ol>

<p>注)受注管理上の見え方は下記のようになります。（こちらはカスタマイズができません）</p>
<ul>
  <li>「店舗受取」の場合は「お届け時間」の項目に「受取店舗」が入ります。<br />
この場合、CSVでも店舗の場合は「お届け時間」の項目に「受取店舗」が入ります。</li>
</ul>
    </div>
    <p class="p-faq__question">ファビコンを変更したいのですが、どうしたらいいでしょうか？</p>
    <div class="p-faq__answer">
      <p>管理画面 ＞ コンテンツ管理 ＞ ファイル管理 で、assets ＞ img ＞ commonと辿り、favicon.icoをアップロードすることで変更が可能です。</p>
    </div>

    <p class="p-faq__question">独自プラグインを作ってカスタマイズ開発の選択肢のみですか？コアカスタマイズはできますか？</p>
    <div class="p-faq__answer">
      <p>スタンダードプランをご契約いただいた後は、カスタマイズディレクトリによるカスタマイズ開発が可能です。<br />
独自プラグインによる実現も可能ですが、 独自プラグインに関連する不具合対応等はサポート外となります。ご了承ください。<br />
また、コアカスタマイズ可能な新プランは現在検討中となります。</p>
    </div>

    <p class="p-faq__question">商品登録の自由記載のフィールドをカスタマイズして操作するようにできますか？</p>
    <div class="p-faq__answer">
      <p><a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a> にございますプラグインを使用することで実現可能かと思われます。</p>

<p>例：<a href="https://www.ec-cube.net/products/detail.php?product_id=1713" target="_blank">商品情報項目追加プラグイン</a></p>
    </div>

    <p class="p-faq__question">納品書のカスタマイズをしたいのですが、どうしたらいいでしょうか？</p>
    <div class="p-faq__answer">
      <p>トライアル環境では不可となります。ご了承ください。<br />
スタンダードプランをご契約いただいた後は、実装方法にもよりますが実現は可能です。</p>
    </div>

    <p class="p-faq__question">受注ステータスの追加は可能でしょうか？</p>
    <div class="p-faq__answer">
 <p>管理画面から受注ステータスの追加とともにプログラムの変更も必要となる為、不可となります。ご了承ください。 <br />
詳しくは <a href="https://github.com/EC-CUBE/ec-cube/issues/4299" target="_blank">Github Issue：マスタデータ管理から注文ステータスを追加しても、受注管理から操作できない</a> をご参照ください。</p>
    </div>

    <p class="p-faq__question">フロントのデザイン変更方法を教えて欲しい</p>
    <div class="p-faq__answer">
     <p>管理画面のコンテンツ管理下の</p>
<ul>
  <li>CSS管理</li>
  <li>JavaScript管理</li>
</ul>

<p>で既存のコードを更新することにより、フロントのデザイン変更が可能です。<br />
また、自身で作成された独自デザインテンプレートをアップロードすることで、フロントのデザイン変更は可能です。<br />
ただし独自デザインテンプレートに関連する不具合対応等はサポート外となります。ご了承ください。</p>

<p>※管理画面から直接ファイル(default_frame.twig)を編集することは不可となりますのでご了承ください。</p>
    </div>

    <p class="p-faq__question">nstagramショッピング機能は導入出来ますか？</p>
    <div class="p-faq__answer">
      <p><a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a> にございますFacebook社が提供する <a href="https://www.ec-cube.net/products/detail.php?product_id=1763" target="_blank">Facebook連携プラグイン EC-CUBE4対応版</a> を導入することで可能です。</p>
    </div>

    <p class="p-faq__question">簡易カートへの追加機能やヘッダー上に最近見た商品表示機能などが実現可能でしょうか？</p>
    <div class="p-faq__answer">
      <p>以下のような機能が実現可能かのお問い合わせをいただくことがございます。</p>
<ul>
  <li>商品一覧のカートマークをクリックすると、ポップアップで簡易カートに追加できる</li>
  <li>ヘッダーの上に「最近見た商品」の一覧を横並びで表示する</li>
</ul>

<p>実装方法にもよりますが、スタンダードプランをご契約いただいた後はカスタマイズ領域の使用により実現が可能となります。</p>
    </div>

    <p class="p-faq__question">SEO対策でmetaタグを設定したいのですが、どうしたらいいでしょうか？</p>
    <div class="p-faq__answer">
      <p>管理画面 ＞ コンテンツ管理 ＞ ページ管理 の各ページ下部のメタ設定より設定が可能です。</p>
    </div>

    <p class="p-faq__question">納品書のロゴを変更したいのですが、どうしたらいいでしょうか？</p>
    <div class="p-faq__answer">
      <p>管理画面 ＞ コンテンツ管理 ＞ ファイル管理 からロゴ画像をアップロードすることで変更が可能です。</p>
    </div>

    <p class="p-faq__question">どんなカスタマイズが可能でしょうか？</p>
    <div class="p-faq__answer">
     <ul>
  <li>管理画面から変更できる範囲のカスタマイズは可能です。</li>
  <li>プラグイン、デザインテンプレート導入によるカスタマイズは可能です。</li>
  <li>カスタマイズディレクトリ使用によるカスタマイズは可能です。</li>
  <li>独自プラグイン、独自デザインテンプレートによるカスタマイズは可能ですが、独自プラグインや独自テンプレートに関連する不具合対応等はサポート外となります。ご了承ください。</li>
</ul>

<p>※コアのカスタマイズは不可となります。ご了承ください。</p>
    </div>

    <p class="p-faq__question">管理画面からコンテンツ管理のコードを変更したのですが、初期の状態に戻すにはどうしたらいいですか？</p>
    <div class="p-faq__answer">
      <p>ec-cube.coで利用されているEC-CUBEのソースコードは <a href="https://github.com/EC-CUBE/ec-cube/tree/co/master" target="_blank">co/masterブランチ</a> で公開されております。<br />
ご参照いただき、該当のコードをコピーしてご自身の環境へ反映してください。</p>
    </div>

    <p class="p-faq__question">各社プラグインのカスタマイズの可・不可を教えて欲しい</p>
    <div class="p-faq__answer">
      <p>各社提供プラグインごとによってライセンスが異なり、イーシーキューブ社ではお答えできかねます。<br />
ご了承ください。</p>

<p><a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a> の各社プラグインの商品詳細ページに掲載されておりますライセンス情報をご確認の上、各社へお問い合わせくださいますようお願いいたします。</p>
    </div>

    <p class="p-faq__question">デザイン面のサポートはどちらに相談したらよいでしょうか？</p>
    <div class="p-faq__answer">
      <p><a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> からご相談ください。<br />
また、 <a href="https://www.ec-cube.net/integrate/partner/" target="_blank">EC-CUBE インテグレートパートナー</a> にご相談いただくことも可能です。</p>
    </div>

  </section>


  <section class="p-db">
    <h2 class="p-faq__title" id="サイト移行">サイト移行</h2>
    <p class="p-faq__question">ダウンロード(2系、3系)からec-cube.coへ移行する手順を教えてほしい</p>
    <div class="p-faq__answer">
      <p><a href="https://www.ec-cube.co/pdf/migration_manual_flow.pdf" target="_blank">2、3系データ移行マニュアル</a> をご確認いただき、お手続きをお願いいたします。<br />
上記マニュアルには掲載されていない内容についてのご相談は、<a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> からご相談ください。</p>
    </div>
    <p class="p-faq__question">ec-cube.coからダウンロード版へ移行する手順を教えてほしい</p>
    <div class="p-faq__answer">
    <p>有料にてご提供が可能です。<br />
    <a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> から以下の情報を添えてご連絡ください。</p>
    <ul>
      <li>対象のサイトURL</li>
      <li>データ抜き出し希望日時</li>
    </ul>

    <p>お渡しする情報は以下となります。</p>
    <ul>
      <li>DB</li>
      <li>ソースコード</li>
      <li>商品画像</li>
    </ul>

    <p>ただしec-cube.co環境停止から1週間以上経過しますと、データ保管期限が切れてしまいお渡しすることができなくなります。<br />
    ご了承ください。</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="サイト規模">サイト規模</h2>
    <p class="p-faq__question">どれぐらいの規模向けのサービスでしょうか？</p>
    <div class="p-faq__answer">
     <p>小規模から中〜大規模サイトの開発・運用実績がございます。</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="セキュリティ">セキュリティ</h2>
    <p class="p-faq__question">SSL設置は可能でしょうか？</p>
    <div class="p-faq__answer">
      <p>常時SSL対応済です。</p>
    </div>
    <p class="p-faq__question">PCI DSSに準拠したサイト構築をしていく必要があるのですが、PCI DSSの要件に対応はしていますか？</p>
    <div class="p-faq__answer">
      <p>PCI DSSの要件に対応はしておりません。ご了承ください。<br />
カード情報非保持化につきましては <a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a> の決済プラグインが対応しております。</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="ダウンロード商品販売">ダウンロード商品販売</h2>
    <p class="p-faq__question">ダウンロード商品販売は可能でしょうか？</p>
    <div class="p-faq__answer">
      <p>可能です。ただし、1ファイル毎のファイルアップロード制限(10MB)があり、限界はございます。ご了承ください。<br />
回避策として他のCDNなど外部サービスを利用することをお勧めします。</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="データコンテンツ">データ/コンテンツ</h2>
    <p class="p-faq__question">本番で行ったテスト受注があり一括で消したいのですが、どうしたらいいでしょうか？</p>
    <div class="p-faq__answer">
      <p><a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a> にございます <a href="https://www.ec-cube.net/products/detail.php?product_id=2101" target="_blank">受注データ一括削除プラグイン for EC-CUBE4</a> を使用することで実現が可能です。</p>
    </div>
    <p class="p-faq__question">本番環境を最新にしたいのですが、DBダンプファイルなどの反映はお願いできますか？</p>
    <div class="p-faq__answer">
      <p>まずは<a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> からご相談ください。<br />
その際、以下の状況を共有いただけますとスムーズになります。ご協力お願いいたします。</p>
<ul>
  <li>調査対象のサイトのURL</li>
  <li>ご依頼内容：例)DBを最新化してほしい</li>
</ul>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="デザインテンプレート">デザインテンプレート</h2>
    <p class="p-faq__question">デザインテンプレートを適用したいのですが、ec-cube.coで使用可能なものを教えてもらえますか？</p>
    <div class="p-faq__answer">
      <p>利用可能なデザインテンプレート一覧は <a href="https://www.ec-cube.net/products/list.php?category_id=7&amp;ecversion=4.0" target="_blank">こちら</a> になります。<br />
ただし容量が10Mを超えるものはファイルアップロード時の制限でエラーとなりますのでご注意ください。</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="ドメイン">ドメイン</h2>
    <p class="p-faq__question">独自ドメインは使用できますか？SSL証明書の取得や設定はどうすればいいでしょうか？</p>
    <div class="p-faq__answer">
      <p>可能です。独自ドメイン切り替え作業と併せて設定いたします。<br />
流れについては <a href="https://www.ec-cube.net/user_data/packages/default/img/product/co/original_domain_setting.pdf" target="_blank">PDF：独自ドメイン設定の流れ</a> をご参照ください。</p>

<p>また、サブドメインのみ設定が可能です。ルートドメインは設定NGとなりますのでご注意ください。</p>

<div class="language-plaintext highlighter-rouge"><div class="highlight"><pre class="highlight"><code>NG： https://example.com
OK： https://www.example.com
OK： https://shop.example.com
</code></pre></div></div>
    </div>

  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="プラグイン">プラグイン</h2>
    <p class="p-faq__question">決済プラグインをインストールしたいのですが、ec-cube.coで使用可能なものを教えてもらえますか？</p>
    <div class="p-faq__answer">
      <p>利用可能な決済プラグイン一覧は <a href="https://www.ec-cube.net/products/list.php?ecversion=4.0&amp;category_id=3" target="_blank">こちら</a> になります。</p>

<p>ただし、 <a href="https://www.ec-cube.net/products/detail.php?product_id=1918" target="_blank">定期購入プラグイン「リピートキューブ」4系用</a> のみ、仕様上通常のインストールでは使用不可となります。<br />
個別で <a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> からご相談ください。</p>

<p>上記以外の決済プラグインを使用した場合、 <a href="https://www.ec-cube.co/pdf/term.pdf" target="_blank">ec-cube.co利用規約</a> に基づきサイトを停止することがございます。<br />
ご了承ください。</p>
    </div>
    <p class="p-faq__question">複数のプラグイン導入を検討していますが、サイトの処理速度の低下の可能性はありますか？</p>
    <div class="p-faq__answer">
      <p>プラグインを20以上インストールし運用しているサイトもございますので特に問題なしと考えられます。
ただし、各プラグインの実装の仕方によっては処理速度に影響が出ることもございます。</p>
    </div>
    <p class="p-faq__question">複数のプラグイン導入を検討していますが、競合発生によりサイトが正常に動作しなくなる可能性はありますか？</p>
    <div class="p-faq__answer">
      <p>選択したプラグインの組み合わせによっては競合が発生する可能性はございます。<br />
またバリエーションが多岐にわたる為、競合情報提供はできかねます。ご了承ください。</p>
    </div>
    <p class="p-faq__question">プラグインをインストール後、サーバーエラーとなってサイトへアクセスできなくなりました。どうしたらよいでしょうか？</p>
    <div class="p-faq__answer">
      <p>プラグインインストール時のトラブル(主にキャッシュ)によるものと考えられます。<br />
管理画面へのログインが困難となることもございますので、 <a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> からご相談ください。<br />
その際、以下の状況を共有いただけますとスムーズになります。ご協力お願いいたします。</p>
<ul>
  <li>調査対象のサイトのURL</li>
  <li>どのプラグインをインストールした後発生したのか</li>
  <li>いつ発生したか</li>
  <li>事象が発生する前に行った変更など(プラグインインストール以外)</li>
</ul>

<p>次回からの回避策として、手動でメンテナンスモードに切り替えてからプラグインインストール(アップデートも同様)を行うと、トラブルは起こりにくくなるかと存じます。ご検討ください。</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="メール">メール</h2>
    <p class="p-faq__question">商品購入メールが届かないとお客様から問い合わせがあったのですが、どうしたらよいでしょうか？</p>
    <div class="p-faq__answer">
      <p>上記に問題がない場合は <a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a> からご相談ください。<br />
      その際、以下の状況を共有いただけますとスムーズになります。ご協力お願いいたします。</p>
      <ul>
        <li>調査対象のサイトのURL</li>
        <li>どの注文に対して発生しているか</li>
        <li>いつ発生したか</li>
        <li>他の注文でも発生しているか</li>
        <li>事象が発生する前に行った変更など(思い当たる事があれば)</li>
      </ul>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="メールマガジン">メールマガジン</h2>
    <p class="p-faq__question">メールマガジンの配信はできますか？</p>
    <div class="p-faq__answer">
      <p>目安として 1万2千通/月 の配信を上限とさせていただいております。<br />
      上限を超える場合は外部サービスの利用をお願いいたします。</p>

      <p><a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a> よりプラグインをインストールしていただくことで実現可能です。</p>
      <ul>
        <li><a href="https://www.ec-cube.net/products/detail.php?product_id=1760" target="_blank">メルマガ管理プラグイン</a></li>
        <li><a href="https://www.ec-cube.net/products/detail.php?product_id=1859" target="_blank">かんたんメルマガ配信 x 本格メールフォームGreenFormプラグイン(4系)</a></li>
        <li><a href="https://www.ec-cube.net/products/detail.php?product_id=1940" target="_blank">EC-CUBE4用「PostCarrier for EC-CUBE」メルマガ配信プラグイン</a></li>
      </ul>

      <p>またメールマガジン用のテンプレートもございます。ご検討ください。</p>
      <ul>
        <li><a href="https://www.ec-cube.net/products/detail.php?product_id=2114" target="_blank">HTMLメールマガジンテンプレート No.EM001</a></li>
        <li><a href="https://www.ec-cube.net/products/detail.php?product_id=2115" target="_blank">HTMLメールマガジンテンプレート No.EM002</a></li>
      </ul>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="運用">運用</h2>
    <p class="p-faq__question">商品をまとめてサイトに登録したいのですが、どうしたらよいでしょうか？</p>
    <div class="p-faq__answer">
      <p>管理画面から商品CSVファイルを登録することで一括登録が可能です。<br />
登録可能件数に上限はございません。(商品1万件で検証済)</p>
    </div>
    <p class="p-faq__question">商品登録数やデータ容量の制限はありますか？</p>
    <div class="p-faq__answer">
     <p>制限はございません。</p>

    <p>ただし、以下の点にご留意ください。</p>
    <ul>
      <li>1度のデータアップロードは10Mの制限がございます。</li>
      <li>商品登録数の制限はありませんが、大量の商品登録を行うとサイトの動作パフォーマンスは低下する可能性がございます。</li>
    </ul>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="開発">開発</h2>
    <p class="p-faq__question">サイト開発を依頼したい場合、どうすればよいでしょうか？また、制作会社のご紹介はしていただけますでしょうか？</p>
    <div class="p-faq__answer">
      <p>ぜひ<a href="https://www.ec-cube.net/advisor/" target="_blank">EC-CUBEアドバイザー</a> にご相談ください。<br />
<a href="https://www.ec-cube.net/integrate/partner/" target="_blank">EC-CUBEインテグレートパートナー</a> にご相談いただくことも可能です。</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="外部サービス連携">外部サービス連携</h2>
    <p class="p-faq__question">外部サービスを使用したいのですが、どうすればよいでしょうか？</p>
    <div class="p-faq__answer">
      <p><a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a> より外部サービス連携プラグインをインストールしていただくほか、スタンダードプランをご契約いただいた後はカスタマイズ領域使用や独自プラグイン・独自デザインテンプレートで実現が可能です。</p>

      <p>ただし以下の点にご注意ください。</p>
      <ul>
        <li>決済機能につきましては、オーナーズストアでダウンロードできる決済のみ使用可能となります。</li>
        <li>独自プラグインや独自デザインテンプレートに関連する不具合対応等はサポート外となります。</li>
      </ul>

      <p>オーナーズストア以外でダウンロードした決済プラグインを使用した場合、 <a href="https://www.ec-cube.co/pdf/term.pdf" target="_blank">ec-cube.co利用規約</a> に基づきサイトを停止することがございます。ご了承ください。</p>
    </div>

  </section>

    <section class="p-db">
    <h2 class="p-faq__title" id="環境への接続">環境への接続</h2>
    <p class="p-faq__question">本番環境へ直接接続して操作はできますか？</p>
    <div class="p-faq__answer">
      <p>操作可能なものは以下に限定させていただいております。</p>
<ul>
  <li>管理画面</li>
  <li>スタンダードプランをご契約いただいた後にお渡しするコンソール</li>
</ul>

<p>画像を一括でアップロードしたい場合は、 <a href="https://www.ec-cube.net/products/detail.php?product_id=1957" target="_blank">商品画像一括アップロードプラグイン</a> を利用すれば可能です。</p>

<p>※SSH/telnet/SFTP/FTPなどを使用して環境へ直接接続することは不可となります。ご了承ください。</p>

    </div>
  </section>

    <section class="p-db">
    <h2 class="p-faq__title" id="監視">監視</h2>
    <p class="p-faq__question">リソース監視は実施されていますか？</p>
    <div class="p-faq__answer">
      <p>リソースについてはモニタリングを実施しております。<br />
その他実施しております監視につきましては、 <a href="http://localhost:4000/co/co_security#%E7%9B%A3%E8%A6%96%E3%83%A2%E3%83%8B%E3%82%BF%E3%83%AA%E3%83%B3%E3%82%B0" target="_blank">監視・モニタリング</a> をご参照ください。</p>
    </div>

  </section>

    <section class="p-db">
    <h2 class="p-faq__title" id="機能">機能</h2>
    <p class="p-faq__question">EC-CUBEの本体機能にはどんなものがありますか？</p>
    <div class="p-faq__answer">
      <p>以下のサイトをご参照ください。</p>
      <ul>
        <li><a href="https://www.ec-cube.net/product/functions.php" target="_blank">EC-CUBE4系・機能一覧</a></li>
        <li><a href="https://www.shiro8.net/manual4/v40x/index.html" target="_blank">EC-CUBE4 管理・運用 マニュアル</a></li>
      </ul>
    </div>
  </section>

    <section class="p-db">
    <h2 class="p-faq__title" id="決済">決済</h2>
    <p class="p-faq__question">全額ポイント利用による決済を実現したいのですが、どうすればよいでしょうか？</p>
    <div class="p-faq__answer">
<p>0円決済が不可の決済会社での実現方法でお問い合わせいただきました。<br />
決済会社を通して0円決済をするのではなく、支払い方法として「0件決済」や「ポイント全額決済」を登録すると実現可能かと存じます。</p>

    </div>
  </section>

    <section class="p-db">
    <h2 class="p-faq__title" id="自動アップデート">自動アップデート</h2>
    <p class="p-faq__question">本体の自動アップデートとは、どのような内容で更新されるのでしょうか？</p>
    <div class="p-faq__answer">
      <p><a href="https://github.com/EC-CUBE/ec-cube/tree/co/master" target="_blank">co/masterブランチ</a> に反映されたものが、本体のアップデート時に取り込まれます。</p>
    </div>
    <p class="p-faq__question">本体の自動アップデート前にどのような確認を行うのでしょうか？</p>
    <div class="p-faq__answer">
      <p><a href="https://github.com/EC-CUBE/ec-cube/tree/co/master" target="_blank">co/masterブランチ</a> に適用する内容を確認し、リリースを行っております。</p>
    </div>
     <p class="p-faq__question">本体の自動アップデート後、影響が出た場合どんな対応を行うのでしょうか？</p>
    <div class="p-faq__answer">
      <p>お問い合わせいただいた内容を元に調査し、復旧対応を行います。</p>
    </div>
    <p class="p-faq__question">自動アップデートはテスト環境(STG環境)も対象となるのでしょうか？</p>
    <div class="p-faq__answer">
    <p>はい、STG環境にも自動アップデートは適用されます。</p>
    </div>
    <p class="p-faq__question">毎週の自動アップデート時は、メンテナンス画面が表示されてサイトにアクセスできなくなるのでしょうか？</p>
    <div class="p-faq__answer">
      <p>いいえ、週次のメンテナンスはサイト無停止で実施いたします。</p>
    </div>
    <p class="p-faq__question">自動アップデート後は、毎回動作検証する必要がありますか？</p>
    <div class="p-faq__answer">
      <p>特に動作検証をお願いすることはございません。<br>
カスタマイズ等で気になる箇所がある場合は、動作検証をしていただくと安心かと存じます。</p>
    </div>
  </section>

    <section class="p-db">
    <h2 class="p-faq__title" id="障害対応">障害対応</h2>
    <p class="p-faq__question">問題が発生した場合、どんな体制で対応をしていただけるのでしょうか？</p>
    <div class="p-faq__answer">
      <p>お問い合わせをいただく、もしくはイーシーキューブ社でアラートを検知した後に調査・対応を行い回答いたします。</p>
    </div>
    <p class="p-faq__question">障害検知時に契約者や運営者にメールでお知らせは来るのでしょうか？</p>
    <div class="p-faq__answer">
      <p>個別にご要望いただいたタイミングでメール通知の設定をいたします。<br />
メールの設定は1サイトで1メールアドレスとさせていただきます。<br />
複数のメールアドレスの設定は不可となりますのでご了承ください。</p>
    </div>
  </section>

    <section class="p-db">
    <h2 class="p-faq__title" id="商材説明動画のアップロード">商材説明動画のアップロード</h2>
    <p class="p-faq__question">商材説明の動画をサイトにアップロードして商品詳細ページに埋め込みたいのですが、どうしたらよいでしょうか？</p>
    <div class="p-faq__answer">
      <p>可能です。ただし、1ファイル毎のファイルアップロード制限(10MB)があり、限界はございます。ご了承ください。<br />
回避策として他のCDNなど外部サービスを利用することをお勧めします。</p>
    </div>
  </section>

    <section class="p-db">
    <h2 class="p-faq__title" id="設定">設定</h2>
    <p class="p-faq__question">決済プラグインの設定で必要なのですが、送信元のグローバルIPは固定でしょうか？</p>
    <div class="p-faq__answer">
      <p>はい。固定です。 <br> スタンダードプランにお申し込みいただきますと、IPはコンソール画面からご確認いただけます。  </p>
      <p>トライアル申込後、 <a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a>お問い合わせください。 <br>その際、以下の状況を共有いただけますとスムーズになります。ご協力お願いいたします。</p>
      <ul>
        <li>対象のサイトのURL</li>
        <li>問い合わせ内容：IPを教えて欲しい</li>
      </ul>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="独自プラグインデザインテンプレート">独自プラグイン/デザインテンプレート</h2>
    <p class="p-faq__question">独自にプラグインや独自デザインテンプレートを作ってインストールしたいのですが良いでしょうか？</p>
    <div class="p-faq__answer">
      <p>はい、可能です。<br>
ですが、独自プラグインや独自テンプレートに関連する不具合対応等はサポート外となります。ご了承ください。</p>
<p><a href="https://www.ec-cube.net/owners/" target="_blank">オーナーズストア</a>に多種多様なプラグインやデザインテンプレートがございます。  
こちらのご利用もぜひご検討ください。  
</p>
    </div>
  </section>

  <section class="p-db">
    <h2 class="p-faq__title" id="問合わせ">問合わせ</h2>
    <p><a href="https://www.ec-cube.net/product/co/support.php" target="_blank">ec-cube.coに関するお問い合わせ</a>以外にも、目的に合わせてご活用ください。</p>
    <p>ちょっとした不具合解消や工夫を相談したい</p>
    <p><a href="https://xoops.ec-cube.net/" target="_blank">EC-CUBE開発コミュニティ</a></p>
    <p>ec-cube.coやEC-CUBE本体の技術情報を詳しく知りたい</p>
    <p><a href="https://doc4.ec-cube.net/" target="_blank">EC-CUBE 4.0 開発者向けドキュメント</a></p>
    <p>EC-CUBEに詳しい技術者に直接相談にのって欲しい </p>
    <p>各ユーザーグループやイベント・セミナーに参加してみてはいかがでしょうか。</p>
    <p><a href="https://www.ec-cube.net/news/#events_information" target="_blank">ニュース・イベント・コミュニティコンテンツ</a></p>  
    <p>サイト構築をお願いしたい</p>
    <p><a href="https://www.ec-cube.net/integrate/partner/" target="_blank">EC-CUBE インテグレートパートナー</a></p>
    <p>サイト構築をお願いしたいが、どのパートナーに依頼すればよいか迷う</p>
    <p><a href="https://www.ec-cube.net/advisor/" target="_blank">EC-CUBEアドバイザー</a></p>

  </section>


</section>
