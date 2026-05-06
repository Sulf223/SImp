-- Migration: learning paths used by pagini/invatare.php and the AI final exams

CREATE TABLE IF NOT EXISTS learning_paths (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(80) NOT NULL,
    title VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_learning_paths_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS learning_path_steps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    path_id INT NOT NULL,
    step_order INT NOT NULL,
    lesson_slug VARCHAR(80) NOT NULL,
    title VARCHAR(160) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_learning_path_step (path_id, step_order),
    KEY idx_learning_path_steps_path (path_id),
    CONSTRAINT fk_learning_path_steps_path
        FOREIGN KEY (path_id) REFERENCES learning_paths(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Older Docker volumes may have this table without uq_learning_path_step.
-- Keep the newest row for each path/order before enforcing the key.
DELETE stale_step
FROM learning_path_steps stale_step
JOIN learning_path_steps newest_step
    ON newest_step.path_id = stale_step.path_id
    AND newest_step.step_order = stale_step.step_order
    AND newest_step.id > stale_step.id;

SET @has_uq_learning_path_step := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'learning_path_steps'
      AND INDEX_NAME = 'uq_learning_path_step'
);
SET @ddl_learning_path_step := IF(
    @has_uq_learning_path_step = 0,
    'ALTER TABLE learning_path_steps ADD UNIQUE KEY uq_learning_path_step (path_id, step_order)',
    'SELECT 1'
);
PREPARE stmt_learning_path_step FROM @ddl_learning_path_step;
EXECUTE stmt_learning_path_step;
DEALLOCATE PREPARE stmt_learning_path_step;

INSERT IGNORE INTO learning_paths (slug, title, description) VALUES
('sorting-basics', 'Bazele sortării', 'Parcurs pentru înțelegerea algoritmilor clasici de sortare, de la metode simple O(n^2) la algoritmi eficienți O(n log n).'),
('recursion-pro', 'Recursivitate și Divide et Impera', 'Parcurs pentru funcții recursive, stiva de apeluri și algoritmi care împart problema în subprobleme.'),
('backtracking', 'Backtracking aplicat', 'Parcurs pentru construirea incrementală a soluțiilor, validare parțială și revenire controlată.'),
('divide-et-impera', 'Divide et Impera', 'Parcurs dedicat strategiei divide-conquer-combine prin Merge Sort, Quick Sort și căutare binară.');

INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 1, 'sort_bubble', 'Bubble Sort - interschimbări adiacente' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 2, 'sort_selection', 'Selection Sort - minimul succesiv' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 3, 'sort_insertion', 'Insertion Sort - inserare ordonată' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 4, 'sort_quick', 'Quick Sort - partiționare și pivot' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 5, 'sort_merge', 'Merge Sort - interclasare' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 6, 'sort_counting', 'Counting Sort - frecvențe' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 7, 'final_quiz', 'Examen final AI' FROM learning_paths WHERE slug = 'sorting-basics';

INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 1, 'recursivitate', 'Recursivitate - caz de bază și apel recursiv' FROM learning_paths WHERE slug = 'recursion-pro';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 2, 'divide_et_impera', 'Divide et Impera - împărțire și combinare' FROM learning_paths WHERE slug = 'recursion-pro';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 3, 'sort_merge', 'Merge Sort recursiv' FROM learning_paths WHERE slug = 'recursion-pro';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 4, 'sort_quick', 'Quick Sort recursiv' FROM learning_paths WHERE slug = 'recursion-pro';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 5, 'final_quiz', 'Examen final AI' FROM learning_paths WHERE slug = 'recursion-pro';

INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 1, 'backtracking', 'Backtracking - spațiu de stare și revenire' FROM learning_paths WHERE slug = 'backtracking';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 2, 'greedy', 'Comparație cu metoda greedy' FROM learning_paths WHERE slug = 'backtracking';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 3, 'final_quiz', 'Examen final AI' FROM learning_paths WHERE slug = 'backtracking';

INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 1, 'divide_et_impera', 'Divide et Impera - modelul general' FROM learning_paths WHERE slug = 'divide-et-impera';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 2, 'sort_merge', 'Merge Sort - divide-conquer-combine' FROM learning_paths WHERE slug = 'divide-et-impera';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 3, 'sort_quick', 'Quick Sort - partiționare' FROM learning_paths WHERE slug = 'divide-et-impera';
INSERT IGNORE INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 4, 'final_quiz', 'Examen final AI' FROM learning_paths WHERE slug = 'divide-et-impera';

CREATE TEMPORARY TABLE tmp_learning_path_steps (
    path_slug VARCHAR(80) NOT NULL,
    step_order INT NOT NULL,
    lesson_slug VARCHAR(80) NOT NULL,
    title VARCHAR(160) NOT NULL,
    PRIMARY KEY (path_slug, step_order)
) ENGINE=MEMORY DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tmp_learning_path_steps (path_slug, step_order, lesson_slug, title) VALUES
('sorting-basics', 1, 'sort_bubble', 'Bubble Sort - interschimbări adiacente'),
('sorting-basics', 2, 'sort_selection', 'Selection Sort - minimul succesiv'),
('sorting-basics', 3, 'sort_insertion', 'Insertion Sort - inserare ordonată'),
('sorting-basics', 4, 'sort_quick', 'Quick Sort - partiționare și pivot'),
('sorting-basics', 5, 'sort_merge', 'Merge Sort - interclasare'),
('sorting-basics', 6, 'sort_counting', 'Counting Sort - frecvențe'),
('sorting-basics', 7, 'final_quiz', 'Examen final AI'),
('recursion-pro', 1, 'recursivitate', 'Recursivitate - caz de bază și apel recursiv'),
('recursion-pro', 2, 'divide_et_impera', 'Divide et Impera - împărțire și combinare'),
('recursion-pro', 3, 'sort_merge', 'Merge Sort recursiv'),
('recursion-pro', 4, 'sort_quick', 'Quick Sort recursiv'),
('recursion-pro', 5, 'final_quiz', 'Examen final AI'),
('backtracking', 1, 'backtracking', 'Backtracking - spațiu de stare și revenire'),
('backtracking', 2, 'greedy', 'Comparație cu metoda greedy'),
('backtracking', 3, 'final_quiz', 'Examen final AI'),
('divide-et-impera', 1, 'divide_et_impera', 'Divide et Impera - modelul general'),
('divide-et-impera', 2, 'sort_merge', 'Merge Sort - divide-conquer-combine'),
('divide-et-impera', 3, 'sort_quick', 'Quick Sort - partiționare'),
('divide-et-impera', 4, 'final_quiz', 'Examen final AI');

UPDATE learning_path_steps s
JOIN learning_paths p ON p.id = s.path_id
JOIN tmp_learning_path_steps t
    ON t.path_slug = p.slug
    AND t.step_order = s.step_order
SET s.lesson_slug = t.lesson_slug,
    s.title = t.title;

DROP TEMPORARY TABLE tmp_learning_path_steps;
