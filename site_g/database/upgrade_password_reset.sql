SET @offbyone_sql = IF(
    (
        SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'utilizatori'
          AND COLUMN_NAME = 'email'
    ) = 0,
    'ALTER TABLE utilizatori ADD COLUMN email VARCHAR(190) NULL AFTER username',
    'SELECT 1'
);
PREPARE offbyone_stmt FROM @offbyone_sql;
EXECUTE offbyone_stmt;
DEALLOCATE PREPARE offbyone_stmt;

SET @offbyone_sql = IF(
    (
        SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'utilizatori'
          AND INDEX_NAME = 'uq_email'
    ) = 0,
    'ALTER TABLE utilizatori ADD UNIQUE KEY uq_email (email)',
    'SELECT 1'
);
PREPARE offbyone_stmt FROM @offbyone_sql;
EXECUTE offbyone_stmt;
DEALLOCATE PREPARE offbyone_stmt;

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token_hash (token_hash),
    INDEX idx_user (user_id, expires_at),
    CONSTRAINT fk_reset_user FOREIGN KEY (user_id) REFERENCES utilizatori(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
