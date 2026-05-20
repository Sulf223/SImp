<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/partials/sort_lesson_template.php';

$lesson = [
    'title' => 'Bubble',
    'accent' => 'Sort',
    'algorithm' => 'bubble',
    'lesson_slug' => 'sort_bubble',
    'visualizer_title' => 'Bubble Sort în acțiune',
    'lead' => 'Un algoritm de sortare simplu: compară elemente vecine și împinge treptat valorile mari spre finalul vectorului.',
    'idea' => 'Bubble Sort parcurge vectorul de mai multe ori. La fiecare trecere compară două elemente alăturate și le interschimbă dacă sunt în ordine greșită. După prima trecere, cel mai mare element ajunge pe ultima poziție; după a doua, următorul cel mai mare ajunge pe penultima poziție.',
    'use_when' => [
        'Vrei să înțelegi mecanismul de bază al sortării prin interschimbare.',
        'Vectorul este foarte mic sau aproape sortat.',
        'Ai nevoie de un exemplu ușor de urmărit pas cu pas.',
    ],
    'avoid_when' => [
        'Ai multe elemente și contează performanța.',
        'Datele sunt amestecate puternic, pentru că apar multe comparații și schimbări.',
        'Ai nevoie de o sortare folosită în producție pentru volume mari.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n), cu oprire când nu apar schimbări',
        'Caz mediu' => 'O(n^2)',
        'Caz rău' => 'O(n^2)',
        'Memorie' => 'O(1)',
        'Stabil' => 'Da',
    ],
    'steps' => [
        'Pornește de la începutul vectorului și compară elementele vecine.',
        'Dacă elementul din stânga este mai mare decât cel din dreapta, le interschimbă.',
        'La finalul unei treceri, cel mai mare element rămas nesortat este fixat la dreapta.',
        'Repetă pentru zona rămasă, care devine mai scurtă cu o poziție după fiecare trecere.',
        'Dacă într-o trecere nu se face nicio interschimbare, vectorul este deja sortat.',
    ],
    'example' => 'Pentru [5, 2, 4, 1], prima trecere mută 5 spre dreapta: [2, 4, 1, 5]. Următoarele treceri fixează 4, apoi 2, până rămâne [1, 2, 4, 5].',
    'pseudocode' => [
        ['line' => 1, 'text' => 'pentru i de la 0 la n - 2'],
        ['line' => 2, 'text' => '  pentru j de la 0 la n - i - 2'],
        ['line' => 3, 'text' => '    dacă v[j] > v[j + 1]'],
        ['line' => 4, 'text' => '      interschimbă v[j] cu v[j + 1]'],
    ],
    'variables' => [
        'i' => 'trecerea curentă',
        'j' => 'perechea comparată',
        'comparisons' => 'comparații făcute',
        'swaps' => 'interschimbări făcute',
    ],
    'cpp' => [
        'void bubbleSort(vector<int>& v) {',
        '    int n = v.size();',
        '    bool schimbat = true;',
        '',
        '    for (int i = 0; i < n - 1 && schimbat; i++) {',
        '        schimbat = false;',
        '',
        '        for (int j = 0; j < n - i - 1; j++) {',
        '            if (v[j] > v[j + 1]) {',
        '                swap(v[j], v[j + 1]);',
        '                schimbat = true;',
        '            }',
        '        }',
        '    }',
        '}',
    ],
    'mistakes' => [
        'Limita interioară trebuie să fie n - i - 1, altfel compari cu o poziție care nu există.',
        'Dacă folosești >= în loc de >, poți strica stabilitatea pentru elemente egale.',
        'Fără oprirea când nu apar schimbări, algoritmul rămâne corect, dar face treceri inutile.',
        'Nu confunda trecerea i cu poziția finală: după fiecare trecere se fixează o valoare la dreapta.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
