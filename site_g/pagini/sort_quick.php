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
    'lead' => 'Quick Sort imparte vectorul in jurul unui pivot, apoi sorteaza recursiv partile obtinute.',
    'idea' => 'Elementul pivot este asezat pe pozitia lui finala: in stanga raman valori mai mici sau egale, iar in dreapta valori mai mari. Apoi acelasi proces se aplica separat pe cele doua subsecvente.',
    'use_when' => [
        'Vrei un algoritm foarte rapid in practica.',
        'Datele pot fi impartite eficient in jurul unui pivot bun.',
        'Vrei sa intelegi tehnica divide et impera prin partitionare.',
    ],
    'avoid_when' => [
        'Ai nevoie de stabilitate fara structuri suplimentare.',
        'Pivotul ales prost poate aparea des si poate duce la O(n^2).',
        'Stiva recursiva este o problema pentru date foarte mari sau foarte dezechilibrate.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n log n)',
        'Caz mediu' => 'O(n log n)',
        'Caz rau' => 'O(n^2)',
        'Memorie' => 'O(log n) mediu, din recursivitate',
        'Stabil' => 'Nu, in varianta standard',
    ],
    'steps' => [
        'Alege un pivot, de obicei ultimul element in varianta simpla.',
        'Parcurge secventa si muta in stanga valorile mai mici sau egale cu pivotul.',
        'La final pune pivotul intre cele doua zone.',
        'Pivotul este acum pe pozitia finala.',
        'Sorteaza recursiv partea din stanga si partea din dreapta.',
    ],
    'example' => 'Pentru [6, 2, 8, 4], pivotul 4 ajunge intre [2] si [8, 6], obtinand [2, 4, 8, 6]. Apoi se sorteaza separat zona din dreapta.',
    'pseudocode' => [
        ['line' => 1, 'text' => 'pivot = ultimul element'],
        ['line' => 2, 'text' => 'i = low - 1'],
        ['line' => 3, 'text' => 'pentru j de la low la high - 1'],
        ['line' => 4, 'text' => '  daca v[j] <= pivot, creste i si interschimba'],
        ['line' => 5, 'text' => 'pune pivotul pe pozitia i + 1'],
        ['line' => 6, 'text' => 'sorteaza recursiv stanga si dreapta'],
    ],
    'variables' => [
        'pivot' => 'valoarea care separa secventa',
        'i' => 'ultima pozitie cu valoare <= pivot',
        'j' => 'elementul analizat',
        'range' => 'subsecventa curenta',
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
        'Nu include pivotul in apelurile recursive: foloseste p - 1 si p + 1.',
        'Daca alegi mereu ultimul element ca pivot pe date deja sortate, poti ajunge la O(n^2).',
        'Conditia de oprire este low >= high, pentru secvente cu zero sau un element.',
        'Partitionarea nu sorteaza complet vectorul; doar pune pivotul pe pozitia finala.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
