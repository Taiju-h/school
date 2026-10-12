# 川さんへのNAVI側対応依頼

定額決済は、既存の都度購入とは別の入口で連携する方針になりました。
NAVI側では次の対応をお願いします。

## 1. メニュー追加

既存のクレジット都度購入7商品は変更しません。

ログイン中のユーザーが利用できる場所に、次の2リンクを追加してください。

### 定額コース申込み

```text
https://school.heartf.com/navi/
  ?token=<現在ログイン中ユーザーのJWT>
  &callback=<現在のcallback>
  &navi_mode=<prod|stg>
```

金額は渡しません。学校側画面で、980円・1,980円・2,980円・3,980円から選択します。
既に契約中の場合は、現在コースと変更届の画面が表示されます。

### 定額コース解約

```text
https://school.heartf.com/navi/cancel.php
  ?token=<現在ログイン中ユーザーのJWT>
```

## 2. `/api/creditsadd` の定額決済対応

学校側から、初回および毎月の決済結果がPOSTされます。
既存の `hash`・`key` 検証方式は変更しません。

成功時の金額と付与数：

| price | 付与数 |
|---:|---:|
| 980 | 16 |
| 1980 | 34 |
| 2980 | 54 |
| 3980 | 72 |

処理条件：

- `subscription=1` のときは上の固定表を使用
- `result=success` の場合だけ付与
- `result=failure` の場合は付与しない
- 同じ `transaction_id` は再付与しない
- 受信した `credits` ではなく、`price` の固定表から付与数を決定
- 正常処理と処理済み再送は、HTTP 2xxで `{"ok":true}` または `SuccessOK` を返す

署名式：

```text
sha256(transaction_id + ":" + result + ":" + price + ":" + timestamp + ":" + "[server-local secret]")
```

詳細な全項目は `navi_recurring_link_spec.md` を参照してください。
