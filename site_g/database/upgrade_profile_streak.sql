-- Adăugăm coloanele necesare tabelului utilizatori
-- Notă: Aceste coloane au fost adăugate manual pentru a asigura stabilitatea.
-- ALTER TABLE utilizatori ADD COLUMN display_name VARCHAR(64) NULL;
-- ALTER TABLE utilizatori ADD COLUMN bio VARCHAR(280) NULL;
-- ALTER TABLE utilizatori ADD COLUMN avatar_seed VARCHAR(64) NULL;
-- ALTER TABLE utilizatori ADD COLUMN theme_pref ENUM('dark','light','auto') DEFAULT 'dark';
-- ALTER TABLE utilizatori ADD COLUMN onboarded_at TIMESTAMP NULL;

CREATE TABLE IF NOT EXISTS user_streak (
    user_id INT PRIMARY KEY,
    current_streak INT DEFAULT 0,
    longest_streak INT DEFAULT 0,
    last_activity_date DATE,
    streak_freezes INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_streak_user FOREIGN KEY (user_id) REFERENCES utilizatori(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS activity_day (
    user_id INT NOT NULL,
    activity_date DATE NOT NULL,
    activity_count INT DEFAULT 0,
    PRIMARY KEY (user_id, activity_date),
    CONSTRAINT fk_actday_user FOREIGN KEY (user_id) REFERENCES utilizatori(id) ON DELETE CASCADE
);
