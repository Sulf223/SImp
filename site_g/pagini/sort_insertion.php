<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/partials/sort_lesson_template.php';

$lesson = [
    'title' => 'Insertion',
    'accent' => 'Sort',
    'algorithm' => 'insertion',
    'lesson_slug' => 'sort_insertion',
    'visualizer_title' => 'Insertion Sort în acțiune',
    'lead' => 'Insertion Sort construiește zona sortată de la stânga la dreapta, inserând fiecare element la locul potrivit.',
    'idea' => 'Algoritmul seamănă cu ordonarea cărților în mână: iei următorul element, îl reții temporar și deplasezi spre dreapta elementele mai mari până apare locul corect.',
    'use_when' => [
        'Vectorul este mic sau aproape sortat.',
        'Vrei o metodă stabilă și ușor de implementat.',
        'Ai date care sosesc treptat și vrei să menții o zonă sortată.',
    ],
    'avoid_when' => [
        'Vectorul este mare și foarte amestecat.',
        'Ai nevoie de performanță predictibilă O(n log n).',
        'Numărul mare de deplasări este o problemă.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n)',
        'Caz mediu' => 'O(n^2)',
        'Caz rău' => 'O(n^2)',
        'Memorie' => 'O(1)',
        'Stabil' => 'Da',
    ],
    'steps' => [
        'Considera primul element ca fiind deja sortat.',
        'Ia elementul de pe poziția i și salvează-l într-o variabilă temporară.',
        'Compară spre stânga cu elementele din zona sortată.',
        'Cât timp găsești elemente mai mari, le muți o poziție la dreapta.',
        'Așază elementul salvat în locul rămas liber.',
    ],
    'example' => 'Pentru [4, 1, 3, 2], elementul 1 este salvat, 4 se mută la dreapta și 1 intră la început: [1, 4, 3, 2]. Apoi 3 intră între 1 și 4, iar 2 între 1 și 3.',
    'pseudocode' => [
        ['line' => 1, 'text' => 'pentru i de la 1 la n - 1'],
        ['line' => 2, 'text' => '  key = v[i]'],
        ['line' => 3, 'text' => '  j = i - 1'],
        ['line' => 4, 'text' => '  cât timp j >= 0 și v[j] > key'],
        ['line' => 5, 'text' => '    mută v[j] pe poziția j + 1'],
        ['line' => 6, 'text' => '  pune key pe poziția j + 1'],
    ],
    'variables' => [
        'i' => 'elementul de inserat',
        'key' => 'valoarea salvată temporar',
        'j' => 'cursorul care merge spre stânga',
        'comparisons' => 'comparații făcute',
        'swaps' => 'deplasări spre dreapta',
    ],
    'cpp' => [
        'void insertionSort(vector<int>& v) {',
        '    int n = v.size();',
        '',
        '    for (int i = 1; i < n; i++) {',
        '        int key = v[i];',
        '        int j = i - 1;',
        '',
        '        while (j >= 0 && v[j] > key) {',
        '            v[j + 1] = v[j];',
        '            j--;',
        '        }',
        '',
        '        v[j + 1] = key;',
        '    }',
        '}',
    ],
    'mistakes' => [
        'După while, poziția corectă este j + 1, nu j.',
        'Condiția folosește > pentru stabilitate; cu >= elementele egale își pot schimba ordinea.',
        'Trebuie salvat key înainte să începi deplasările, altfel îl suprascrii.',
        'Nu porni de la i = 0; primul element formează singur o zonă sortată.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
