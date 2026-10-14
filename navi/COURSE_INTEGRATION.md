# 買い切りコース購入

14コース共通で、料金別の入口を用意。すべて税込・1回払い。

| 入口 | 対象 | 各コースの料金 |
|---|---|---:|
| `/navi/course.php` | 既存の特別価格 | 2,980円 |
| `/navi/course_student.php` | 生徒・先生（期間終了後） | 3,980円 |
| `/navi/course_general.php` | 一般 | 4,980円 |

価格は入口PHPの設定で固定する。GET/POSTの `price` や `pricing_plan` は使用しない。
3ページとも同じ商品コード・決済処理を利用する。フォーム送信、確認画面の戻るリンクでも料金別の入口を維持する。
終了日は未指定のため、日付での自動切り替えは実装しない。呼び出し元のNAVIで、対象者と案内期間に応じてリンク先を選ぶ。
本変更は対象者の資格判定を追加しない。各ページは引き続き既存JWTで購入者を確認する。

NAVIから既存JWTを `token`、戻り先を `callback`、環境を `navi_mode=prod|stg` で渡す。
`course=course_tarot` のように初期選択できる。画面表示だけならJWTは不要。購入にはJWTが必要。

呼び出し例（一般用）：

```text
https://school.heartf.com/navi/course_general.php?token=<既存JWT>&callback=my_courses&course=course_tarot
```

生徒・先生用は上記のパスを `course_student.php` に替え、同じパラメータを渡す。
購入完了通知のJSON項目は引き続き `user_id`, `price`, `item_name`, `transaction_id`, `timestamp`, `hash`。
料金区分によって `price` が2980/3980/4980となり、商品コードは同じ占術なら共通。

## 処理の流れ

1. 料金別の入口でコース選択、CSRF・JWT検証。金額はサーバー側の料金設定で固定。
2. `course_checkout.php` で購入内容確認。
3. 既存 `kanaeru/index.php` の都度クレジット購入と同じ `order.pl` へPOST。
4. 既存 `/kanaeru/rep.php` へ戻り、同じJSON形式・署名で `https://kanaeru.heartf.com/api/creditsadd` へ `item_name` を通知。
5. API正常応答後は既存 `https://kanaeru.heartf.com/credits/thanks` へ戻る。

継続課金パラメータは送信しない。定額契約テーブルと `teigaku.php` は変更しない。
`course_checkout.php` は直接アクセスを拒否する。

`item_name` は下表の商品コードを使う。Kanaeru API側でのコード処理は外部実装のため、実環境で受入確認が必要。
購入完了通知は、指定された既存の単発処理 `/kanaeru/rep.php` を利用し、本番Kanaeru API宛てになる。JWT検証キーは既存NAVIのローカル設定を使用（既存Kanaeru都度購入のキーと一致）。
`navi_mode=stg` を渡すだけではテスト決済にならない。実課金テストは実施していない。

金額・通知パラメータの回帰確認はPHP 8で `php navi/tests/course_payment_test.php` を実行する。外部への決済要求・API送信は行わない。

| 商品コード | 表示名 |
|---|---|
| course_tarot | タロット |
| course_palmistry | 手相 |
| course_western_astrology | 西洋占星術 |
| course_numerology | 数秘術 |
| course_ziwei | 紫微斗数 |
| course_four_pillars | 四柱推命 |
| course_sanmeigaku | 算命学 |
| course_daneki | 断易 |
| course_shueki | 周易 |
| course_kyusei | 九星気学 |
| course_name_divination | 姓名判断 |
| course_mayan_calendar | マヤ暦 |
| course_lenormand | ルノルマンカード |
| course_sukuyo | 宿曜占星術 |
