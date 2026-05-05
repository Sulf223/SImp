-- Migration: add doc_link column to grile_cpp
-- Run this SQL once against the application database
-- Note: some MySQL versions do not support IF NOT EXISTS in ALTER TABLE.
-- If your server rejects the statement, run the ALTER TABLE manually:
-- ALTER TABLE grile_cpp ADD COLUMN doc_link VARCHAR(255) DEFAULT NULL AFTER explicatie;

ALTER TABLE grile_cpp
  ADD COLUMN doc_link VARCHAR(255) DEFAULT NULL;

-- Optional index for faster lookups
CREATE INDEX idx_grile_doc_link ON grile_cpp (doc_link(191));
