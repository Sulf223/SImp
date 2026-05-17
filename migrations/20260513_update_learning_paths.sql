SET NAMES utf8mb4;

INSERT INTO learning_paths (slug, title, description) VALUES
('parcurs-recomandat', 'Parcurs recomandat', 'Ordinea cea mai clară pentru prezentare: fundamente, sortări, laborator vizual, tehnici algoritmice, grile și test AI.'),
('algoritmi-fundamentali', 'Algoritmi fundamentali', 'Noțiunile de bază folosite în problemele de început: parcurgeri, cifre, divizori, CMMDC, primalitate, frecvențe, căutare binară și ciur.'),
('sorting-basics', 'Sortări și eficiență', 'De la metode simple O(n^2) la algoritmi eficienți O(n log n), cu comparații și vizualizări.'),
('tehnici-algoritmice', 'Tehnici algoritmice', 'Recursivitate, Divide et Impera, Backtracking și Greedy, explicate prin pași, schelete C++ și greșeli frecvente.')
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    description = VALUES(description);

DELETE s
FROM learning_path_steps s
JOIN learning_paths p ON p.id = s.path_id
WHERE p.slug IN (
    'parcurs-recomandat',
    'algoritmi-fundamentali',
    'sorting-basics',
    'tehnici-algoritmice',
    'recursion-pro',
    'backtracking',
    'divide-et-impera'
);

DELETE FROM learning_paths
WHERE slug IN ('recursion-pro', 'backtracking', 'divide-et-impera');

INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 1, 'algoritmi_fundamentali', 'Algoritmi fundamentali - baza pentru probleme' FROM learning_paths WHERE slug = 'parcurs-recomandat';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 2, 'sortare', 'Metode de sortare - harta algoritmilor' FROM learning_paths WHERE slug = 'parcurs-recomandat';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 3, 'laborator_vizual', 'Laborator vizual - urmărește pașii' FROM learning_paths WHERE slug = 'parcurs-recomandat';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 4, 'algoritmi_avansati', 'Tehnici algoritmice - recursivitate, backtracking, greedy, divide' FROM learning_paths WHERE slug = 'parcurs-recomandat';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 5, 'grile', 'Grile - verificare rapidă' FROM learning_paths WHERE slug = 'parcurs-recomandat';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 6, 'profesor_ai', 'Profesor AI - antrenament personalizat' FROM learning_paths WHERE slug = 'parcurs-recomandat';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 7, 'final_quiz', 'Test final AI' FROM learning_paths WHERE slug = 'parcurs-recomandat';

INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 1, 'algoritmi_fundamentali', 'Noțiuni de bază - fișe explicate' FROM learning_paths WHERE slug = 'algoritmi-fundamentali';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 2, 'compilator', 'Compilator - testează schelete C++' FROM learning_paths WHERE slug = 'algoritmi-fundamentali';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 3, 'grile', 'Grile - întrebări de fixare' FROM learning_paths WHERE slug = 'algoritmi-fundamentali';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 4, 'final_quiz', 'Test AI pe algoritmi fundamentali' FROM learning_paths WHERE slug = 'algoritmi-fundamentali';

INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 1, 'sortare', 'Privire de ansamblu - metode de sortare' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 2, 'sort_bubble', 'Bubble Sort - interschimbări adiacente' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 3, 'sort_selection', 'Selection Sort - minimul succesiv' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 4, 'sort_insertion', 'Insertion Sort - inserare ordonată' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 5, 'sort_quick', 'Quick Sort - partiționare și pivot' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 6, 'sort_merge', 'Merge Sort - interclasare' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 7, 'sort_counting', 'Counting Sort - frecvențe' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 8, 'comparatii_sortare', 'Comparații - alegerea metodei potrivite' FROM learning_paths WHERE slug = 'sorting-basics';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 9, 'final_quiz', 'Test AI pe sortări' FROM learning_paths WHERE slug = 'sorting-basics';

INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 1, 'algoritmi_avansati', 'Tehnici algoritmice - harta conceptelor' FROM learning_paths WHERE slug = 'tehnici-algoritmice';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 2, 'recursivitate', 'Recursivitate - caz de bază și apel recursiv' FROM learning_paths WHERE slug = 'tehnici-algoritmice';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 3, 'divide_et_impera', 'Divide et Impera - împărțire și combinare' FROM learning_paths WHERE slug = 'tehnici-algoritmice';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 4, 'backtracking', 'Backtracking - spațiu de stare și revenire' FROM learning_paths WHERE slug = 'tehnici-algoritmice';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 5, 'greedy', 'Greedy - alegere locală și justificare' FROM learning_paths WHERE slug = 'tehnici-algoritmice';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 6, 'laborator_vizual', 'Laborator vizual - simulare pas cu pas' FROM learning_paths WHERE slug = 'tehnici-algoritmice';
INSERT INTO learning_path_steps (path_id, step_order, lesson_slug, title)
SELECT id, 7, 'final_quiz', 'Test AI pe tehnici algoritmice' FROM learning_paths WHERE slug = 'tehnici-algoritmice';
