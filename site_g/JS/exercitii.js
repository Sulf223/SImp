// Exerciții interactive W3-style pe lecții fundamentale + tracking progres
(function () {
    const allExercises = [
        {
            id: 'bubble_1',
            lesson: 'sort_bubble',
            titlu: 'Bubble Sort - conditia din if',
            text: 'Completeaza conditia astfel incat vectorul sa fie sortat crescator.',
            cod: [
                'if ( ____ ) {',
                '    int aux = v[i];',
                '    v[i] = v[i + 1];',
                '    v[i + 1] = aux;',
                '}'
            ],
            raspunsuri: ['v[i] > v[i + 1]'],
            hint: 'Compara elementul curent cu urmatorul si inverseaza doar daca sunt in ordinea gresita.'
        },
        {
            id: 'bubble_2',
            lesson: 'sort_bubble',
            titlu: 'Bubble Sort - limita buclei interioare',
            text: 'Completeaza limita lui j.',
            cod: [
                'for (int j = 0; j < ____; j++) {',
                '    if (v[j] > v[j + 1]) {',
                '        // swap',
                '    }',
                '}'
            ],
            raspunsuri: ['n - i - 1'],
            hint: 'La fiecare pas i, ultimele i elemente sunt deja pozitionate.'
        },
        {
            id: 'bubble_3',
            lesson: 'sort_bubble',
            titlu: 'Bubble Sort - finalul swap-ului',
            text: 'Completeaza ultima linie din interschimbare.',
            cod: [
                'int aux = v[j];',
                'v[j] = v[j + 1];',
                'v[j + 1] = ____;'
            ],
            raspunsuri: ['aux'],
            hint: 'La final pui inapoi valoarea salvata in variabila auxiliara.'
        },
        {
            id: 'selection_1',
            lesson: 'sort_selection',
            titlu: 'Selection Sort - actualizare minim',
            text: 'Completeaza expresia care actualizeaza indexul minim.',
            cod: [
                'if (v[j] < v[minIdx]) {',
                '    minIdx = ____;',
                '}'
            ],
            raspunsuri: ['j'],
            hint: 'Cand gasesti un element mai mic, memorezi pozitia lui curenta.'
        },
        {
            id: 'selection_2',
            lesson: 'sort_selection',
            titlu: 'Selection Sort - swap final',
            text: 'Completeaza swap-ul dintre pozitia curenta si minimul gasit.',
            cod: [
                'int aux = v[i];',
                'v[i] = ____;',
                'v[minIdx] = aux;'
            ],
            raspunsuri: ['v[minIdx]'],
            hint: 'Pe pozitia i trebuie adus minimul gasit in sub-secventa nesortata.'
        },
        {
            id: 'insertion_1',
            lesson: 'sort_insertion',
            titlu: 'Insertion Sort - cheia',
            text: 'Completeaza linia care salveaza elementul curent.',
            cod: [
                'for (int i = 1; i < n; i++) {',
                '    int key = ____;',
                '}'
            ],
            raspunsuri: ['v[i]'],
            hint: 'Cheia este elementul de pe pozitia curenta i.'
        },
        {
            id: 'insertion_2',
            lesson: 'sort_insertion',
            titlu: 'Insertion Sort - conditia while',
            text: 'Completeaza conditia pentru deplasarea elementelor.',
            cod: [
                'while ( ____ ) {',
                '    v[j + 1] = v[j];',
                '    j--;',
                '}'
            ],
            raspunsuri: ['j >= 0 && v[j] > key'],
            hint: 'Muti spre dreapta cat timp mai ai elemente in stanga si acestea sunt mai mari decat key.'
        },
        {
            id: 'insertion_3',
            lesson: 'sort_insertion',
            titlu: 'Insertion Sort - plasarea finala',
            text: 'Completeaza plasarea finala a cheii.',
            cod: [
                'v[j + 1] = ____;'
            ],
            raspunsuri: ['key'],
            hint: 'Dupa deplasari, key merge pe pozitia j + 1.'
        },
        {
            id: 'quick_1',
            lesson: 'sort_quick',
            titlu: 'Quick Sort - pivotul',
            text: 'Completeaza alegerea pivotului in varianta clasica.',
            cod: [
                'int pivot = ____;'
            ],
            raspunsuri: ['arr[high]'],
            hint: 'In implementarea uzuala, pivotul este ultimul element din segment.'
        },
        {
            id: 'quick_2',
            lesson: 'sort_quick',
            titlu: 'Quick Sort - conditia partitionarii',
            text: 'Completeaza conditia pentru mutarea in stanga pivotului.',
            cod: [
                'if (____) {',
                '    i++;',
                '    swap(&arr[i], &arr[j]);',
                '}'
            ],
            raspunsuri: ['arr[j] <= pivot'],
            hint: 'Elementele <= pivot ajung in partea stanga.'
        },
        {
            id: 'quick_3',
            lesson: 'sort_quick',
            titlu: 'Quick Sort - recursia pe stanga',
            text: 'Completeaza apelul recursiv pentru subvectorul din stanga.',
            cod: [
                'int pi = partition(arr, low, high);',
                '____;',
                'quickSort(arr, pi + 1, high);'
            ],
            raspunsuri: ['quickSort(arr, low, pi - 1)'],
            hint: 'Partea stanga este delimitata de low .. pi - 1.'
        },
        {
            id: 'merge_1',
            lesson: 'sort_merge',
            titlu: 'Merge - comparatia in interclasare',
            text: 'Completeaza conditia pentru alegerea elementului mai mic.',
            cod: [
                'if ( ____ ) {',
                '    C[k++] = A[i++];',
                '} else {',
                '    C[k++] = B[j++];',
                '}'
            ],
            raspunsuri: ['A[i] <= B[j]'],
            hint: 'Interclasarea corecta ia elementul mai mic dintre A[i] si B[j].'
        },
        {
            id: 'merge_2',
            lesson: 'sort_merge',
            titlu: 'Merge Sort - conditia de oprire',
            text: 'Completeaza baza recursiei.',
            cod: [
                'if ( ____ ) return;'
            ],
            raspunsuri: ['st >= dr'],
            hint: 'Recursia se opreste cand sub-vectorul are 0 sau 1 element.'
        },
        {
            id: 'counting_1',
            lesson: 'sort_counting',
            titlu: 'Counting Sort - frecventa',
            text: 'Completeaza incrementarea vectorului de frecventa.',
            cod: [
                'for (int i = 0; i < n; i++) {',
                '    freq[ ____ ]++;',
                '}'
            ],
            raspunsuri: ['v[i]'],
            hint: 'Indexul din frecventa este valoarea elementului.'
        },
        {
            id: 'counting_2',
            lesson: 'sort_counting',
            titlu: 'Counting Sort - reconstructie',
            text: 'Completeaza valoarea copiata in vectorul final.',
            cod: [
                'while (freq[x]-- > 0) {',
                '    v[p++] = ____;',
                '}'
            ],
            raspunsuri: ['x'],
            hint: 'Scriem de atatea ori valoarea x cat indica frecventa.'
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
                    setLessonProgressText('Progres lectie: ' + done + '/' + total + ' exercitii rezolvate (' + pct + '%)');
                }
            })
            .catch(() => {
                // Nu blocam UI-ul pe erori de retea.
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
            setFeedback('Bravo, raspuns corect!', true);
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
            setHint('Sugestie: ' + (ex.hint || 'Reia pasii algoritmului si observa ce lipseste.'));
            helpClicks++;
        } else {
            setHint('O varianta corecta: ' + (ex.raspunsuri && ex.raspunsuri[0] ? ex.raspunsuri[0] : '-'));
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

        setLessonProgressText('Exercitii disponibile: ' + currentSet.length);
        afiseazaExercitiu();
    });
})();
