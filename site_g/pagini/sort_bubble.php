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
    'visualizer_title' => 'Bubble Sort in actiune',
    'lead' => 'Un algoritm de sortare simplu: compara elemente vecine si impinge treptat valorile mari spre finalul vectorului.',
    'idea' => 'Bubble Sort parcurge vectorul de mai multe ori. La fiecare trecere compara doua elemente alaturate si le interschimba daca sunt in ordine gresita. Dupa prima trecere, cel mai mare element ajunge pe ultima pozitie; dupa a doua, urmatorul cel mai mare ajunge pe penultima pozitie.',
    'use_when' => [
        'Vrei sa intelegi mecanismul de baza al sortarii prin interschimbare.',
        'Vectorul este foarte mic sau aproape sortat.',
        'Ai nevoie de un exemplu usor de urmarit pas cu pas.',
    ],
    'avoid_when' => [
        'Ai multe elemente si conteaza performanta.',
        'Datele sunt amestecate puternic, pentru ca apar multe comparatii si schimbari.',
        'Ai nevoie de o sortare folosita in productie pentru volume mari.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n), cu oprire cand nu apar schimbari',
        'Caz mediu' => 'O(n^2)',
        'Caz rau' => 'O(n^2)',
        'Memorie' => 'O(1)',
        'Stabil' => 'Da',
    ],
    'steps' => [
        'Porneste de la inceputul vectorului si compara elementele vecine.',
        'Daca elementul din stanga este mai mare decat cel din dreapta, le interschimba.',
        'La finalul unei treceri, cel mai mare element ramas nesortat este fixat la dreapta.',
        'Repeta pentru zona ramasa, care devine mai scurta cu o pozitie dupa fiecare trecere.',
        'Daca intr-o trecere nu se face nicio interschimbare, vectorul este deja sortat.',
    ],
    'example' => 'Pentru [5, 2, 4, 1], prima trecere muta 5 spre dreapta: [2, 4, 1, 5]. Urmatoarele treceri fixeaza 4, apoi 2, pana ramane [1, 2, 4, 5].',
    'pseudocode' => [
        ['line' => 1, 'text' => 'pentru i de la 0 la n - 2'],
        ['line' => 2, 'text' => '  pentru j de la 0 la n - i - 2'],
        ['line' => 3, 'text' => '    daca v[j] > v[j + 1]'],
        ['line' => 4, 'text' => '      interschimba v[j] cu v[j + 1]'],
    ],
    'variables' => [
        'i' => 'trecerea curenta',
        'j' => 'perechea comparata',
        'comparisons' => 'comparatii facute',
        'swaps' => 'interschimbari facute',
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
        'Limita interioara trebuie sa fie n - i - 1, altfel compari cu o pozitie care nu exista.',
        'Daca folosesti >= in loc de >, poti strica stabilitatea pentru elemente egale.',
        'Fara oprirea cand nu apar schimbari, algoritmul ramane corect, dar face treceri inutile.',
        'Nu confunda trecerea i cu pozitia finala: dupa fiecare trecere se fixeaza o valoare la dreapta.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
