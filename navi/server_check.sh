#!/usr/bin/env bash
set -eu

for file in config.php subscription_lib.php teigaku.php receive.php cancel.php cancel_receive.php; do
    php -l "$file"
done

echo "PHP構文確認完了"
