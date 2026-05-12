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
    'visualizer_title' => 'Selection Sort in actiune',
    'lead' => 'Selection Sort cauta minimul din zona nesortata si il aseaza pe prima pozitie libera.',
    'idea' => 'Algoritmul imparte vectorul in doua zone: stanga este sortata, dreapta este inca nesortata. La fiecare pas cauta cel mai mic element din zona nesortata si il muta la inceputul acestei zone.',
    'use_when' => [
        'Vrei o metoda simpla, cu putine interschimbari.',
        'Ai date putine si vrei sa explici clar ideea de minim selectat.',
        'Costul interschimbarilor este mai important decat numarul comparatiilor.',
    ],
    'avoid_when' => [
        'Ai nevoie de un algoritm rapid pentru vectori mari.',
        'Ai nevoie de stabilitate fara modificari suplimentare.',
        'Datele sunt deja aproape sortate; algoritmul tot cauta minimul complet.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n^2)',
        'Caz mediu' => 'O(n^2)',
        'Caz rau' => 'O(n^2)',
        'Memorie' => 'O(1)',
        'Stabil' => 'Nu, in varianta standard',
    ],
    'steps' => [
        'Considera pozitia i ca prima pozitie libera din zona nesortata.',
        'Presupune ca minimul este pe pozitia i.',
        'Cauta in restul vectorului o valoare mai mica.',
        'Daca gaseste una, actualizeaza pozitia minimului.',
        'La final, interschimba minimul gasit cu elementul de pe pozitia i.',
    ],
    'example' => 'Pentru [7, 3, 5, 2], la primul pas minimul este 2, deci vectorul devine [2, 3, 5, 7]. Urmatoarele pozitii sunt deja in ordine, dar comparatiile continua.',
    'pseudocode' => [
        ['line' => 1, 'text' => 'pentru i de la 0 la n - 2'],
        ['line' => 2, 'text' => '  minIndex = i'],
        ['line' => 3, 'text' => '  pentru j de la i + 1 la n - 1'],
        ['line' => 4, 'text' => '    daca v[j] < v[minIndex], minIndex = j'],
        ['line' => 5, 'text' => '  interschimba v[i] cu v[minIndex]'],
    ],
    'variables' => [
        'i' => 'prima pozitie nesortata',
        'j' => 'pozitia testata',
        'minIndex' => 'pozitia minimului curent',
        'swaps' => 'mutari ale minimului',
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
        'Nu interschimba imediat cand gasesti o valoare mai mica; intai termini cautarea minimului.',
        'Bucla interioara incepe de la i + 1, pentru ca pozitia i este deja candidatul initial.',
        'Selection Sort nu devine mai rapid doar fiindca vectorul este aproape sortat.',
        'Daca ai elemente egale, varianta standard poate schimba ordinea lor relativa.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
