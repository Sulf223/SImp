<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/partials/sort_lesson_template.php';

$lesson = [
    'title' => 'Selection',
    'accent' => 'Sort',
    'algorithm' => 'selection',
    'lesson_slug' => 'sort_selection',
    'visualizer_title' => 'Selection Sort în acțiune',
    'lead' => 'Selection Sort caută minimul din zona nesortată și îl așază pe prima poziție liberă.',
    'idea' => 'Algoritmul împarte vectorul în două zone: stânga este sortată, dreapta este încă nesortată. La fiecare pas caută cel mai mic element din zona nesortată și îl mută la începutul acestei zone.',
    'use_when' => [
        'Vrei o metodă simplă, cu puține interschimbări.',
        'Ai date puține și vrei să explici clar ideea de minim selectat.',
        'Costul interschimbărilor este mai important decât numărul comparațiilor.',
    ],
    'avoid_when' => [
        'Ai nevoie de un algoritm rapid pentru vectori mari.',
        'Ai nevoie de stabilitate fără modificări suplimentare.',
        'Datele sunt deja aproape sortate; algoritmul tot caută minimul complet.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n^2)',
        'Caz mediu' => 'O(n^2)',
        'Caz rău' => 'O(n^2)',
        'Memorie' => 'O(1)',
        'Stabil' => 'Nu, în varianta standard',
    ],
    'steps' => [
        'Consideră poziția i ca prima poziție liberă din zona nesortată.',
        'Presupune că minimul este pe poziția i.',
        'Caută în restul vectorului o valoare mai mică.',
        'Dacă găsește una, actualizează poziția minimului.',
        'La final, interschimbă minimul găsit cu elementul de pe poziția i.',
    ],
    'example' => 'Pentru [7, 3, 5, 2], la primul pas minimul este 2, deci vectorul devine [2, 3, 5, 7]. Următoarele poziții sunt deja în ordine, dar comparațiile continuă.',
    'pseudocode' => [
        ['line' => 1, 'text' => 'pentru i de la 0 la n - 2'],
        ['line' => 2, 'text' => '  minIndex = i'],
        ['line' => 3, 'text' => '  pentru j de la i + 1 la n - 1'],
        ['line' => 4, 'text' => '    dacă v[j] < v[minIndex], minIndex = j'],
        ['line' => 5, 'text' => '  interschimbă v[i] cu v[minIndex]'],
    ],
    'variables' => [
        'i' => 'prima poziție nesortată',
        'j' => 'poziția testată',
        'minIndex' => 'poziția minimului curent',
        'swaps' => 'mutări ale minimului',
    ],
    'cpp' => [
        'void selectionSort(vector<int>& v) {',
        '    int n = v.size();',
        '',
        '    for (int i = 0; i < n - 1; i++) {',
        '        int minIndex = i;',
        '',
        '        for (int j = i + 1; j < n; j++) {',
        '            if (v[j] < v[minIndex]) {',
        '                minIndex = j;',
        '            }',
        '        }',
        '',
        '        if (minIndex != i) {',
        '            swap(v[i], v[minIndex]);',
        '        }',
        '    }',
        '}',
    ],
    'mistakes' => [
        'Nu interschimba imediat când găsești o valoare mai mică; întâi termini căutarea minimului.',
        'Bucla interioară începe de la i + 1, pentru că poziția i este deja candidatul inițial.',
        'Selection Sort nu devine mai rapid doar fiindca vectorul este aproape sortat.',
        'Dacă ai elemente egale, varianta standard poate schimba ordinea lor relativă.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
