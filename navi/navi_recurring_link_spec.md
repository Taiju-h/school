# NAVI側 定額コースリンク仕様

## NAVI画面の変更

NAVIの `/credits?callback=my` にある既存の都度購入7商品は変更しません。
NAVIには「定額コース申込み」と「定額コース解約」の入口を追加します。

申込み：

```text
https://school.heartf.com/navi/
  ?token=<現在ログイン中ユーザーのJWT>
  &callback=<現在のcallback>
  &navi_mode=<prod|stg>
```

例：

```text
https://school.heartf.com/navi/?token=...&callback=my&navi_mode=prod
```

解約：

```text
https://school.heartf.com/navi/cancel.php
  ?token=<現在ログイン中ユーザーのJWT>
```

金額とコースは `index.php` 内で選択します。NAVIから金額、クレジット数、
`rebill_param_id` を渡す必要はありません。

学校側画面は、契約状態により次のように切り替わります。

- 未契約：金額を選び、新規申込みへ進む
- 契約中：現在金額を表示し、別金額の変更届を登録する

テレコムの「継続ユーザー情報変更」では `rebill_param_id` が使用不可のため、
変更届の時点ではテレコム契約を直接上書きしません。

## NAVIクレジット付与API

学校側の `receive.php` から、決済ごとに以下へPOSTします。

```text
本番: https://navi.uranai.pw/api/creditsadd
STG : https://navi-stg.v-mm.com/api/creditsadd
```

送信項目：

| 項目 | 内容 |
|---|---|
| `user_id` | JWTに入っていたNAVIユーザーID |
| `transaction_id` | テレコムの `settle_uuid` |
| `result` | `success` または `failure` |
| `price` | 決済金額 |
| `credits` | 成功時の付与予定数。失敗時は0 |
| `timestamp` | UNIX時刻 |
| `hash` | 下記のSHA-256署名 |
| `key` | 既存連携で使用している設定キー |
| `subscription` | 固定値 `1` |

署名式は既存連携と同じです。

```text
sha256(transaction_id + ":" + result + ":" + price + ":" + timestamp + ":" + "[server-local secret]")
```

NAVI側の処理：

1. `key`、`timestamp`、`hash` を検証します。
2. `transaction_id` が処理済みなら、再付与せず正常応答を返します。
3. `result=success` の場合だけ、`price` から次の固定表で付与数を決めます。
4. 受信した `credits` は照合に使い、付与数の決定には使いません。
5. `result=failure` の場合は付与しません。

| price | 付与数 |
|---:|---:|
| 980 | 16 |
| 1980 | 34 |
| 2980 | 54 |
| 3980 | 72 |

正常時はHTTP 2xxで、次のいずれかを返してください。

```json
{"ok":true}
```

または：

```text
SuccessOK
```

NAVIが正常応答を返さない場合、学校側はテレコムへ `SuccessOK` を返しません。
その場合はテレコムからの再送で、NAVI通知を再試行します。

## 変更しないもの

- 既存の都度購入用 `/navi/index.php`
- 660円から30,000円までの既存7商品
- 既存のJWT形式
- 既存の `hash`・`key` 方式

## 元ソースについて

今回受領した「貼り付けられたテキスト（1点）(30).txt」は、Nuxtがブラウザへ出力した生成後HTMLです。
入口ボタンを追加するには、NAVI側の `/credits` ページまたはメニューを実装している
Vue/Nuxtコンポーネントの元ソースが必要です。
