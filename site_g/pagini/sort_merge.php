<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/partials/sort_lesson_template.php';

$lesson = [
    'title' => 'Merge',
    'accent' => 'Sort',
    'algorithm' => 'merge',
    'lesson_slug' => 'sort_merge',
    'visualizer_title' => 'Interclasarea Merge Sort',
    'lead' => 'Merge Sort imparte vectorul in jumatati, sorteaza fiecare jumatate si apoi le interclaseaza.',
    'idea' => 'Principiul este divide et impera: problema mare se imparte in probleme mici. Cand doua jumatati sunt deja sortate, ele pot fi combinate liniar alegand mereu cel mai mic element de la inceputul uneia dintre jumatati.',
    'use_when' => [
        'Vrei complexitate O(n log n) in orice caz.',
        'Ai nevoie de o sortare stabila.',
        'Lucrezi cu liste sau date unde interclasarea este naturala.',
    ],
    'avoid_when' => [
        'Memoria suplimentara O(n) este o problema.',
        'Vectorul este foarte mic si o metoda simpla ar fi suficienta.',
        'Vrei o sortare in-place stricta, fara vector auxiliar.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n log n)',
        'Caz mediu' => 'O(n log n)',
        'Caz rau' => 'O(n log n)',
        'Memorie' => 'O(n)',
        'Stabil' => 'Da, daca la egalitate alegi din stanga',
    ],
    'steps' => [
        'Imparte vectorul in doua jumatati.',
        'Sorteaza recursiv jumatatea stanga.',
        'Sorteaza recursiv jumatatea dreapta.',
        'Interclaseaza cele doua jumatati sortate intr-un vector auxiliar.',
        'Copiaza rezultatul inapoi in vectorul initial.',
    ],
    'example' => 'Pentru [5, 1, 4, 2], se obtin [1, 5] si [2, 4], apoi interclasarea alege pe rand 1, 2, 4, 5.',
    'pseudocode' => [
        ['line' => 1, 'text' => 'imparte vectorul in doua jumatati'],
        ['line' => 2, 'text' => 'sorteaza recursiv jumatatea stanga'],
        ['line' => 3, 'text' => 'sorteaza recursiv jumatatea dreapta'],
        ['line' => 4, 'text' => 'interclaseaza cele doua jumatati sortate'],
        ['line' => 5, 'text' => 'copiaza rezultatul in vectorul initial'],
    ],
    'variables' => [
        'left' => 'prima jumatate sortata',
        'right' => 'a doua jumatate sortata',
        'i / j' => 'pozitii in cele doua jumatati',
        'k' => 'pozitia din vectorul final',
    ],
    'cpp' => [
        'void interclasare(vector<int>& v, int st, int mij, int dr) {',
        '    vector<int> aux;',
        '    int i = st, j = mij + 1;',
        '',
        '    while (i <= mij && j <= dr) {',
        '        if (v[i] <= v[j]) {',
        '            aux.push_back(v[i++]);',
        '        } else {',
        '            aux.push_back(v[j++]);',
        '        }',
        '    }',
        '',
        '    while (i <= mij) aux.push_back(v[i++]);',
        '    while (j <= dr) aux.push_back(v[j++]);',
        '',
        '    for (int k = 0; k < aux.size(); k++) {',
        '        v[st + k] = aux[k];',
        '    }',
        '}',
        '',
        'void mergeSort(vector<int>& v, int st, int dr) {',
        '    if (st >= dr) return;',
        '',
        '    int mij = st + (dr - st) / 2;',
        '    mergeSort(v, st, mij);',
        '    mergeSort(v, mij + 1, dr);',
        '    interclasare(v, st, mij, dr);',
        '}',
    ],
    'mistakes' => [
        'La calculul mijlocului, st + (dr - st) / 2 evita depasiri pentru indici foarte mari.',
        'Dupa while-ul principal trebuie copiate elementele ramase din ambele jumatati.',
        'Pentru stabilitate, la egalitate alege elementul din jumatatea stanga.',
        'Nu uita sa copiezi vectorul auxiliar inapoi in intervalul [st, dr].',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
