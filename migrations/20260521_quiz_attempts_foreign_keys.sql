-- Harden quiz_attempts referential integrity.
-- The table was introduced without foreign keys, so deleting users/questions could leave orphan attempts.

DELETE qa
FROM quiz_attempts qa
LEFT JOIN utilizatori u ON u.id = qa.user_id
WHERE u.id IS NULL;

DELETE qa
FROM quiz_attempts qa
LEFT JOIN grile_cpp g ON g.id = qa.grila_id
WHERE g.id IS NULL;

SET @fk_user_exists := (
    SELECT COUNT(*)
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND TABLE_NAME = 'quiz_attempts'
      AND CONSTRAINT_NAME = 'fk_quiz_attempts_user'
      AND CONSTRAINT_TYPE = 'FOREIGN KEY'
);

SET @fk_user_sql := IF(
    @fk_user_exists = 0,
    'ALTER TABLE quiz_attempts ADD CONSTRAINT fk_quiz_attempts_user FOREIGN KEY (user_id) REFERENCES utilizatori(id) ON DELETE CASCADE',
    'SELECT 1'
);
PREPARE fk_user_stmt FROM @fk_user_sql;
EXECUTE fk_user_stmt;
DEALLOCATE PREPARE fk_user_stmt;

SET @fk_grila_exists := (
    SELECT COUNT(*)
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND TABLE_NAME = 'quiz_attempts'
      AND CONSTRAINT_NAME = 'fk_quiz_attempts_grila'
      AND CONSTRAINT_TYPE = 'FOREIGN KEY'
);

SET @fk_grila_sql := IF(
    @fk_grila_exists = 0,
    'ALTER TABLE quiz_attempts ADD CONSTRAINT fk_quiz_attempts_grila FOREIGN KEY (grila_id) REFERENCES grile_cpp(id) ON DELETE CASCADE',
    'SELECT 1'
);
PREPARE fk_grila_stmt FROM @fk_grila_sql;
EXECUTE fk_grila_stmt;
DEALLOCATE PREPARE fk_grila_stmt;
