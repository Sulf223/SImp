CREATE TABLE IF NOT EXISTS rate_limit_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifier VARCHAR(64) NOT NULL,
    action VARCHAR(40) NOT NULL,
    attempt_count INT DEFAULT 1,
    window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ident_action (identifier, action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
