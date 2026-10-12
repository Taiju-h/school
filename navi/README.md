# NAVI × テレコムクレジット 定額決済

既存の都度購入を壊さず追加する、定額決済一式です。

## ファイル

- `index.php` - NAVIからJWT・callbackを受け取り、新規申込みまたは変更届を表示
- `teigaku.php` - 旧案URL互換用。`index.php`を読み込み
- `receive.php` - 初回・毎月の決済結果を受け、NAVIへ付与通知
- `rep.php` - 初回決済後のブラウザ戻り先
- `cancel.php` - ユーザー向け解約開始画面
- `cancel_receive.php` - テレコムからの解約完了通知
- `subscription_config.php` - コース・URL・署名設定
- `subscription_lib.php` - 共通処理
- `schema.sql` - DB追加項目とイベント台帳
- `migration_plan_changes.sql` - 既存導入済みDBへ変更届テーブルだけ追加
- `navi_callback_verify.php` - NAVI側の戻り値検証例
- `navi_recurring_link_spec.md` - NAVI側の完全な連携仕様
- `kawa_san_request.md` - 川さんへ渡す作業依頼の要約

## 固定URL

- 定額申込み・変更届：`https://school.heartf.com/navi/`
- 決済通知：`https://school.heartf.com/navi/receive.php`
- 決済後：`https://school.heartf.com/navi/rep.php`
- 解約開始：`https://school.heartf.com/navi/cancel.php`
- 解約通知：`https://school.heartf.com/navi/cancel_receive.php`

## 導入順

1. 新規導入では、`heartf_school2` を選択し、`schema.sql` を一度だけ実行します。
   既に旧版の `schema.sql` を実行済みなら、`migration_plan_changes.sql` だけ実行します。
2. 既存の `db_config.php` はそのまま残します。
3. PHPファイルを `/navi/` へ配置します。既存の都度購入用 `index.php` は上書きしません。
4. まずステージングで980円コースをテストします。
5. `navi_subscription_events` とNAVI側の実残高を確認します。

## NAVIから申込み画面へ渡す値

```text
https://school.heartf.com/navi/
  ?token=<NAVIのJWT>
  &callback=my
  &navi_mode=stg
```

NAVIから金額は渡しません。`index.php` 内でユーザーが4コースから選択し、
サーバー側の固定表でテレコム登録済みの `rebill_param_id` と毎月付与数に変換します。

| 金額 | rebill_param_id | 毎月付与 |
|---:|---|---:|
| 980 | `1month980yen` | 16 |
| 1,980 | `1month1980yen` | 34 |
| 2,980 | `1month2980yen` | 54 |
| 3,980 | `1month3980yen` | 72 |

## 重要な動作

- 成功は `rel=yes`、失敗は `rel=no` で判断します。
- `settle_uuid` をイベント台帳の一意キーにして二重付与を防ぎます。
- 初回も毎月もブラウザ遷移ではなく `receive.php` がNAVIへ付与通知します。
- 学校側では契約状態と決済イベントだけを管理し、クレジット残高はNAVIを正本とします。
- DB処理またはNAVI通知に失敗した場合は `SuccessOK` を返さず、テレコムの再送を利用します。
- NAVIは `transaction_id` を一意キーとして、再送時の二重付与を防ぎます。
- 解約通知は同じ会員IDについて何度届いても安全です。
- NAVIへの戻り値は、既に動作確認済みの `hash`・`key` 方式を変更せず維持します。

## 継続決済の表示

`index.php` には、テレコムクレジットから指定された以下の3項目を明記しています。

1. 毎月自動的に課金されること
2. NAVIの「定額コース解約」画面から解約すること
3. 次回課金日の前日までに解約手続きを完了すること

テレコムクレジットからのメールでは、同じ内容を特定商取引法に基づく表示にも
掲載するよう指定されています。`tokusho_subscription_notice.html` の文面を
既存の特商法ページにも追加してください。

## 既存のクレジット購入ページとの関係

NAVIの `/credits?callback=my` に現在表示されている以下の7商品は都度購入です。

| クレジット | 金額 |
|---:|---:|
| 12 | 660円 |
| 30 | 1,650円 |
| 60 | 3,300円 |
| 100 | 5,500円 |
| 200 | 10,800円 |
| 300 | 15,600円 |
| 600 | 30,000円 |

これらのボタンと、遷移先である既存の `/navi/index.php` は変更しません。
NAVIには定額申込みへの入口を1個だけ追加し、`/navi/` へ遷移させます。
NAVI側の具体的な受け渡しは `navi_recurring_link_spec.md` を参照してください。

## 契約中のコース変更

- 未契約の場合：4コースから選択後、テレコムの新規申込みへ進みます。
- 契約中の場合：現在のコースを表示し、別コースの「変更届」を登録できます。
- 変更届は `navi_subscription_plan_changes` に保存し、管理者メールへ通知します。
- テレコムの仕様では「継続ユーザー情報変更」に `rebill_param_id` を送信できません。
  このため、変更届の登録時点では現在契約を上書きしません。
- テレコム側で変更処理が完了するまでは、現在の金額と付与数が継続します。

## NAVIへのクレジット付与

`receive.php` は決済通知を検証した後、NAVIの `/api/creditsadd` へ通知します。
NAVI側は金額から付与数を再計算し、`transaction_id` が未処理の場合だけ付与します。
リクエストと正常応答の仕様は `navi_recurring_link_spec.md` に記載しています。
