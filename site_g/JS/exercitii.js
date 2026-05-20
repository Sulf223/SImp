// Exerciții interactive W3-style pe lecții fundamentale + tracking progres
(function () {
    const allExercises = [
        {
            id: 'bubble_1',
            lesson: 'sort_bubble',
            titlu: 'Bubble Sort - condiția din if',
            text: 'Completează condiția astfel încât vectorul să fie sortat crescător.',
            cod: [
                'if ( ____ ) {',
                '    int aux = v[i];',
                '    v[i] = v[i + 1];',
                '    v[i + 1] = aux;',
                '}'
            ],
            raspunsuri: ['v[i] > v[i + 1]'],
            hint: 'Compară elementul curent cu următorul și inversează doar dacă sunt în ordinea greșită.'
        },
        {
            id: 'bubble_2',
            lesson: 'sort_bubble',
            titlu: 'Bubble Sort - limita buclei interioare',
            text: 'Completează limita lui j.',
            cod: [
                'for (int j = 0; j < ____; j++) {',
                '    if (v[j] > v[j + 1]) {',
                '        // swap',
                '    }',
                '}'
            ],
            raspunsuri: ['n - i - 1'],
            hint: 'La fiecare pas i, ultimele i elemente sunt deja poziționate.'
        },
        {
            id: 'bubble_3',
            lesson: 'sort_bubble',
            titlu: 'Bubble Sort - finalul swap-ului',
            text: 'Completează ultima linie din interschimbare.',
            cod: [
                'int aux = v[j];',
                'v[j] = v[j + 1];',
                'v[j + 1] = ____;'
            ],
            raspunsuri: ['aux'],
            hint: 'La final pui înapoi valoarea salvată în variabila auxiliară.'
        },
        {
            id: 'selection_1',
            lesson: 'sort_selection',
            titlu: 'Selection Sort - actualizare minim',
            text: 'Completează expresia care actualizează indexul minim.',
            cod: [
                'if (v[j] < v[minIdx]) {',
                '    minIdx = ____;',
                '}'
            ],
            raspunsuri: ['j'],
            hint: 'Când găsești un element mai mic, memorezi poziția lui curentă.'
        },
        {
            id: 'selection_2',
            lesson: 'sort_selection',
            titlu: 'Selection Sort - swap final',
            text: 'Completează swap-ul dintre poziția curentă și minimul găsit.',
            cod: [
                'int aux = v[i];',
                'v[i] = ____;',
                'v[minIdx] = aux;'
            ],
            raspunsuri: ['v[minIdx]'],
            hint: 'Pe poziția i trebuie adus minimul găsit în sub-secvența nesortată.'
        },
        {
            id: 'insertion_1',
            lesson: 'sort_insertion',
            titlu: 'Insertion Sort - cheia',
            text: 'Completează linia care salvează elementul curent.',
            cod: [
                'for (int i = 1; i < n; i++) {',
                '    int key = ____;',
                '}'
            ],
            raspunsuri: ['v[i]'],
            hint: 'Cheia este elementul de pe poziția curentă i.'
        },
        {
            id: 'insertion_2',
            lesson: 'sort_insertion',
            titlu: 'Insertion Sort - condiția while',
            text: 'Completează condiția pentru deplasarea elementelor.',
            cod: [
                'while ( ____ ) {',
                '    v[j + 1] = v[j];',
                '    j--;',
                '}'
            ],
            raspunsuri: ['j >= 0 && v[j] > key'],
            hint: 'Muți spre dreapta cât timp mai ai elemente în stânga și acestea sunt mai mari decât key.'
        },
        {
            id: 'insertion_3',
            lesson: 'sort_insertion',
            titlu: 'Insertion Sort - plasarea finală',
            text: 'Completează plasarea finală a cheii.',
            cod: [
                'v[j + 1] = ____;'
            ],
            raspunsuri: ['key'],
            hint: 'După deplasări, key merge pe poziția j + 1.'
        },
        {
            id: 'quick_1',
            lesson: 'sort_quick',
            titlu: 'Quick Sort - pivotul',
            text: 'Completează alegerea pivotului în varianta clasică.',
            cod: [
                'int pivot = ____;'
            ],
            raspunsuri: ['arr[high]'],
            hint: 'În implementarea uzuală, pivotul este ultimul element din segment.'
        },
        {
            id: 'quick_2',
            lesson: 'sort_quick',
            titlu: 'Quick Sort - condiția partiționării',
            text: 'Completează condiția pentru mutarea în stânga pivotului.',
            cod: [
                'if (____) {',
                '    i++;',
                '    swap(&arr[i], &arr[j]);',
                '}'
            ],
            raspunsuri: ['arr[j] <= pivot'],
            hint: 'Elementele <= pivot ajung în partea stângă.'
        },
        {
            id: 'quick_3',
            lesson: 'sort_quick',
            titlu: 'Quick Sort - recursia pe stânga',
            text: 'Completează apelul recursiv pentru subvectorul din stânga.',
            cod: [
                'int pi = partition(arr, low, high);',
                '____;',
                'quickSort(arr, pi + 1, high);'
            ],
            raspunsuri: ['quickSort(arr, low, pi - 1)'],
            hint: 'Partea stângă este delimitată de low .. pi - 1.'
        },
        {
            id: 'merge_1',
            lesson: 'sort_merge',
            titlu: 'Merge - comparația în interclasare',
            text: 'Completează condiția pentru alegerea elementului mai mic.',
            cod: [
                'if ( ____ ) {',
                '    C[k++] = A[i++];',
                '} else {',
                '    C[k++] = B[j++];',
                '}'
            ],
            raspunsuri: ['A[i] <= B[j]'],
            hint: 'Interclasarea corectă ia elementul mai mic dintre A[i] și B[j].'
        },
        {
            id: 'merge_2',
            lesson: 'sort_merge',
            titlu: 'Merge Sort - condiția de oprire',
            text: 'Completează baza recursiei.',
            cod: [
                'if ( ____ ) return;'
            ],
            raspunsuri: ['st >= dr'],
            hint: 'Recursia se oprește când sub-vectorul are 0 sau 1 element.'
        },
        {
            id: 'counting_1',
            lesson: 'sort_counting',
            titlu: 'Counting Sort - frecvența',
            text: 'Completează incrementarea vectorului de frecvență.',
            cod: [
                'for (int i = 0; i < n; i++) {',
                '    freq[ ____ ]++;',
                '}'
            ],
            raspunsuri: ['v[i]'],
            hint: 'Indexul din frecvență este valoarea elementului.'
        },
        {
            id: 'counting_2',
            lesson: 'sort_counting',
            titlu: 'Counting Sort - reconstrucție',
            text: 'Completează valoarea copiată în vectorul final.',
            cod: [
                'while (freq[x]-- > 0) {',
                '    v[p++] = ____;',
                '}'
            ],
            raspunsuri: ['x'],
            hint: 'Scriem de atâtea ori valoarea x cât indică frecvența.'
        }
    ];

    let indexCurent = 0;
    let helpClicks = 0;
    let currentSet = [];
    const solvedInSession = new Set();

    function normalize(str) {
        if (typeof str !== 'string') return '';
        return str.replace(/\s+/g, '').toLowerCase();
    }

    function getLessonSlug() {
        const container = document.getElementById('exercitiu-container');
        if (container && container.dataset.lesson) {
            return container.dataset.lesson;
        }

        const params = new URLSearchParams(window.location.search);
        return params.get('page') || '';
    }

    function getCurrentExercise() {
        return currentSet[indexCurent] || null;
    }

    function setFeedback(html, isOk) {
        const fb = document.getElementById('feedback');
        if (!fb) return;
        fb.innerHTML = html;
        fb.style.color = isOk ? '#15803d' : '#b91c1c';
        fb.style.display = html ? 'block' : 'none';
    }

    function setHint(text) {
        const h = document.getElementById('hint');
        if (!h) return;
        h.innerText = text;
        h.style.display = 'block';
    }

    function setLessonProgressText(text) {
        const el = document.getElementById('lesson-progress-status');
        if (!el) return;
        el.textContent = text;
    }

    function afiseazaExercitiu() {
        const ex = getCurrentExercise();
        const container = document.getElementById('exercitiu-container');
        if (!container || !ex) return;

        let html = '<h3>' + ex.titlu + '</h3>';
        html += '<p>' + ex.text + '</p>';
        html += '<pre><code>';

        for (let i = 0; i < ex.cod.length; i++) {
            const line = ex.cod[i];
            if (line.indexOf('____') !== -1) {
                html += line.replace('____', "<input type='text' id='raspuns" + i + "' class='exercise-input' size='30'>") + '\n';
            } else {
                html += line + '\n';
            }
        }

        html += '</code></pre>';
        container.innerHTML = html;

        setFeedback('', false);
        const hint = document.getElementById('hint');
        if (hint) {
            hint.innerText = '';
            hint.style.display = 'none';
        }
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function reportExerciseCompletion(ex) {
        if (!ex || !ex.lesson) return;
        fetch('PHP/progres_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrfToken()
            },
            body: JSON.stringify({
                action: 'mark_exercise_complete',
                lesson: ex.lesson,
                exerciseKey: ex.id
            })
        })
            .then((res) => res.json())
            .then((data) => {
                if (data && data.ok && data.stats) {
                    const done = Number(data.stats.done || 0);
                    const total = Number(data.stats.total || 0);
                    const pct = Number(data.progress || 0);
                    setLessonProgressText('Progres lecție: ' + done + '/' + total + ' exerciții rezolvate (' + pct + '%)');
                }
            })
            .catch(() => {
                // Nu blocăm UI-ul pe erori de rețea.
            });
    }

    window.verificaExercitiu = function () {
        const ex = getCurrentExercise();
        if (!ex) return;

        let corect = true;
        let lastUserInput = '';

        for (let i = 0; i < ex.cod.length; i++) {
            if (ex.cod[i].indexOf('____') !== -1) {
                const inputEl = document.getElementById('raspuns' + i);
                const userInput = (inputEl && inputEl.value) || '';
                lastUserInput = userInput;
                const normalizedUser = normalize(userInput);
                const isCorrect = (ex.raspunsuri || []).map(normalize).some((r) => r === normalizedUser);
                if (!isCorrect) {
                    corect = false;
                }
            }
        }

        if (corect) {
            setFeedback('Bravo, răspuns corect!', true);
            if (!solvedInSession.has(ex.id)) {
                solvedInSession.add(ex.id);
                reportExerciseCompletion(ex);
            }
        } else {
            const context = {
                cod: ex.cod.join('\n'),
                raspuns_user: lastUserInput
            };
            
            const html = `
                <div style="display: flex; flex-direction: column; gap: var(--space-2);">
                    <span>Răspuns greșit. Încearcă din nou sau apasă Ajutor.</span>
                    <button class="btn btn--quiet btn--xs" style="background: rgba(239, 68, 68, 0.1); color: var(--color-danger); align-self: flex-start; border: 1px solid var(--color-danger-soft);" 
                        data-ask-ai="exercise" 
                        data-context='${JSON.stringify(context).replace(/'/g, "&#39;")}'>
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                        Întreabă AI-ul
                    </button>
                </div>
            `;
            setFeedback(html, false);
        }
    };

    window.urmatorulExercitiu = function () {
        if (currentSet.length === 0) return;
        indexCurent = (indexCurent + 1) % currentSet.length;
        helpClicks = 0;
        afiseazaExercitiu();
    };

    window.afiseazaAjutor = function () {
        const ex = getCurrentExercise();
        if (!ex) return;

        if (helpClicks === 0) {
            setHint('Sugestie: ' + (ex.hint || 'Reia pașii algoritmului și observă ce lipsește.'));
            helpClicks++;
        } else {
            setHint('O variantă corectă: ' + (ex.raspunsuri && ex.raspunsuri[0] ? ex.raspunsuri[0] : '-'));
        }
    };

    window.addEventListener('load', function () {
        const container = document.getElementById('exercitiu-container');
        if (!container) return;

        const lesson = getLessonSlug();
        if (lesson) {
            currentSet = allExercises.filter((ex) => ex.lesson === lesson);
        }

        if (currentSet.length === 0) {
            currentSet = allExercises.slice();
        }

        setLessonProgressText('Exerciții disponibile: ' + currentSet.length);
        afiseazaExercitiu();
    });
})();
