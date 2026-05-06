-- Adăugăm coloanele necesare tabelului utilizatori.
-- Sunt condiționale pentru a putea rula migrarea și pe baze deja actualizate.
DROP PROCEDURE IF EXISTS offbyone_add_column_if_missing;
DELIMITER //
CREATE PROCEDURE offbyone_add_column_if_missing(
    IN p_table_name VARCHAR(64),
    IN p_column_name VARCHAR(64),
    IN p_column_definition TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_table_name
          AND COLUMN_NAME = p_column_name
    ) THEN
        SET @offbyone_sql = CONCAT('ALTER TABLE `', p_table_name, '` ADD COLUMN ', p_column_definition);
        PREPARE offbyone_stmt FROM @offbyone_sql;
        EXECUTE offbyone_stmt;
        DEALLOCATE PREPARE offbyone_stmt;
    END IF;
END//
DELIMITER ;

CALL offbyone_add_column_if_missing('utilizatori', 'display_name', '`display_name` VARCHAR(64) NULL AFTER `rol`');
CALL offbyone_add_column_if_missing('utilizatori', 'bio', '`bio` VARCHAR(280) NULL AFTER `display_name`');
CALL offbyone_add_column_if_missing('utilizatori', 'avatar_seed', '`avatar_seed` VARCHAR(64) NULL AFTER `bio`');
CALL offbyone_add_column_if_missing('utilizatori', 'theme_pref', '`theme_pref` ENUM(''dark'',''light'',''auto'') DEFAULT ''dark'' AFTER `avatar_seed`');
CALL offbyone_add_column_if_missing('utilizatori', 'onboarded_at', '`onboarded_at` TIMESTAMP NULL AFTER `theme_pref`');

DROP PROCEDURE IF EXISTS offbyone_add_column_if_missing;

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
