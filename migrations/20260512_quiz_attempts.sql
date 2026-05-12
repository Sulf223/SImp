CREATE TABLE IF NOT EXISTS quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    grila_id INT NOT NULL,
    selected_answer TINYINT NOT NULL,
    is_correct TINYINT(1) NOT NULL DEFAULT 0,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_quiz_attempt_user_time (user_id, attempted_at),
    INDEX idx_quiz_attempt_grila (grila_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
