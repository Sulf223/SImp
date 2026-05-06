-- Migration: add doc_link column to grile_cpp
-- Safe to run multiple times.

SET @offbyone_has_doc_link := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'grile_cpp'
      AND COLUMN_NAME = 'doc_link'
);

SET @offbyone_sql := IF(
    @offbyone_has_doc_link = 0,
    'ALTER TABLE grile_cpp ADD COLUMN doc_link VARCHAR(255) DEFAULT NULL AFTER explicatie',
    'SELECT 1'
);
PREPARE offbyone_stmt FROM @offbyone_sql;
EXECUTE offbyone_stmt;
DEALLOCATE PREPARE offbyone_stmt;

SET @offbyone_has_doc_link_idx := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'grile_cpp'
      AND INDEX_NAME = 'idx_grile_doc_link'
);

SET @offbyone_sql := IF(
    @offbyone_has_doc_link_idx = 0,
    'CREATE INDEX idx_grile_doc_link ON grile_cpp (doc_link(191))',
    'SELECT 1'
);
PREPARE offbyone_stmt FROM @offbyone_sql;
EXECUTE offbyone_stmt;
DEALLOCATE PREPARE offbyone_stmt;
