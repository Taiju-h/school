#!/usr/bin/env bash
set -eu

REPO_ROOT='/home/heartf/heartf.com/public_html/school'
SOURCE_DIR="$REPO_ROOT/.deploy/school"
RUNTIME_DIR="$REPO_ROOT/__deploy"
STATE_DIR='/home/heartf/.school-deployer'
PASSWORD_FILE='/home/heartf/.htpasswd_school_deploy'
DEPLOY_USER="${1:-taiju}"

if [ ! -d "$REPO_ROOT/.git" ]; then
    echo "ERROR: Git repository not found: $REPO_ROOT"
    exit 1
fi

if [ ! -f "$SOURCE_DIR/index.php" ] || [ ! -f "$SOURCE_DIR/.htaccess" ]; then
    echo "ERROR: Deployer source files are missing: $SOURCE_DIR"
    exit 1
fi

if ! command -v php >/dev/null 2>&1; then
    echo 'ERROR: PHP CLI command was not found.'
    exit 1
fi

if ! command -v htpasswd >/dev/null 2>&1; then
    echo 'ERROR: htpasswd command was not found.'
    exit 1
fi

php -l "$SOURCE_DIR/index.php"

install -d -m 755 "$RUNTIME_DIR"
install -m 644 "$SOURCE_DIR/index.php" "$RUNTIME_DIR/index.php"
install -m 644 "$SOURCE_DIR/.htaccess" "$RUNTIME_DIR/.htaccess"

install -d -m 700 "$STATE_DIR"
touch "$STATE_DIR/allowed-ips.txt" "$STATE_DIR/history.jsonl" "$STATE_DIR/deploy.lock"
chmod 600 "$STATE_DIR/allowed-ips.txt" "$STATE_DIR/history.jsonl" "$STATE_DIR/deploy.lock"

if [ -f "$PASSWORD_FILE" ]; then
    echo "Updating Basic authentication user: $DEPLOY_USER"
    htpasswd "$PASSWORD_FILE" "$DEPLOY_USER"
else
    echo "Creating Basic authentication user: $DEPLOY_USER"
    htpasswd -c "$PASSWORD_FILE" "$DEPLOY_USER"
fi
chmod 600 "$PASSWORD_FILE"

echo 'OK: SCHOOL Deployer installed.'
echo 'URL: https://school.heartf.com/__deploy/'
