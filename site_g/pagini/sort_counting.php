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
    'visualizer_title' => 'Counting Sort in actiune',
    'lead' => 'Counting Sort nu compara elemente; numara de cate ori apare fiecare valoare si reconstruieste vectorul sortat.',
    'idea' => 'Algoritmul este eficient cand valorile sunt intregi si se afla intr-un interval mic. In loc sa tot compare perechi de elemente, creeaza un vector de frecvente: frecventa[x] spune de cate ori apare x.',
    'use_when' => [
        'Valorile sunt numere intregi intr-un interval cunoscut si mic.',
        'Vrei o sortare liniara in functie de n si de marimea intervalului.',
        'Ai multe repetitii si comparatiile ar fi inutile.',
    ],
    'avoid_when' => [
        'Valorile sunt reale, texte sau nu pot fi mapate simplu la indici.',
        'Intervalul de valori este foarte mare fata de numarul elementelor.',
        'Ai valori negative si nu ai pregatit o translatare a indicilor.',
    ],
    'metrics' => [
        'Caz bun' => 'O(n + k)',
        'Caz mediu' => 'O(n + k)',
        'Caz rau' => 'O(n + k)',
        'Memorie' => 'O(k), sau O(n + k) pentru varianta stabila',
        'Stabil' => 'Da in varianta cu pozitii cumulative',
    ],
    'steps' => [
        'Gaseste valoarea maxima sau intervalul posibil al valorilor.',
        'Initializeaza vectorul de frecvente cu zero.',
        'Parcurge vectorul si creste frecventa valorii intalnite.',
        'Reconstruieste vectorul punand fiecare valoare de cate ori apare.',
        'Pentru varianta stabila, transforma frecventele in pozitii cumulative.',
    ],
    'example' => 'Pentru [3, 1, 3, 2], frecventele sunt: 1 apare o data, 2 apare o data, 3 apare de doua ori. Reconstruirea produce [1, 2, 3, 3].',
    'pseudocode' => [
        ['line' => 1, 'text' => 'creeaza vectorul de frecvente'],
        ['line' => 2, 'text' => 'numara aparitiile fiecarei valori'],
        ['line' => 3, 'text' => 'parcurge valorile in ordine crescatoare'],
        ['line' => 4, 'text' => 'scrie fiecare valoare de frecventa ei ori'],
    ],
    'variables' => [
        'count' => 'vectorul de frecvente',
        'value' => 'valoarea reconstruita',
        'k' => 'marimea intervalului',
        'index' => 'pozitia completata in vector',
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
        'Counting Sort are sens doar cand intervalul k nu este prea mare.',
        'Pentru valori negative, foloseste un offset: valoarea x se numara la x - minim.',
        'Varianta simpla reconstruieste valori, dar nu pastreaza informatii asociate elementelor.',
        'Daca ai nevoie de stabilitate pentru perechi sau obiecte, foloseste frecvente cumulative si vector auxiliar.',
    ],
];

render_sort_lesson($lesson, $nonce ?? '');
