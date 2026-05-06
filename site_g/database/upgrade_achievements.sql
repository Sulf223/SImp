CREATE TABLE IF NOT EXISTS achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(60) NOT NULL UNIQUE,
    title VARCHAR(120) NOT NULL,
    description VARCHAR(255) NOT NULL,
    icon VARCHAR(40) NOT NULL DEFAULT 'star',
    criteria_type ENUM('grile_count','exercise_count','algorithm_completed','streak_days','first_login') NOT NULL,
    criteria_value INT DEFAULT NULL,
    criteria_meta VARCHAR(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_achievements (
    user_id INT NOT NULL,
    achievement_id INT NOT NULL,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, achievement_id),
    CONSTRAINT fk_ua_user FOREIGN KEY (user_id) REFERENCES utilizatori(id) ON DELETE CASCADE,
    CONSTRAINT fk_ua_ach FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO achievements (slug, title, description, icon, criteria_type, criteria_value, criteria_meta) VALUES
('first_login', 'Bun venit!', 'Ai făcut primul login pe OffByOne Academy.', 'sun', 'first_login', 0, NULL),
('grile_5', 'Apetit pentru grile', 'Ai rezolvat 5 grile.', 'check-circle', 'grile_count', 5, NULL),
('grile_25', 'Maestru de grile', 'Ai rezolvat 25 de grile.', 'award', 'grile_count', 25, NULL),
('grile_50', 'Tocilar absolut', 'Ai rezolvat 50 de grile.', 'crown', 'grile_count', 50, NULL),
('exercise_1', 'Prima soluție', 'Ai completat primul exercițiu.', 'code', 'exercise_count', 1, NULL),
('exercise_10', 'Cod fluent', 'Ai completat 10 exerciții.', 'code', 'exercise_count', 10, NULL),
('algo_quick', 'Cuceritor de Quick Sort', 'Ai completat Quick Sort.', 'zap', 'algorithm_completed', 1, 'quick'),
('algo_merge', 'Maestru Merge Sort', 'Ai completat Merge Sort.', 'layers', 'algorithm_completed', 1, 'merge'),
('streak_3', 'Trei zile la rând', 'Streak de 3 zile.', 'flame', 'streak_days', 3, NULL),
('streak_7', 'O săptămână de foc', 'Streak de 7 zile.', 'flame', 'streak_days', 7, NULL);