テレコム定額決済一式
======================

対象ファイル
------------
teigaku.php          定額コース選択・初回決済開始
receive.php          ブラウザ戻り・テレコム決済通知の共用
cancel.php           NAVIから解約手続きを開始
cancel_receive.php   テレコム退会通知
subscription_lib.php 共通処理
config.php           URL・キー設定
install.sql          DBテーブル作成・カラム追加
db_config.php.example DB設定見本

設置手順
--------
1. 現在の /navi をバックアップする。
2. install.sqlを対象DBで実行する。
3. db_config.php.exampleをdb_config.phpへコピーし、既存DB情報を設定する。
4. PHPファイルを /navi に置く。
5. config.phpのNAVI停止API URLを、NAVI側API完成後に設定する。
6. php -l で全PHPの構文確認を行う。

確定プラン
----------
月額980円  : 16クレジット
月額1980円 : 34クレジット
月額2980円 : 54クレジット
月額3980円 : 72クレジット

重要事項
--------
- rebill_param_idは送信していません。テレコム側の自動設定を使用します。
- receive.phpは、テレコムPOSTを先に判定し、それ以外で申込セッションがあればブラウザ戻りとして処理します。
- settle_uuidにUNIQUE制約を付け、クレジットの二重付与を防止します。
- NAVIのクレジット残高が正本です。学校側DBは契約状態・決済通知・API処理状態だけを管理します。
- cancel_receive.phpのNAVI停止API URLだけは、現在の資料に確定URLがないためconfig.phpで未設定です。
