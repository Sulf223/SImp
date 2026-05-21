<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/partials/sort_lesson_template.php';

$lesson = [
    'title' => 'Quick',
    'accent' => 'Sort',
    'algorithm' => 'quick',
    'lesson_slug' => 'sort_quick',
    'visualizer_title' => 'Partitionarea Quick Sort',
    'lead' => 'Quick Sort împarte vectorul în jurul unui pivot, apoi sortează recursiv părțile obținute.',
    'idea' => 'Elementul pivot este așezat pe poziția lui finală: în stânga rămân valori mai mici sau egale, iar în dreapta valori mai mari. Apoi același proces se aplică separat pe cele două subsecvențe.',
    'use_when' => [
        'Vrei un algoritm foarte rapid în practică.',
        'Datele pot fi împărțite eficient în jurul unui pivot bun.',
        'Vrei să înțelegi tehnica divide et impera prin partiționare.',
    ],
    'avoid_when' => [
        'Ai nevoie de stabilitate fără structuri suplimentare.',
        'Pivotul ales prost poate apărea des și poate duce la O(n^2).',
        'Stiva recursivă este o problemă pentru date foarte mari sau foarte dezechilibrate.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n log n)',
        'Caz mediu' => 'O(n log n)',
        'Caz rău' => 'O(n^2)',
        'Memorie' => 'O(log n) mediu, din recursivitate',
        'Stabil' => 'Nu, în varianta standard',
    ],
    'steps' => [
        'Alege un pivot, de obicei ultimul element în varianta simplă.',
        'Parcurge secvența și mută în stânga valorile mai mici sau egale cu pivotul.',
        'La final pune pivotul între cele două zone.',
        'Pivotul este acum pe poziția finală.',
        'Sortează recursiv partea din stânga și partea din dreapta.',
    ],
    'example' => 'Pentru [6, 2, 8, 4], pivotul 4 ajunge între [2] și [8, 6], obținând [2, 4, 8, 6]. Apoi se sortează separat zona din dreapta.',
    'pseudocode' => [
        ['line' => 1, 'text' => 'pivot = ultimul element'],
        ['line' => 2, 'text' => 'i = low - 1'],
        ['line' => 3, 'text' => 'pentru j de la low la high - 1'],
        ['line' => 4, 'text' => '  dacă v[j] <= pivot, crește i și interschimbă'],
        ['line' => 5, 'text' => 'pune pivotul pe poziția i + 1'],
        ['line' => 6, 'text' => 'sortează recursiv stânga și dreapta'],
    ],
    'variables' => [
        'low' => 'începutul subsecvenței',
        'high' => 'finalul subsecvenței',
        'pivot' => 'valoarea care separă secvența',
        'i' => 'ultima poziție cu valoare <= pivot',
        'comparisons' => 'comparații făcute',
        'swaps' => 'interschimbări făcute',
    ],
    'cpp' => [
        'int partitionare(vector<int>& v, int low, int high) {',
        '    int pivot = v[high];',
        '    int i = low - 1;',
        '',
        '    for (int j = low; j < high; j++) {',
        '        if (v[j] <= pivot) {',
        '            i++;',
        '            swap(v[i], v[j]);',
        '        }',
        '    }',
        '',
        '    swap(v[i + 1], v[high]);',
        '    return i + 1;',
        '}',
        '',
        'void quickSort(vector<int>& v, int low, int high) {',
        '    if (low >= high) return;',
        '',
        '    int p = partitionare(v, low, high);',
        '    quickSort(v, low, p - 1);',
        '    quickSort(v, p + 1, high);',
        '}',
    ],
    'mistakes' => [
        'Nu include pivotul în apelurile recursive: folosește p - 1 și p + 1.',
        'Dacă alegi mereu ultimul element ca pivot pe date deja sortate, poți ajunge la O(n^2).',
        'Condiția de oprire este low >= high, pentru secvențe cu zero sau un element.',
        'Partiționarea nu sortează complet vectorul; doar pune pivotul pe poziția finală.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
