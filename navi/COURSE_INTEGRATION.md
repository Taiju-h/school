# 買い切りコース購入

入口は `/navi/course.php`。14コースは各税込2,980円、1回払い。

NAVIから既存JWTを `token`、戻り先を `callback`、環境を `navi_mode=prod|stg` で渡す。
`course=course_tarot` のように初期選択できる。画面表示だけならJWTは不要。購入にはJWTが必要。

## 処理の流れ

1. `course.php` でコース選択、CSRF・JWT検証。金額はサーバー固定の2,980円。
2. `course_checkout.php` で購入内容確認。
3. 既存 `kanaeru/index.php` の都度クレジット購入と同じ `order.pl` へPOST。
4. 既存 `/kanaeru/rep.php` へ戻り、同じJSON形式・署名で `https://kanaeru.heartf.com/api/creditsadd` へ `item_name` を通知。
5. API正常応答後は既存 `https://kanaeru.heartf.com/credits/thanks` へ戻る。

継続課金パラメータは送信しない。定額契約テーブルと `teigaku.php` は変更しない。
`course_checkout.php` は直接アクセスを拒否する。

`item_name` は下表の商品コードを使う。Kanaeru API側でのコード処理は外部実装のため、実環境で受入確認が必要。
購入完了通知は、指定された既存の単発処理 `/kanaeru/rep.php` を利用し、本番Kanaeru API宛てになる。JWT検証キーは既存NAVIのローカル設定を使用（既存Kanaeru都度購入のキーと一致）。
`navi_mode=stg` を渡すだけではテスト決済にならない。実課金テストは実施していない。

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
