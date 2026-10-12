-- 既に定額決済用schema.sqlを実行済みの環境では、このファイルだけを一度実行してください。

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
