-- NAVI定額決済用スキーマ
-- heartf_school2 を選択してから一度だけ実行してください。

ALTER TABLE navi_user
    ADD COLUMN telecom_member_id VARCHAR(16) NULL AFTER user_id,
    ADD COLUMN telecom_password VARCHAR(16) NULL AFTER telecom_member_id,
    ADD COLUMN rebill_param_id VARCHAR(255) NULL AFTER telecom_password,
    ADD COLUMN monthly_amount INT NOT NULL DEFAULT 0 AFTER rebill_param_id,
    ADD COLUMN credits_per_cycle INT NOT NULL DEFAULT 0 AFTER monthly_amount,
    ADD COLUMN pending_option VARCHAR(64) NULL AFTER credits_per_cycle,
    ADD COLUMN callback_path VARCHAR(100) NOT NULL DEFAULT 'my' AFTER pending_option,
    ADD COLUMN navi_mode VARCHAR(10) NOT NULL DEFAULT 'prod' AFTER callback_path,
    ADD COLUMN last_settle_uuid VARCHAR(64) NULL AFTER last_transaction_id,
    ADD COLUMN last_settle_count INT NULL AFTER last_settle_uuid,
    ADD COLUMN last_payment_at DATETIME NULL AFTER last_settle_count,
    ADD COLUMN last_failure_at DATETIME NULL AFTER last_payment_at,
    ADD COLUMN cancel_requested_at DATETIME NULL AFTER last_failure_at,
    ADD COLUMN cancelled_at DATETIME NULL AFTER cancel_requested_at,
    ADD UNIQUE KEY uq_navi_user_telecom_member_id (telecom_member_id);

CREATE TABLE navi_subscription_events (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    event_key VARCHAR(80) NOT NULL,
    settle_uuid VARCHAR(64) NULL,
    user_id INT NOT NULL,
    telecom_member_id VARCHAR(16) NOT NULL,
    event_type VARCHAR(20) NOT NULL,
    result VARCHAR(10) NOT NULL,
    amount INT NOT NULL DEFAULT 0,
    credits INT NOT NULL DEFAULT 0,
    rebill_param_id VARCHAR(255) NULL,
    settle_count INT NULL,
    payload_json LONGTEXT NULL,
    processed_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_navi_subscription_event_key (event_key),
    KEY idx_navi_subscription_events_user_id (user_id),
    KEY idx_navi_subscription_events_member_id (telecom_member_id),
    KEY idx_navi_subscription_events_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS navi_subscription_plan_changes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    request_id VARCHAR(40) NOT NULL,
    user_id INT NOT NULL,
    telecom_member_id VARCHAR(16) NOT NULL,
    current_rebill_param_id VARCHAR(255) NOT NULL,
    requested_rebill_param_id VARCHAR(255) NOT NULL,
    current_amount INT NOT NULL,
    requested_amount INT NOT NULL,
    current_credits INT NOT NULL,
    requested_credits INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'requested',
    requested_at DATETIME NOT NULL,
    processed_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_navi_plan_change_request_id (request_id),
    KEY idx_navi_plan_change_user_status (user_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
