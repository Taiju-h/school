-- MySQL 8.x 用
-- 実行前に必ずバックアップしてください。

CREATE TABLE IF NOT EXISTS subscription_plans (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    plan_code VARCHAR(32) NOT NULL,
    plan_name VARCHAR(100) NOT NULL,
    monthly_amount INT UNSIGNED NOT NULL,
    credits_per_cycle INT UNSIGNED NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_subscription_plans_code (plan_code),
    UNIQUE KEY uq_subscription_plans_amount (monthly_amount)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO subscription_plans
    (plan_code, plan_name, monthly_amount, credits_per_cycle, is_active)
VALUES
    ('1MONTH_980',  '1ヶ月980円コース',  980,  16, 1),
    ('1MONTH_1980', '1ヶ月1980円コース', 1980, 34, 1),
    ('1MONTH_2980', '1ヶ月2980円コース', 2980, 54, 1),
    ('1MONTH_3980', '1ヶ月3980円コース', 3980, 72, 1)
ON DUPLICATE KEY UPDATE
    plan_name = VALUES(plan_name),
    credits_per_cycle = VALUES(credits_per_cycle),
    is_active = VALUES(is_active);

ALTER TABLE navi_user
    ADD COLUMN IF NOT EXISTS plan_id BIGINT UNSIGNED NULL,
    ADD COLUMN IF NOT EXISTS telecom_member_id VARCHAR(16) NULL,
    ADD COLUMN IF NOT EXISTS telecom_password VARCHAR(16) NULL,
    ADD COLUMN IF NOT EXISTS monthly_amount INT UNSIGNED NULL,
    ADD COLUMN IF NOT EXISTS credits_per_cycle INT UNSIGNED NULL,
    ADD COLUMN IF NOT EXISTS navi_mode VARCHAR(8) NOT NULL DEFAULT 'stg',
    ADD COLUMN IF NOT EXISTS callback_path VARCHAR(255) NOT NULL DEFAULT 'my',
    ADD COLUMN IF NOT EXISTS last_settle_uuid VARCHAR(64) NULL,
    ADD COLUMN IF NOT EXISTS started_at DATETIME NULL,
    ADD COLUMN IF NOT EXISTS cancelled_at DATETIME NULL,
    ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL;

CREATE TABLE IF NOT EXISTS telecom_payment_events (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    settle_uuid VARCHAR(80) NOT NULL,
    user_id VARCHAR(64) NOT NULL,
    plan_id BIGINT UNSIGNED NULL,
    event_type ENUM('payment', 'cancel') NOT NULL,
    rel VARCHAR(16) NOT NULL,
    money INT UNSIGNED NOT NULL DEFAULT 0,
    settle_count INT UNSIGNED NULL,
    received_data JSON NOT NULL,
    api_status ENUM('pending', 'success', 'failed') NOT NULL DEFAULT 'pending',
    api_response TEXT NULL,
    received_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    processed_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_telecom_event_uuid (settle_uuid),
    KEY idx_telecom_event_user (user_id),
    KEY idx_telecom_event_status (api_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
