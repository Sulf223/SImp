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
    'visualizer_title' => 'Insertion Sort in actiune',
    'lead' => 'Insertion Sort construieste zona sortata de la stanga la dreapta, inserand fiecare element la locul potrivit.',
    'idea' => 'Algoritmul seamana cu ordonarea cartilor in mana: iei urmatorul element, il retii temporar si deplasezi spre dreapta elementele mai mari pana apare locul corect.',
    'use_when' => [
        'Vectorul este mic sau aproape sortat.',
        'Vrei o metoda stabila si usor de implementat.',
        'Ai date care sosesc treptat si vrei sa mentii o zona sortata.',
    ],
    'avoid_when' => [
        'Vectorul este mare si foarte amestecat.',
        'Ai nevoie de performanta predictibila O(n log n).',
        'Numarul mare de deplasari este o problema.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n)',
        'Caz mediu' => 'O(n^2)',
        'Caz rau' => 'O(n^2)',
        'Memorie' => 'O(1)',
        'Stabil' => 'Da',
    ],
    'steps' => [
        'Considera primul element ca fiind deja sortat.',
        'Ia elementul de pe pozitia i si salveaza-l intr-o variabila temporara.',
        'Compara spre stanga cu elementele din zona sortata.',
        'Cat timp gasesti elemente mai mari, le muti o pozitie la dreapta.',
        'Aseaza elementul salvat in locul ramas liber.',
    ],
    'example' => 'Pentru [4, 1, 3, 2], elementul 1 este salvat, 4 se muta la dreapta si 1 intra la inceput: [1, 4, 3, 2]. Apoi 3 intra intre 1 si 4, iar 2 intre 1 si 3.',
    'pseudocode' => [
        ['line' => 1, 'text' => 'pentru i de la 1 la n - 1'],
        ['line' => 2, 'text' => '  key = v[i]'],
        ['line' => 3, 'text' => '  j = i - 1'],
        ['line' => 4, 'text' => '  cat timp j >= 0 si v[j] > key'],
        ['line' => 5, 'text' => '    muta v[j] pe pozitia j + 1'],
        ['line' => 6, 'text' => '  pune key pe pozitia j + 1'],
    ],
    'variables' => [
        'i' => 'elementul de inserat',
        'key' => 'valoarea salvata temporar',
        'j' => 'cursorul care merge spre stanga',
        'shifts' => 'deplasari spre dreapta',
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
        'Dupa while, pozitia corecta este j + 1, nu j.',
        'Conditia foloseste > pentru stabilitate; cu >= elementele egale isi pot schimba ordinea.',
        'Trebuie salvat key inainte sa incepi deplasarile, altfel il suprascrii.',
        'Nu porni de la i = 0; primul element formeaza singur o zona sortata.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
