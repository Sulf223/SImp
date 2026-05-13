CREATE TABLE IF NOT EXISTS ai_quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    path_slug VARCHAR(80) NOT NULL DEFAULT 'general',
    score INT NOT NULL,
    total INT NOT NULL,
    percent DECIMAL(5,2) NOT NULL DEFAULT 0,
    feedback_summary TEXT NULL,
    sources_json TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ai_quiz_user_time (user_id, created_at),
    INDEX idx_ai_quiz_user_path (user_id, path_slug),
    CONSTRAINT fk_ai_quiz_attempts_user
        FOREIGN KEY (user_id) REFERENCES utilizatori(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
