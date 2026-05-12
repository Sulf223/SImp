-- Fix mojibake in achievement titles/descriptions.
-- Safe to run multiple times.

UPDATE achievements
SET title = 'Bun venit!',
    description = 'Ai făcut primul login pe OffByOne Academy.'
WHERE slug = 'first_login';

UPDATE achievements
SET title = 'Apetit pentru grile',
    description = 'Ai rezolvat 5 grile.'
WHERE slug = 'grile_5';

UPDATE achievements
SET title = 'Maestru de grile',
    description = 'Ai rezolvat 25 de grile.'
WHERE slug = 'grile_25';

UPDATE achievements
SET title = 'Tocilar absolut',
    description = 'Ai rezolvat 50 de grile.'
WHERE slug = 'grile_50';

UPDATE achievements
SET title = 'Prima soluție',
    description = 'Ai completat primul exercițiu.'
WHERE slug = 'exercise_1';

UPDATE achievements
SET title = 'Cod fluent',
    description = 'Ai completat 10 exerciții.'
WHERE slug = 'exercise_10';

UPDATE achievements
SET title = 'Cuceritor de Quick Sort',
    description = 'Ai completat Quick Sort.'
WHERE slug = 'algo_quick';

UPDATE achievements
SET title = 'Maestru Merge Sort',
    description = 'Ai completat Merge Sort.'
WHERE slug = 'algo_merge';

UPDATE achievements
SET title = 'Trei zile la rând',
    description = 'Streak de 3 zile.'
WHERE slug = 'streak_3';

UPDATE achievements
SET title = 'O săptămână de foc',
    description = 'Streak de 7 zile.'
WHERE slug = 'streak_7';
