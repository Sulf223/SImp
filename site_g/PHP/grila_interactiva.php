<?php
// PHP/grila_interactiva.php
include_once 'conexiune.php';
include_once 'auth.php';

$mode = $_GET['mode'] ?? 'db';
if ($mode !== 'w3') {
    require_login();
}

$id_grila = $_GET['id'] ?? 0;
$grila = null;
$raspunsuri = [];
$next_id = 0;

if ($mode === 'w3') {
    $banca_intrebari = [
        ['set'=>'recursivitate', 'metoda'=>'Recursivitate', 'dificultate'=>'Usor', 'intrebare'=>'Ce reprezinta cazul de baza intr-un algoritm recursiv?', 'optiuni'=>['Apelul recursiv principal','Conditia care opreste recursia','Vectorul de intrare','Pasul de interschimbare'], 'corect'=>1, 'explicatie'=>'Cazul de baza previne recursia infinita.'],
        ['set'=>'recursivitate', 'metoda'=>'Recursivitate', 'dificultate'=>'Mediu', 'intrebare'=>'Ce valoare intoarce factorial(0)?', 'optiuni'=>['0','1','Nu este definit','Depinde de compilator'], 'corect'=>1, 'explicatie'=>'By definition, 0! = 1.'],
        ['set'=>'backtracking', 'metoda'=>'Backtracking', 'dificultate'=>'Usor', 'intrebare'=>'Cand facem pas inapoi in backtracking?', 'optiuni'=>['Cand gasim o solutie completa','Cand o alegere curenta devine invalida','Doar la finalul algoritmului','Dupa fiecare pas'], 'corect'=>1, 'explicatie'=>'Pasul inapoi apare cand starea curenta nu poate duce la o solutie valida.'],
        ['set'=>'backtracking', 'metoda'=>'Backtracking', 'dificultate'=>'Mediu', 'intrebare'=>'Ce face functia de validare in backtracking?', 'optiuni'=>['Calculeaza complexitatea','Verifica daca solutia curenta respecta restrictiile','Sorteaza rezultatele','Afiseaza arborele'], 'corect'=>1, 'explicatie'=>'Validarea filtreaza starile invalide inainte de continuare.'],
        ['set'=>'fundamentali', 'metoda'=>'Greedy', 'dificultate'=>'Mediu', 'intrebare'=>'Strategia greedy alege:', 'optiuni'=>['O solutie aleatoare la fiecare pas','Cea mai buna alegere locala la fiecare pas','Toate combinatiile posibile','Doar ultima varianta'], 'corect'=>1, 'explicatie'=>'Greedy construieste solutia prin alegeri locale optime.'],
        ['set'=>'fundamentali', 'metoda'=>'Divide et Impera', 'dificultate'=>'Mediu', 'intrebare'=>'Care este ordinea corecta in Divide et Impera?', 'optiuni'=>['Combinare → Impartire → Rezolvare','Impartire → Rezolvare subprobleme → Combinare','Rezolvare → Impartire → Combinare','Impartire → Combinare → Rezolvare'], 'corect'=>1, 'explicatie'=>'Intai imparti, apoi rezolvi subprobleme, apoi combini.'],
        ['set'=>'sortari', 'metoda'=>'Bubble Sort', 'dificultate'=>'Usor', 'intrebare'=>'Bubble Sort compara in principal:', 'optiuni'=>['Primul cu ultimul element','Elemente adiacente','Elemente din mijloc','Doar elemente pare'], 'corect'=>1, 'explicatie'=>'Bubble Sort face comparatii intre elemente vecine.'],
        ['set'=>'sortari', 'metoda'=>'Selection Sort', 'dificultate'=>'Mediu', 'intrebare'=>'Selection Sort selecteaza la fiecare pas:', 'optiuni'=>['Elementul maxim din partea sortata','Elementul minim din partea nesortata','Un element random','Pivotul median'], 'corect'=>1, 'explicatie'=>'In varianta crescatoare, alege minimul din zona nesortata.'],
        ['set'=>'sortari', 'metoda'=>'Insertion Sort', 'dificultate'=>'Mediu', 'intrebare'=>'Insertion Sort construieste:', 'optiuni'=>['O zona sortata in stanga','O zona sortata in dreapta','Doar un heap','Doar o lista inlantuita'], 'corect'=>0, 'explicatie'=>'Insertion Sort extinde progresiv segmentul sortat din stanga.'],
        ['set'=>'sortari', 'metoda'=>'Quick Sort', 'dificultate'=>'Mediu', 'intrebare'=>'Quick Sort foloseste in mod esential:', 'optiuni'=>['Un pivot pentru partitionare','Doar numarare frecvente','Doar interclasare in vector auxiliar','Doar comparatii adiacente'], 'corect'=>0, 'explicatie'=>'Cheia in Quick Sort este partitionarea in jurul pivotului.'],
        ['set'=>'sortari', 'metoda'=>'Merge Sort', 'dificultate'=>'Mediu', 'intrebare'=>'Complexitatea tipica pentru Merge Sort este:', 'optiuni'=>['O(n^2)','O(log n)','O(n log n)','O(1)'], 'corect'=>2, 'explicatie'=>'Merge Sort ruleaza in O(n log n) in caz mediu si nefavorabil.'],
        ['set'=>'sortari', 'metoda'=>'Counting Sort', 'dificultate'=>'Mediu', 'intrebare'=>'Cand este eficient Counting Sort?', 'optiuni'=>['Cand valorile sunt intregi si intervalul e mic','Cand datele sunt texte lungi','Cand vectorul e inversat','Cand nu stim nimic despre date'], 'corect'=>0, 'explicatie'=>'Counting Sort e bun cand domeniul valorilor este limitat.'],
        ['set'=>'mix', 'metoda'=>'Mix', 'dificultate'=>'Usor', 'intrebare'=>'Ce este un algoritm?', 'optiuni'=>['O componenta hardware','Un set de pasi finiti pentru rezolvarea unei probleme','Un limbaj de programare','O baza de date'], 'corect'=>1, 'explicatie'=>'Algoritmul este o secventa finita de operatii.'],
    ];

    $set_key = $_GET['set'] ?? 'mix';
    $intrebari_selectate = ($set_key === 'mix') 
        ? $banca_intrebari 
        : array_filter($banca_intrebari, fn($q) => $q['set'] === $set_key);

    if (empty($intrebari_selectate)) {
        $intrebari_selectate = $banca_intrebari;
        $set_key = 'mix';
    }

    shuffle($intrebari_selectate);
    $intrebari_selectate = array_slice($intrebari_selectate, 0, min(8, count($intrebari_selectate)));
    ?>
    <div id="w3-quiz-root" class="bento" style="gap: var(--space-6); min-height: 400px;">
        <!-- Quiz content rendered via JS -->
        <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1); display: flex; align-items: center; justify-content: center; min-height: 300px;">
            <div class="skeleton skeleton--block" style="width: 80%; height: 200px;"></div>
        </article>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const questions = <?php echo json_encode(array_values($intrebari_selectate), JSON_UNESCAPED_UNICODE); ?>;
        const root = document.getElementById('w3-quiz-root');
        let state = { index: 0, score: 0, answered: false };

        function renderQuestion() {
            if (state.index >= questions.length) {
                renderResults();
                return;
            }

            const q = questions[state.index];
            root.innerHTML = `
                <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1);">
                    <div class="card__head">
                        <span class="card__eyebrow" style="color: var(--color-primary);">${q.metoda}</span>
                        <span class="badge badge--soft" style="font-size: 10px;">${q.dificultate}</span>
                        <span class="badge badge--soft" style="margin-left: auto;">${state.index + 1} / ${questions.length}</span>
                    </div>
                    <h2 class="card__title-sm" style="font-size: var(--text-lg); margin: var(--space-4) 0;">${q.intrebare}</h2>
                    
                    <div id="options-container" style="display: flex; flex-direction: column; gap: var(--space-3); margin: var(--space-4) 0;">
                        ${q.optiuni.map((opt, i) => `
                            <label class="grila-option" style="cursor: pointer; display: flex; align-items: center; gap: var(--space-3); padding: var(--space-3); border: 1px solid var(--color-border); border-radius: var(--radius-md); transition: all 0.2s ease;">
                                <input type="radio" name="quiz-opt" value="${i}" style="accent-color: var(--color-primary);">
                                <span style="font-size: var(--text-sm);">${opt}</span>
                            </label>
                        `).join('')}
                    </div>

                    <div id="quiz-feedback" style="display: none; margin-bottom: var(--space-4);"></div>

                    <div class="card__actions">
                        <button id="btn-check" class="btn btn--primary">Verifică răspunsul</button>
                        <button id="btn-next" class="btn btn--ghost" disabled>Următoarea întrebare</button>
                    </div>
                </article>
            `;

            const btnCheck = document.getElementById('btn-check');
            const btnNext = document.getElementById('btn-next');
            const feedback = document.getElementById('quiz-feedback');
            const inputs = document.querySelectorAll('input[name="quiz-opt"]');

            btnCheck.onclick = () => {
                const selected = document.querySelector('input[name="quiz-opt"]:checked');
                if (!selected) {
                    alert('Te rugăm să alegi o variantă!');
                    return;
                }

                state.answered = true;
                const isCorrect = parseInt(selected.value) === q.corect;
                if (isCorrect) state.score++;

                feedback.style.display = 'block';
                feedback.innerHTML = `
                    <div class="alert alert--${isCorrect ? 'success' : 'danger'}" style="margin: 0; padding: var(--space-3); border-radius: var(--radius-md); border: 1px solid currentColor;">
                        <strong>${isCorrect ? 'Corect!' : 'Greșit!'}</strong><br>
                        <p style="font-size: var(--text-xs); margin-top: 4px;">${q.explicatie}</p>
                    </div>
                `;

                inputs.forEach(input => {
                    input.disabled = true;
                    if (parseInt(input.value) === q.corect) {
                        input.parentElement.style.borderColor = 'var(--color-success)';
                        input.parentElement.style.background = 'var(--color-success-soft)';
                    } else if (parseInt(input.value) === parseInt(selected.value)) {
                        input.parentElement.style.borderColor = 'var(--color-danger)';
                        input.parentElement.style.background = 'var(--color-danger-soft)';
                    }
                });

                btnCheck.disabled = true;
                btnNext.disabled = false;
            };

            btnNext.onclick = () => {
                state.index++;
                renderQuestion();
            };
        }

        function renderResults() {
            const percent = Math.round((state.score / questions.length) * 100);
            root.innerHTML = `
                <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1); text-align: center; padding: var(--space-10);">
                    <div class="dash__eyebrow" style="margin: 0 auto var(--space-6);">Test Finalizat</div>
                    <h2 class="dash__title">Scorul tău: <span class="dash__title-accent">${state.score} / ${questions.length}</span></h2>
                    <p class="dash__lede" style="margin: var(--space-4) auto var(--space-8);">Ai răspuns corect la ${percent}% din întrebări.</p>
                    
                    <div class="progress" style="height: 12px; margin-bottom: var(--space-8);">
                        <div class="progress__bar" style="width: ${percent}%;"></div>
                    </div>

                    <div class="card__actions" style="justify-content: center;">
                        <a href="index.php?page=grila_interactiva&mode=w3&set=${new URLSearchParams(window.location.search).get('set') || 'mix'}" class="btn btn--primary">Reia testul</a>
                        <a href="index.php?page=grile" class="btn btn--ghost">Alt test</a>
                    </div>
                </article>
            `;
        }

        renderQuestion();
    });
    </script>
    <?php
    return;
}

if ($id_grila > 0) {
    $stmt = $con->prepare("SELECT * FROM grile_cpp WHERE id = ?");
    $stmt->bind_param("i", $id_grila);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result) $grila = $result->fetch_assoc();
    $stmt->close();

    if ($grila) {
        $raspunsuri = [
            ['id' => 1, 'text' => $grila['varianta_1']],
            ['id' => 2, 'text' => $grila['varianta_2']],
            ['id' => 3, 'text' => $grila['varianta_3']],
            ['id' => 4, 'text' => $grila['varianta_4']],
        ];
        shuffle($raspunsuri);
    }
    
    $stmt_next = $con->prepare("SELECT id FROM grile_cpp WHERE id > ? ORDER BY id ASC LIMIT 1");
    $stmt_next->bind_param("i", $id_grila);
    if ($stmt_next->execute()) {
        $res_next = $stmt_next->get_result();
        $row_next = $res_next->fetch_assoc();
        if ($row_next) { $next_id = intval($row_next['id']); }
    }
    $stmt_next->close();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>
            Evaluare interactivă
        </span>
        <h1 class="dash__title">Grilă <span class="dash__title-accent">C++</span></h1>
        <p class="dash__lede">
            Rezolvă întrebarea trăgând răspunsul corect în zona marcată. Verifică-ți logica și primește feedback instant.
        </p>
        <div class="card__actions">
            <a href="index.php?page=grile" class="btn btn--ghost btn--sm">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                Înapoi la toate grilele
            </a>
            <?php if ($next_id > 0): ?>
                <a href="index.php?page=grila_interactiva&id=<?php echo $next_id; ?>" class="btn btn--primary btn--sm">
                    Următoarea întrebare
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($grila): ?>
        <div class="bento" style="gap: var(--space-6);">
            <!-- QUESTION CARD -->
            <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1);">
                <div class="card__head">
                    <span class="card__eyebrow" style="color: var(--color-primary);"><?php echo htmlspecialchars($grila['nume_metoda']); ?></span>
                    <span class="badge badge--soft" style="font-size: 10px;"><?php echo htmlspecialchars($grila['dificultate']); ?></span>
                </div>
                <h2 class="card__title-sm" style="font-size: var(--text-lg); margin-top: var(--space-2);"><?php echo htmlspecialchars($grila['intrebare']); ?></h2>
                
                <?php if (!empty($grila['cod_exemplu'])): ?>
                    <pre class="lesson-code"><code><?php echo htmlspecialchars($grila['cod_exemplu']); ?></code></pre>
                <?php endif; ?>

                <div id="drop-zone" class="drop-zone">
                    <div id="drop-zone-content">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 32px; height: 32px; margin-bottom: 8px; opacity: 0.5;"><path d="M12 2v20M2 12h20"/><circle cx="12" cy="12" r="10"/></svg>
                        <p>Trage răspunsul corect aici</p>
                    </div>
                </div>
                
                <div id="feedback-panel" style="display: none; margin-top: var(--space-6); padding: var(--space-4); border-radius: var(--radius-lg); animation: slideUp 0.4s var(--ease-out);">
                    <div style="display: flex; gap: var(--space-3); align-items: flex-start;">
                        <div id="feedback-icon" style="flex-shrink: 0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"></div>
                        <div>
                            <h4 id="feedback-title" style="font-size: var(--text-sm); font-weight: 600; margin-bottom: 4px;"></h4>
                            <p id="feedback-text" style="font-size: var(--text-xs); color: var(--color-fg-muted); line-height: 1.5;"></p>
                        </div>
                    </div>
                </div>
            </article>

            <!-- OPTIONS SIDEBAR -->
            <article class="card bento__card--accent" style="border: 1px solid var(--color-border); background: var(--color-surface-2);">
                <div class="card__head">
                    <span class="card__eyebrow">Opțiuni de răspuns</span>
                </div>
                <div id="answers-pool" style="display: flex; flex-direction: column; gap: var(--space-3); margin-top: var(--space-4);">
                    <?php foreach ($raspunsuri as $index => $r): ?>
                        <div 
                            class="grila-option draggable-answer option" 
                            draggable="true" 
                            data-id="<?php echo $r['id']; ?>"
                        >
                            <?php echo htmlspecialchars($r['text']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="margin-top: auto; padding-top: var(--space-6);">
                    <div style="padding: var(--space-3); background: var(--color-surface-3); border-radius: var(--radius-md); font-size: 11px; color: var(--color-fg-subtle); line-height: 1.4;">
                        <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                          <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 18h6" /><path d="M10 22h4" /><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14" />
                          </svg>
                          Sfat: Dacă greșești, poți trage un alt răspuns peste cel existent pentru a reîncerca.
                        </span>
                    </div>
                </div>
            </article>
        </div>
    <?php endif; ?>
</div>

<style>
.draggable-answer:active { cursor: grabbing; opacity: 0.6; }
.drop-zone--over { background: var(--color-primary-soft) !important; border-color: var(--color-primary) !important; transform: scale(1.02); }
.answer--correct { border-color: var(--color-success) !important; background: var(--color-success-soft) !important; }
.answer--wrong { border-color: var(--color-danger) !important; background: var(--color-danger-soft) !important; }

@keyframes slideUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const draggables = document.querySelectorAll('.draggable-answer');
    const dropZone = document.getElementById('drop-zone');
    const feedbackPanel = document.getElementById('feedback-panel');
    
    let currentGrilaId = <?php echo (int)$id_grila; ?>;
    let raspunsCorect = <?php echo (int)($grila['raspuns_corect'] ?? 0); ?>;
    let explicatie = <?php echo json_encode($grila['explicatie'] ?? ''); ?>;

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', () => {
            draggable.classList.add('dragging');
            draggable.style.opacity = '0.4';
        });

        draggable.addEventListener('dragend', () => {
            draggable.classList.remove('dragging');
            draggable.style.opacity = '1';
        });
    });

    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('drop-zone--over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drop-zone--over');
    });

    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drop-zone--over');
        const dragging = document.querySelector('.dragging');
        if (!dragging) return;

        const answerId = parseInt(dragging.getAttribute('data-id'));
        const answerText = dragging.innerText;

        // Update Drop Zone UI
        dropZone.innerHTML = `<div style="font-weight: 600; color: var(--color-fg);">${answerText}</div>`;
        
        const isCorrect = (answerId === raspunsCorect);
        
        // Feedback Panel
        feedbackPanel.style.display = 'block';
        if (isCorrect) {
            dropZone.style.borderColor = 'var(--color-success)';
            dropZone.style.background = 'rgba(16, 185, 129, 0.05)';
            document.getElementById('feedback-icon').innerHTML = '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" style="width:14px; height:14px;"><polyline points="20 6 9 17 4 12"/></svg>';
            document.getElementById('feedback-icon').style.background = 'var(--color-success)';
            document.getElementById('feedback-title').innerText = 'Corect!';
            document.getElementById('feedback-title').style.color = 'var(--color-success)';
            document.getElementById('feedback-text').innerText = explicatie;
            
            // Save progress via AJAX
            fetch('PHP/ajax_progres.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_grila=${currentGrilaId}`
            });
        } else {
            dropZone.style.borderColor = 'var(--color-danger)';
            dropZone.style.background = 'rgba(239, 68, 68, 0.05)';
            document.getElementById('feedback-icon').innerHTML = '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" style="width:14px; height:14px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
            document.getElementById('feedback-icon').style.background = 'var(--color-danger)';
            document.getElementById('feedback-title').innerText = 'Mai încearcă';
            document.getElementById('feedback-title').style.color = 'var(--color-danger)';
            document.getElementById('feedback-text').innerText = 'Răspunsul ales nu este corect. Analizează codul și încearcă o altă variantă.';
        }
    });
});
</script>
