---
title: EC-CUBE4 Web API プラグイン
keywords: plugin EC-CUBE4 Web API
tags: [plugin, eccube, eccube-api]
permalink: eccube-api4/schema

---

## スキーマ

GraphQLのスキーマは `bin/console eccube:api:dump-schema` コマンドで出力できます。

```graphql
type Authority {
  id: ID!
  name: String!
  sort_no: Int!
}

type Category {
  id: ID!
  name: String!
  hierarchy: Int!
  sort_no: Int!
  create_date: DateTime!
  update_date: DateTime!
  ProductCategories: [ProductCategory]
  Children: [Category]
  Parent: Category
  Creator: Member
}

type ClassCategory {
  id: ID!
  backend_name: String
  name: String!
  sort_no: Int!
  visible: Boolean!
  create_date: DateTime!
  update_date: DateTime!
  ClassName: ClassName
  Creator: Member
}

type ClassName {
  id: ID!
  backend_name: String
  name: String!
  sort_no: Int!
  create_date: DateTime!
  update_date: DateTime!
  ClassCategories: [ClassCategory]
  Creator: Member
}

type Country {
  id: ID!
  name: String!
  sort_no: Int!
}

type Customer {
  id: ID!
  name01: String!
  name02: String!
  kana01: String
  kana02: String
  company_name: String
  postal_code: String
  addr01: String
  addr02: String
  email: String!
  phone_number: String
  birth: DateTime
  first_buy_date: DateTime
  last_buy_date: DateTime
  buy_times: Float
  buy_total: Float
  note: String
  reset_expire: DateTime
  point: Float!
  create_date: DateTime!
  update_date: DateTime!
  CustomerFavoriteProducts: [CustomerFavoriteProduct]
  CustomerAddresses: [CustomerAddress]
  Orders: [Order]
  Status: CustomerStatus
  Sex: Sex
  Job: Job
  Country: Country
  Pref: Pref
}

type CustomerAddress {
  id: ID!
  name01: String!
  name02: String!
  kana01: String
  kana02: String
  company_name: String
  postal_code: String
  addr01: String
  addr02: String
  phone_number: String
  create_date: DateTime!
  update_date: DateTime!
  Customer: Customer
  Country: Country
  Pref: Pref
}

type CustomerConnection {
  edges: [CustomerEdge]
  nodes: [Customer]
  pageInfo: CustomerPageInfo!
  totalCount: Int!
}

type CustomerEdge {
  node: Customer
}

type CustomerFavoriteProduct {
  id: ID!
  create_date: DateTime!
  update_date: DateTime!
  Customer: Customer
  Product: Product
}

type CustomerOrderStatus {
  id: ID!
  name: String!
  sort_no: Int!
}

type CustomerPageInfo {
  hasNextPage: Boolean!
  hasPreviousPage: Boolean!
}

type CustomerStatus {
  id: ID!
  name: String!
  sort_no: Int!
}

"""
The `DateTime` scalar type represents time data, represented as an ISO-8601 encoded UTC date string.
"""
scalar DateTime

type Delivery {
  id: ID!
  name: String
  service_name: String
  description: String
  confirm_url: String
  sort_no: Int
  visible: Boolean!
  create_date: DateTime!
  update_date: DateTime!
  PaymentOptions: [PaymentOption]
  DeliveryFees: [DeliveryFee]
  DeliveryTimes: [DeliveryTime]
  Creator: Member
  SaleType: SaleType
}

type DeliveryDuration {
  id: ID!
  name: String
  duration: Int!
  sort_no: Int!
}

type DeliveryFee {
  id: ID!
  fee: Float!
  Delivery: Delivery
  Pref: Pref
}

type DeliveryTime {
  id: ID!
  delivery_time: String!
  sort_no: Int!
  visible: Boolean!
  create_date: DateTime!
  update_date: DateTime!
  Delivery: Delivery
}

type DeviceType {
  id: ID!
  name: String!
  sort_no: Int!
}

type Job {
  id: ID!
  name: String!
  sort_no: Int!
}

type MailHistory {
  id: ID!
  send_date: DateTime
  mail_subject: String
  mail_body: String
  mail_html_body: String
  Order: Order
  Creator: Member
}

type Member {
  id: ID!
  name: String
  department: String
  login_id: String!
  sort_no: Int!
  create_date: DateTime!
  update_date: DateTime!
  login_date: DateTime
  Work: Work
  Authority: Authority
  Creator: Member
}

type Mutation {
  updateProductStock(
    """商品コード"""
    code: String!

    """在庫数（在庫無制限の場合、0以上の数値を指定）"""
    stock: Int

    """在庫無制限（無制限は true 、制限は false を指定）"""
    stock_unlimited: Boolean!
  ): ProductClass
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

type Order {
  id: ID!
  pre_order_id: String
  order_no: String
  message: String
  name01: String!
  name02: String!
  kana01: String
  kana02: String
  company_name: String
  email: String
  phone_number: String
  postal_code: String
  addr01: String
  addr02: String
  birth: DateTime
  subtotal: Float!
  discount: Float!
  delivery_fee_total: Float!
  charge: Float!
  tax: Float!
  total: Float!
  payment_total: Float!
  payment_method: String
  note: String
  create_date: DateTime!
  update_date: DateTime!
  order_date: DateTime
  payment_date: DateTime
  currency_code: String
  complete_message: String
  complete_mail_message: String
  add_point: Float!
  use_point: Float!
  OrderItems: [OrderItem]
  Shippings: [Shipping]
  MailHistories: [MailHistory]
  Customer: Customer
  Country: Country
  Pref: Pref
  Sex: Sex
  Job: Job
  Payment: Payment
  DeviceType: DeviceType
  CustomerOrderStatus: CustomerOrderStatus
  OrderStatusColor: OrderStatusColor
  OrderStatus: OrderStatus
}

type OrderConnection {
  edges: [OrderEdge]
  nodes: [Order]
  pageInfo: OrderPageInfo!
  totalCount: Int!
}

type OrderEdge {
  node: Order
}

type OrderItem {
  id: ID!
  product_name: String!
  product_code: String
  class_name1: String
  class_name2: String
  class_category_name1: String
  class_category_name2: String
  price: Float!
  quantity: Float!
  tax: Float!
  tax_rate: Float!
  tax_adjust: Float!
  tax_rule_id: Int
  currency_code: String
  processor_name: String
  point_rate: Float
  Order: Order
  Product: Product
  ProductClass: ProductClass
  Shipping: Shipping
  RoundingType: RoundingType
  TaxType: TaxType
  TaxDisplayType: TaxDisplayType
  OrderItemType: OrderItemType
}

type OrderItemType {
  id: ID!
  name: String!
  sort_no: Int!
}

type OrderPageInfo {
  hasNextPage: Boolean!
  hasPreviousPage: Boolean!
}

type OrderStatus {
  display_order_count: Boolean!
  id: ID!
  name: String!
  sort_no: Int!
}

type OrderStatusColor {
  id: ID!
  name: String!
  sort_no: Int!
}

type Payment {
  id: ID!
  method: String
  charge: Float
  rule_max: Float
  sort_no: Int
  fixed: Boolean!
  payment_image: String
  rule_min: Float
  method_class: String
  visible: Boolean!
  create_date: DateTime!
  update_date: DateTime!
  PaymentOptions: [PaymentOption]
  Creator: Member
}

type PaymentOption {
  delivery_id: ID!
  payment_id: ID!
  Delivery: Delivery
  Payment: Payment
}

type Pref {
  id: ID!
  name: String!
  sort_no: Int!
}

type Product {
  id: ID!
  name: String!
  note: String
  description_list: String
  description_detail: String
  search_word: String
  free_area: String
  create_date: DateTime!
  update_date: DateTime!
  ProductCategories: [ProductCategory]
  ProductClasses: [ProductClass]
  ProductImage: [ProductImage]
  ProductTag: [ProductTag]
  CustomerFavoriteProducts: [CustomerFavoriteProduct]
  Creator: Member
  Status: ProductStatus
}

type ProductCategory {
  product_id: ID!
  category_id: ID!
  Product: Product
  Category: Category
}

type ProductClass {
  id: ID!
  code: String
  stock: Float
  stock_unlimited: Boolean!
  sale_limit: Float
  price01: Float
  price02: Float!
  delivery_fee: Float
  visible: Boolean!
  create_date: DateTime!
  update_date: DateTime!
  currency_code: String
  point_rate: Float
  ProductStock: ProductStock
  TaxRule: TaxRule
  Product: Product
  SaleType: SaleType
  ClassCategory1: ClassCategory
  ClassCategory2: ClassCategory
  DeliveryDuration: DeliveryDuration
  Creator: Member
}

type ProductConnection {
  edges: [ProductEdge]
  nodes: [Product]
  pageInfo: ProductPageInfo!
  totalCount: Int!
}

type ProductEdge {
  node: Product
}

type ProductImage {
  id: ID!
  file_name: String!
  sort_no: Int!
  create_date: DateTime!
  Product: Product
  Creator: Member
}

type ProductPageInfo {
  hasNextPage: Boolean!
  hasPreviousPage: Boolean!
}

type ProductStatus {
  id: ID!
  name: String!
  sort_no: Int!
}

type ProductStock {
  id: ID!
  stock: Float
  create_date: DateTime!
  update_date: DateTime!
  ProductClass: ProductClass
  Creator: Member
}

type ProductTag {
  id: ID!
  create_date: DateTime!
  Product: Product
  Tag: Tag
  Creator: Member
}

type Query {
  customer(id: ID!): Customer
  customers(
    """会員ID・メールアドレス・お名前"""
    multi: String = null

    """会員種別"""
    customer_status: [String] = ["1", "2"]

    """性別"""
    sex: [String] = null

    """誕生月"""
    birth_month: String = null

    """誕生日(開始)"""
    birth_start: String = null

    """誕生日(終了)"""
    birth_end: String = null

    """都道府県"""
    pref: String = null

    """電話番号"""
    phone_number: String = null

    """購入商品名"""
    buy_product_name: String = null

    """購入金額(開始)"""
    buy_total_start: String = null

    """購入金額(終了)"""
    buy_total_end: String = null

    """購入件数(開始)"""
    buy_times_start: Int = null

    """購入件数(終了)"""
    buy_times_end: Int = null

    """登録日(開始)"""
    create_date_start: String = null

    """登録日(開始)"""
    create_datetime_start: DateTime = null

    """登録日(終了)"""
    create_date_end: String = null

    """登録日(終了)"""
    create_datetime_end: DateTime = null

    """更新日(開始)"""
    update_date_start: String = null

    """更新日(開始)"""
    update_datetime_start: DateTime = null

    """更新日(終了)"""
    update_date_end: String = null

    """更新日(終了)"""
    update_datetime_end: DateTime = null

    """最終購入日(開始)"""
    last_buy_start: String = null

    """最終購入日(開始)"""
    last_buy_datetime_start: DateTime = null

    """最終購入日(終了)"""
    last_buy_end: String = null

    """最終購入日(終了)"""
    last_buy_datetime_end: DateTime = null

    """ページ番号"""
    page: Int = 1

    """ページあたりの取得数の上限"""
    limit: Int = 50
  ): CustomerConnection
  hello: String
  order(id: ID!): Order
  orders(
    """注文番号・お名前・会社名・メールアドレス・電話番号"""
    multi: String = null

    """対応状況"""
    status: [String] = null

    """注文者名"""
    name: String = null

    """注文者名(カナ)"""
    kana: String = null

    """注文者会社名"""
    company_name: String = null

    """メールアドレス"""
    email: String = null

    """注文番号"""
    order_no: String = null

    """電話番号"""
    phone_number: String = null

    """お問い合わせ番号"""
    tracking_number: String = null

    """出荷メール"""
    shipping_mail: [String] = null

    """支払方法"""
    payment: [String] = null

    """注文日(開始)"""
    order_date_start: String = null

    """注文日(開始)"""
    order_datetime_start: DateTime = null

    """注文日(終了)"""
    order_date_end: String = null

    """注文日(終了)"""
    order_datetime_end: DateTime = null

    """入金日(開始)"""
    payment_date_start: String = null

    """入金日(開始)"""
    payment_datetime_start: DateTime = null

    """入金日(終了)"""
    payment_date_end: String = null

    """入金日(終了)"""
    payment_datetime_end: DateTime = null

    """更新日(開始)"""
    update_date_start: String = null

    """更新日(開始)"""
    update_datetime_start: DateTime = null

    """更新日(終了)"""
    update_date_end: String = null

    """更新日(終了)"""
    update_datetime_end: DateTime = null

    """お届け日(開始)"""
    shipping_delivery_date_start: String = null

    """お届け日(開始)"""
    shipping_delivery_datetime_start: DateTime = null

    """お届け日(終了)"""
    shipping_delivery_date_end: String = null

    """お届け日(終了)"""
    shipping_delivery_datetime_end: DateTime = null

    """購入金額(開始)"""
    payment_total_start: String = null

    """購入金額(終了)"""
    payment_total_end: String = null

    """購入商品名"""
    buy_product_name: String = null

    """ページ番号"""
    page: Int = 1

    """ページあたりの取得数の上限"""
    limit: Int = 50
  ): OrderConnection
  product(id: ID!): Product
  products(
    """商品名・商品ID・商品コード"""
    id: String = null

    """カテゴリ"""
    category_id: String = null

    """公開ステータス"""
    status: [String] = ["1", "2"]

    """在庫数"""
    stock: [String] = null

    """登録日(開始)"""
    create_date_start: String = null

    """登録日(開始)"""
    create_datetime_start: DateTime = null

    """登録日(終了)"""
    create_date_end: String = null

    """登録日(終了)"""
    create_datetime_end: DateTime = null

    """更新日(開始)"""
    update_date_start: String = null

    """更新日(開始)"""
    update_datetime_start: DateTime = null

    """更新日(終了)"""
    update_date_end: String = null

    """更新日(終了)"""
    update_datetime_end: DateTime = null

    """ページ番号"""
    page: Int = 1

    """ページあたりの取得数の上限"""
    limit: Int = 50
  ): ProductConnection
}

type RoundingType {
  id: ID!
  name: String!
  sort_no: Int!
}

type SaleType {
  id: ID!
  name: String!
  sort_no: Int!
}

type Sex {
  id: ID!
  name: String!
  sort_no: Int!
}

type Shipping {
  id: ID!
  name01: String!
  name02: String!
  kana01: String
  kana02: String
  company_name: String
  phone_number: String
  postal_code: String
  addr01: String
  addr02: String
  shipping_delivery_name: String
  time_id: Int
  shipping_delivery_time: String
  shipping_delivery_date: DateTime
  shipping_date: DateTime
  tracking_number: String
  note: String
  sort_no: Int
  create_date: DateTime!
  update_date: DateTime!
  mail_send_date: DateTime
  Order: Order
  OrderItems: [OrderItem]
  Country: Country
  Pref: Pref
  Delivery: Delivery
  Creator: Member
}

type Tag {
  id: ID!
  name: String!
  sort_no: Int!
  ProductTag: [ProductTag]
}

type TaxDisplayType {
  id: ID!
  name: String!
  sort_no: Int!
}

type TaxRule {
  id: ID!
  tax_rate: Float!
  tax_adjust: Float!
  apply_date: DateTime!
  create_date: DateTime!
  update_date: DateTime!
  ProductClass: ProductClass
  Creator: Member
  Country: Country
  Pref: Pref
  Product: Product
  RoundingType: RoundingType
}

type TaxType {
  id: ID!
  name: String!
  sort_no: Int!
}

type Work {
  id: ID!
  name: String!
  sort_no: Int!
}
```
