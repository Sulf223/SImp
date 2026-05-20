<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/partials/sort_lesson_template.php';

$lesson = [
    'title' => 'Counting',
    'accent' => 'Sort',
    'algorithm' => 'counting',
    'lesson_slug' => 'sort_counting',
    'visualizer_title' => 'Counting Sort în acțiune',
    'lead' => 'Counting Sort nu compară elemente; numără de câte ori apare fiecare valoare și reconstruiește vectorul sortat.',
    'idea' => 'Algoritmul este eficient când valorile sunt întregi și se află într-un interval mic. În loc să tot compare perechi de elemente, creează un vector de frecvențe: frecvența[x] spune de câte ori apare x.',
    'use_when' => [
        'Valorile sunt numere întregi într-un interval cunoscut și mic.',
        'Vrei o sortare liniară în funcție de n și de mărimea intervalului.',
        'Ai multe repetiții și comparațiile ar fi inutile.',
    ],
    'avoid_when' => [
        'Valorile sunt reale, texte sau nu pot fi mapate simplu la indici.',
        'Intervalul de valori este foarte mare față de numărul elementelor.',
        'Ai valori negative și nu ai pregătit o translatare a indicilor.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n + k)',
        'Caz mediu' => 'O(n + k)',
        'Caz rău' => 'O(n + k)',
        'Memorie' => 'O(k), sau O(n + k) pentru varianta stabilă',
        'Stabil' => 'Da în varianta cu poziții cumulative',
    ],
    'steps' => [
        'Găsește valoarea maximă sau intervalul posibil al valorilor.',
        'Inițializează vectorul de frecvențe cu zero.',
        'Parcurge vectorul și crește frecvența valorii întâlnite.',
        'Reconstruiește vectorul punând fiecare valoare de câte ori apare.',
        'Pentru varianta stabilă, transformă frecvențele în poziții cumulative.',
    ],
    'example' => 'Pentru [3, 1, 3, 2], frecvențele sunt: 1 apare o dată, 2 apare o dată, 3 apare de două ori. Reconstruirea produce [1, 2, 3, 3].',
    'pseudocode' => [
        ['line' => 1, 'text' => 'creează vectorul de frecvențe'],
        ['line' => 2, 'text' => 'numără aparițiile fiecărei valori'],
        ['line' => 3, 'text' => 'parcurge valorile în ordine crescătoare'],
        ['line' => 4, 'text' => 'scrie fiecare valoare de frecvența ei ori'],
    ],
    'variables' => [
        'count' => 'vectorul de frecvențe',
        'value' => 'valoarea reconstruită',
        'k' => 'mărimea intervalului',
        'index' => 'poziția completată în vector',
    ],
    'cpp' => [
        'void countingSort(vector<int>& v) {',
        '    if (v.empty()) return;',
        '',
        '    int minim = *min_element(v.begin(), v.end());',
        '    int maxim = *max_element(v.begin(), v.end());',
        '    vector<int> frecventa(maxim - minim + 1, 0);',
        '',
        '    for (int x : v) {',
        '        frecventa[x - minim]++;',
        '    }',
        '',
        '    int pozitie = 0;',
        '    for (int i = 0; i < frecventa.size(); i++) {',
        '        int valoare = i + minim;',
        '',
        '        while (frecventa[i] > 0) {',
        '            v[pozitie++] = valoare;',
        '            frecventa[i]--;',
        '        }',
        '    }',
        '}',
    ],
    'mistakes' => [
        'Counting Sort are sens doar când intervalul k nu este prea mare.',
        'Pentru valori negative, folosește un offset: valoarea x se numără la x - minim.',
        'Varianta simplă reconstruiește valori, dar nu păstrează informații asociate elementelor.',
        'Dacă ai nevoie de stabilitate pentru perechi sau obiecte, folosește frecvențe cumulative și vector auxiliar.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
