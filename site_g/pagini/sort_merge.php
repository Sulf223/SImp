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
    'lead' => 'Merge Sort împarte vectorul în jumătăți, sortează fiecare jumătate și apoi le interclasează.',
    'idea' => 'Principiul este divide et impera: problema mare se împarte în probleme mici. Când două jumătăți sunt deja sortate, ele pot fi combinate liniar alegând mereu cel mai mic element de la începutul uneia dintre jumătăți.',
    'use_when' => [
        'Vrei complexitate O(n log n) în orice caz.',
        'Ai nevoie de o sortare stabilă.',
        'Lucrezi cu liste sau date unde interclasarea este naturală.',
    ],
    'avoid_when' => [
        'Memoria suplimentară O(n) este o problemă.',
        'Vectorul este foarte mic și o metodă simplă ar fi suficientă.',
        'Vrei o sortare in-place strictă, fără vector auxiliar.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n log n)',
        'Caz mediu' => 'O(n log n)',
        'Caz rău' => 'O(n log n)',
        'Memorie' => 'O(n)',
        'Stabil' => 'Da, dacă la egalitate alegi din stânga',
    ],
    'steps' => [
        'Împarte vectorul în două jumătăți.',
        'Sortează recursiv jumătatea stângă.',
        'Sortează recursiv jumătatea dreaptă.',
        'Interclasează cele două jumătăți sortate într-un vector auxiliar.',
        'Copiază rezultatul înapoi în vectorul inițial.',
    ],
    'example' => 'Pentru [5, 1, 4, 2], se obțin [1, 5] și [2, 4], apoi interclasarea alege pe rând 1, 2, 4, 5.',
    'pseudocode' => [
        ['line' => 1, 'text' => 'împarte vectorul în două jumătăți'],
        ['line' => 2, 'text' => 'sortează recursiv jumătatea stângă'],
        ['line' => 3, 'text' => 'sortează recursiv jumătatea dreaptă'],
        ['line' => 4, 'text' => 'interclasează cele două jumătăți sortate'],
        ['line' => 5, 'text' => 'copiază rezultatul în vectorul inițial'],
    ],
    'variables' => [
        'left' => 'prima jumătate sortată',
        'right' => 'a doua jumătate sortată',
        'i / j' => 'poziții în cele două jumătăți',
        'k' => 'poziția din vectorul final',
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
        'La calculul mijlocului, st + (dr - st) / 2 evită depășiri pentru indici foarte mari.',
        'După while-ul principal trebuie copiate elementele rămase din ambele jumătăți.',
        'Pentru stabilitate, la egalitate alege elementul din jumătatea stângă.',
        'Nu uita să copiezi vectorul auxiliar înapoi în intervalul [st, dr].',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
