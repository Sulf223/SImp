ALTER TABLE utilizatori ADD COLUMN email VARCHAR(190) NULL AFTER username;
ALTER TABLE utilizatori ADD UNIQUE KEY uq_email (email);

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
