<?php
// Include necessary files
include_once 'conexiune.php';
include_once 'auth.php';

$mode = $_GET['mode'] ?? 'db';

if ($mode !== 'w3') {
    require_login();
}

if ($mode === 'w3') {
    $set = $_GET['set'] ?? 'mix';

    $banca_intrebari = [
        [
            'set' => 'recursivitate',
            'metoda' => 'Recursivitate',
            'dificultate' => 'Usor',
            'intrebare' => 'Ce reprezinta cazul de baza intr-un algoritm recursiv?',
            'optiuni' => [
                'Apelul recursiv principal',
                'Conditia care opreste recursia',
                'Vectorul de intrare',
                'Pasul de interschimbare'
            ],
            'corect' => 1,
            'explicatie' => 'Cazul de baza este conditia de oprire care previne recursia infinita.'
        ],
        [
            'set' => 'recursivitate',
            'metoda' => 'Recursivitate',
            'dificultate' => 'Mediu',
            'intrebare' => 'Ce valoare intoarce factorial(0)?',
            'optiuni' => ['0', '1', 'Nu este definit', 'Depinde de compilator'],
            'corect' => 1,
            'explicatie' => 'Prin definitie matematica, 0! = 1.'
        ],
        [
            'set' => 'backtracking',
            'metoda' => 'Backtracking',
            'dificultate' => 'Usor',
            'intrebare' => 'Cand facem pas inapoi in backtracking?',
            'optiuni' => [
                'Cand gasim o solutie completa',
                'Cand o alegere curenta devine invalida',
                'Doar la finalul algoritmului',
                'Dupa fiecare pas, indiferent de situatie'
            ],
            'corect' => 1,
            'explicatie' => 'Pasul inapoi apare cand starea curenta nu mai poate duce la o solutie valida.'
        ],
        [
            'set' => 'backtracking',
            'metoda' => 'Backtracking',
            'dificultate' => 'Mediu',
            'intrebare' => 'Ce face functia de validare in backtracking?',
            'optiuni' => [
                'Calculeaza complexitatea',
                'Verifica daca solutia curenta respecta restrictiile',
                'Sorteaza rezultatele',
                'Afiseaza arborele de cautare'
            ],
            'corect' => 1,
            'explicatie' => 'Validarea filtreaza starile invalide inainte de a continua recursiv.'
        ],
        [
            'set' => 'fundamentali',
            'metoda' => 'Greedy',
            'dificultate' => 'Mediu',
            'intrebare' => 'Strategia greedy alege:',
            'optiuni' => [
                'O solutie aleatoare la fiecare pas',
                'Cea mai buna alegere locala la fiecare pas',
                'Toate combinatiile posibile',
                'Doar ultima varianta disponibila'
            ],
            'corect' => 1,
            'explicatie' => 'Greedy construieste solutia prin alegeri locale optime.'
        ],
        [
            'set' => 'fundamentali',
            'metoda' => 'Divide et Impera',
            'dificultate' => 'Mediu',
            'intrebare' => 'Care este ordinea corecta in Divide et Impera?',
            'optiuni' => [
                'Combinare -> Impartire -> Rezolvare',
                'Impartire -> Rezolvare subprobleme -> Combinare',
                'Rezolvare -> Impartire -> Combinare',
                'Impartire -> Combinare -> Rezolvare'
            ],
            'corect' => 1,
            'explicatie' => 'Mai intai imparti, apoi rezolvi subprobleme, apoi combini rezultatele.'
        ],
        [
            'set' => 'sortari',
            'metoda' => 'Bubble Sort',
            'dificultate' => 'Usor',
            'intrebare' => 'Bubble Sort compara in principal:',
            'optiuni' => [
                'Primul cu ultimul element',
                'Elemente adiacente',
                'Elemente din mijloc',
                'Doar elemente pare'
            ],
            'corect' => 1,
            'explicatie' => 'Bubble Sort face comparatii intre elemente vecine.'
        ],
        [
            'set' => 'sortari',
            'metoda' => 'Selection Sort',
            'dificultate' => 'Mediu',
            'intrebare' => 'Selection Sort selecteaza la fiecare pas:',
            'optiuni' => [
                'Elementul maxim din partea sortata',
                'Elementul minim din partea nesortata',
                'Un element random',
                'Pivotul median'
            ],
            'corect' => 1,
            'explicatie' => 'In varianta crescatoare, alege minimul din zona nesortata si il aduce in fata.'
        ],
        [
            'set' => 'sortari',
            'metoda' => 'Insertion Sort',
            'dificultate' => 'Mediu',
            'intrebare' => 'Insertion Sort construieste:',
            'optiuni' => [
                'O zona sortata in stanga',
                'O zona sortata in dreapta',
                'Doar un heap',
                'Doar o lista inlantuita'
            ],
            'corect' => 0,
            'explicatie' => 'Insertion Sort extinde progresiv segmentul sortat din partea stanga.'
        ],
        [
            'set' => 'sortari',
            'metoda' => 'Quick Sort',
            'dificultate' => 'Mediu',
            'intrebare' => 'Quick Sort foloseste in mod esential:',
            'optiuni' => [
                'Un pivot pentru partitionare',
                'Doar numarare frecvente',
                'Doar interclasare in vector auxiliar',
                'Doar comparatii adiacente'
            ],
            'corect' => 0,
            'explicatie' => 'Cheia in Quick Sort este partitionarea in jurul pivotului.'
        ],
        [
            'set' => 'sortari',
            'metoda' => 'Merge Sort',
            'dificultate' => 'Mediu',
            'intrebare' => 'Complexitatea tipica pentru Merge Sort este:',
            'optiuni' => ['O(n^2)', 'O(log n)', 'O(n log n)', 'O(1)'],
            'corect' => 2,
            'explicatie' => 'Merge Sort ruleaza in O(n log n) in caz mediu si nefavorabil.'
        ],
        [
            'set' => 'sortari',
            'metoda' => 'Counting Sort',
            'dificultate' => 'Mediu',
            'intrebare' => 'Cand este eficient Counting Sort?',
            'optiuni' => [
                'Cand valorile sunt intregi si intervalul este relativ mic',
                'Cand datele sunt texte lungi',
                'Cand vectorul este deja inversat',
                'Cand nu stim nimic despre date'
            ],
            'corect' => 0,
            'explicatie' => 'Counting Sort este bun cand domeniul valorilor este limitat.'
        ]
    ];

    $intrebari_selectate = [];
    foreach ($banca_intrebari as $item) {
        if ($set === 'mix' || $item['set'] === $set) {
            $intrebari_selectate[] = $item;
        }
    }

    if (count($intrebari_selectate) === 0) {
        $intrebari_selectate = $banca_intrebari;
        $set = 'mix';
    }

    shuffle($intrebari_selectate);
    $intrebari_selectate = array_slice($intrebari_selectate, 0, min(8, count($intrebari_selectate)));
    ?>
    <div class="container-grila">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="index.php?page=grile" class="btn btn-ghost">&larr; Inapoi la Grile</a>
            <span class="dificultate-badge dificultate-mediu">Test: <?php echo htmlspecialchars(strtoupper($set)); ?></span>
        </div>

        <div class="card quiz-card" id="w3-quiz-root"></div>
    </div>

    <script>
    (function () {
        var questions = <?php echo json_encode($intrebari_selectate, JSON_UNESCAPED_UNICODE); ?>;
        var index = 0;
        var score = 0;

        function renderQuestion() {
            var root = document.getElementById('w3-quiz-root');
            if (!root) return;

            if (index >= questions.length) {
                var percent = Math.round((score / questions.length) * 100);
                root.innerHTML = '' +
                    '<h3>Test finalizat</h3>' +
                    '<p>Scorul tau: <strong>' + score + ' / ' + questions.length + '</strong> (' + percent + '%)</p>' +
                    '<div style="display:flex; gap:10px; flex-wrap:wrap;">' +
                    '  <a class="btn btn-primary" href="index.php?page=grila_interactiva&mode=w3&set=<?php echo urlencode($set); ?>">Refa testul</a>' +
                    '  <a class="btn btn-ghost" href="index.php?page=grile">Alege alt test</a>' +
                    '</div>';
                return;
            }

            var q = questions[index];
            var optionsHtml = '';
            for (var i = 0; i < q.optiuni.length; i++) {
                optionsHtml +=
                    '<label class="w3-option">' +
                    '  <input type="radio" name="w3-answer" value="' + i + '"> ' + q.optiuni[i] +
                    '</label>';
            }

            root.innerHTML = '' +
                '<div class="card-header">' +
                '  <h3 style="margin:0;">' + q.metoda + '</h3>' +
                '  <span class="dificultate-badge dificultate-' + q.dificultate.toLowerCase() + '">' + q.dificultate + '</span>' +
                '</div>' +
                '<p style="margin-top:12px;color:#666;">Intrebarea ' + (index + 1) + ' din ' + questions.length + '</p>' +
                '<p class="intrebare">' + q.intrebare + '</p>' +
                '<div class="w3-options">' + optionsHtml + '</div>' +
                '<div id="w3-feedback" style="margin:12px 0 0 0;"></div>' +
                '<div style="display:flex; gap:10px; margin-top:14px;">' +
                '  <button id="w3-check" class="btn btn-primary">Verifica</button>' +
                '  <button id="w3-next" class="btn" disabled>Urmatoarea</button>' +
                '</div>';

            document.getElementById('w3-check').addEventListener('click', function () {
                var selected = document.querySelector('input[name="w3-answer"]:checked');
                var feedback = document.getElementById('w3-feedback');
                if (!selected) {
                    feedback.innerHTML = '<p style="color:#dc3545;">Selecteaza o varianta inainte de verificare.</p>';
                    return;
                }

                var value = parseInt(selected.value, 10);
                var correct = value === q.corect;
                if (correct) {
                    score++;
                    feedback.innerHTML = '<p style="color:#28a745;"><strong>Corect.</strong> ' + q.explicatie + '</p>';
                } else {
                    feedback.innerHTML = '<p style="color:#dc3545;"><strong>Gresit.</strong> ' + q.explicatie + '</p>';
                }

                var radios = document.querySelectorAll('input[name="w3-answer"]');
                for (var i = 0; i < radios.length; i++) {
                    radios[i].disabled = true;
                }
                document.getElementById('w3-check').disabled = true;
                document.getElementById('w3-next').disabled = false;
            });

            document.getElementById('w3-next').addEventListener('click', function () {
                index++;
                renderQuestion();
            });
        }

        renderQuestion();
    })();
    </script>

    <style>
        .w3-options {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-top: 12px;
        }

        .w3-option {
            display: block;
            border: 1px solid #dbe3ff;
            border-radius: 10px;
            padding: 10px 12px;
            background: #fff;
            cursor: pointer;
        }

        .w3-option:hover {
            background: #f6f8ff;
        }

        .w3-option input {
            margin-right: 8px;
        }
    </style>
    <?php
    return;
}

$id_grila = $_GET['id'] ?? 0;
$grila = null;
$raspunsuri = [];
$next_id = 0;

if ($id_grila > 0) {
    // Fetch the multiple-choice quiz
    $stmt = $con->prepare("SELECT * FROM grile_cpp WHERE id = ?");
    $stmt->bind_param("i", $id_grila);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result) $grila = $result->fetch_assoc();
    $stmt->close();

    if ($grila) {
        // Prepare answers for display
        $raspunsuri = [
            ['id' => 1, 'text' => $grila['varianta_1']],
            ['id' => 2, 'text' => $grila['varianta_2']],
            ['id' => 3, 'text' => $grila['varianta_3']],
            ['id' => 4, 'text' => $grila['varianta_4']],
        ];
        shuffle($raspunsuri); // Randomize answer order
    }
    
    // Compute next quiz id for navigation
    $stmt_next = $con->prepare("SELECT id FROM grile_cpp WHERE id > ? ORDER BY id ASC LIMIT 1");
    $stmt_next->bind_param("i", $id_grila);
    if ($stmt_next->execute()) {
        $res_next = $stmt_next->get_result();
        $row_next = $res_next->fetch_assoc();
        if ($row_next) { $next_id = intval($row_next['id']); }
    }
    $stmt_next->close();
    
    // If no greater id, wrap to the smallest id
    if ($next_id === 0) {
        $stmt_first = $con->prepare("SELECT id FROM grile_cpp ORDER BY id ASC LIMIT 1");
        if ($stmt_first->execute()) {
            $res_first = $stmt_first->get_result();
            $row_first = $res_first->fetch_assoc();
            if ($row_first) { $next_id = intval($row_first['id']); }
        }
        $stmt_first->close();
    }
}
?>

<div class="container-grila">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="index.php?page=grile" class="btn btn-ghost">← Înapoi la Grile</a>
        <?php if ($next_id > 0): ?>
            <a href="index.php?page=grila_interactiva&id=<?php echo $next_id; ?>" class="btn btn-primary">Următoarea întrebare →</a>
        <?php endif; ?>
    </div>

    <?php if ($grila): ?>
        <div id="quiz-container" class="card quiz-card">
            <div class="card-header">
                <h3><?php echo htmlspecialchars($grila['nume_metoda']); ?></h3>
                <span class="dificultate-badge dificultate-<?php echo strtolower(htmlspecialchars($grila['dificultate'])); ?>"><?php echo htmlspecialchars($grila['dificultate']); ?></span>
            </div>
            <div class="card-body">
                <p class="intrebare"><?php echo htmlspecialchars($grila['intrebare']); ?></p>
                
                <?php if (!empty($grila['cod_exemplu'])): ?>
                    <div class="custom-code-block">
                        <pre><code><?php echo htmlspecialchars($grila['cod_exemplu']); ?></code></pre>
                    </div>
                <?php endif; ?>

                <div id="drop-zone" class="drop-zone mt-4">
                    <p>Trage răspunsul corect aici</p>
                </div>

                <div id="options-container" class="options-container mt-4">
                    <?php foreach ($raspunsuri as $raspuns): ?>
                        <div class="option" draggable="true" data-id="<?php echo $raspuns['id']; ?>">
                            <?php echo htmlspecialchars($raspuns['text']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Feedback Modal -->
        <div id="feedback-modal" class="modal-overlay" style="display:none;">
            <div class="modal-content">
                <h2 id="feedback-titlu"></h2>
                <p id="feedback-explicatie"></p>
                <div style="display:flex; gap:12px; justify-content:center;">
                    <a href="index.php?page=grila_interactiva&id=<?php echo $next_id; ?>" id="next-question" class="btn btn-primary">Următoarea întrebare →</a>
                    <button id="close-modal" class="btn">Închide</button>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-warning">Grila nu a fost găsită.</div>
    <?php endif; ?>
</div>

<script>
const csrfToken = '<?php echo get_csrf_token(); ?>';

document.addEventListener('DOMContentLoaded', () => {
    const options = document.querySelectorAll('.option');
    const dropZone = document.getElementById('drop-zone');
    const grilaId = <?php echo intval($id_grila); ?>;
    const raspunsCorect = <?php echo intval($grila['raspuns_corect'] ?? 0); ?>;
    const explicatie = <?php echo json_encode($grila['explicatie'] ?? ''); ?>;

    let draggedItem = null;

    options.forEach(option => {
        option.addEventListener('dragstart', (e) => {
            draggedItem = e.target;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', e.target.dataset.id);
            e.target.classList.add('dragging');
            dropZone.classList.add('drag-active');
        });

        option.addEventListener('dragend', (e) => {
            e.target.classList.remove('dragging');
            dropZone.classList.remove('drag-active');
        });
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        e.dataTransfer.dropEffect = 'move';
    });
    
    dropZone.addEventListener('dragenter', (e) => {
        if (e.preventDefault) e.preventDefault();
        dropZone.classList.add('hovered');
    });
    
    dropZone.addEventListener('dragleave', (e) => {
        dropZone.classList.remove('hovered');
    });
    
    dropZone.addEventListener('drop', (e) => {
        if (e.preventDefault) e.preventDefault();
        if (e.stopPropagation) e.stopPropagation();
        
        dropZone.classList.remove('hovered');
        dropZone.classList.remove('drag-active');

        if (draggedItem) {
            const selectedId = parseInt(draggedItem.dataset.id, 10);
            draggedItem.classList.remove('dragging');
            verifyAnswer(selectedId);
        }
        
        return false;
    });

    function verifyAnswer(selectedId) {
        const isCorrect = selectedId === raspunsCorect;
        
        if (isCorrect) {
            document.getElementById('feedback-titlu').innerText = 'Corect!';
            document.getElementById('feedback-titlu').style.color = '#28a745';
            document.getElementById('feedback-explicatie').innerHTML = explicatie;
            
            // Mark as complete on server
            fetch('php/ajax_progres.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({ id_grila: grilaId })
            }).catch(err => console.error('Eroare salvare progres:', err));

        } else {
            document.getElementById('feedback-titlu').innerText = 'Greșit!';
            document.getElementById('feedback-titlu').style.color = '#dc3545';
            document.getElementById('feedback-explicatie').innerHTML = 'Răspunsul nu este corect. Mai încearcă! <br><br>' + explicatie;
        }

        document.getElementById('feedback-modal').style.display = 'flex';
    }

    document.getElementById('close-modal').addEventListener('click', () => {
        document.getElementById('feedback-modal').style.display = 'none';
        // Optional: refresh or go back
        // window.location.href = 'index.php?page=grile';
    });
});
</script>

<style>
    .container-grila { 
        padding: 20px;
    }
    
    .quiz-card { 
        background: linear-gradient(135deg, #ffffff, #f8f9ff);
        border-radius: 16px;
        padding: 32px;
        max-width: 900px;
        margin: auto;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        border: 1px solid rgba(102,126,234,0.1);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid rgba(102,126,234,0.2);
    }
    
    .card-header h3 {
        margin: 0;
        color: #111827;
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .dificultate-badge {
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .dificultate-usor {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.15));
        color: #065f46;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .dificultate-mediu {
        background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(251, 191, 36, 0.15));
        color: #92400e;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }
    
    .dificultate-greu {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.15));
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .intrebare { 
        font-size: 1.15em;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1f2937;
        line-height: 1.6;
    }
    
    .custom-code-block {
        background: #ffffff;
        border-radius: 12px;
        margin: 20px 0;
        padding: 0;
        border: 1px solid rgba(102,126,234,0.2);
        overflow: hidden;
    }
    
    .custom-code-block pre {
        margin: 0;
        padding: 20px;
        background: #ffffff;
        overflow-x: auto;
    }
    
    .custom-code-block code {
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.9rem;
        line-height: 1.6;
        color: #1f2937;
    }

    .drop-zone {
        border: 3px dashed rgba(102,126,234,0.5);
        border-radius: 16px;
        padding: 60px 40px;
        text-align: center;
        color: #667eea;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, #f8f9ff, #ffffff);
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin: 24px 0;
    }
    
    .drop-zone.hovered,
    .drop-zone.drag-active {
        background: linear-gradient(135deg, rgba(102,126,234,0.15), rgba(118,75,162,0.15));
        border-color: #667eea;
        border-width: 4px;
        transform: scale(1.03);
        box-shadow: 0 12px 32px rgba(102,126,234,0.3);
        color: #5568d3;
    }
    
    .option.dragging {
        opacity: 0.4;
        cursor: grabbing;
    }

    .options-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
    }
    
    .option {
        padding: 18px 20px;
        background: linear-gradient(135deg, #ffffff, #f8f9ff);
        border: 2px solid rgba(102,126,234,0.15);
        border-radius: 12px;
        cursor: move;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        font-weight: 500;
        color: #374151;
    }
    
    .option:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(102,126,234,0.2);
        border-color: rgba(102,126,234,0.4);
        background: linear-gradient(135deg, #ffffff, #f0f2ff);
    }

    /* Modal styles */
    .modal-overlay { 
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    
    .modal-content { 
        background: linear-gradient(135deg, #ffffff, #f8f9ff);
        padding: 40px;
        border-radius: 20px;
        text-align: center;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        border: 1px solid rgba(102,126,234,0.2);
    }
    
    .modal-content h2 {
        margin-top: 0;
        margin-bottom: 16px;
        font-size: 1.8rem;
        font-weight: 700;
    }
    
    .modal-content p {
        line-height: 1.7;
        color: #4b5563;
        margin-bottom: 24px;
    }
    
    @media (max-width: 640px) {
        .quiz-card {
            padding: 20px;
        }
        
        .card-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
        
        .options-container {
            grid-template-columns: 1fr;
        }
        
        .modal-content {
            padding: 24px;
            margin: 20px;
        }
    }
    
    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .quiz-card {
            background: linear-gradient(135deg, #1f2937, #111827);
            border-color: rgba(129,140,248,0.2);
        }
        
        .card-header {
            border-bottom-color: rgba(129,140,248,0.2);
        }
        
        .card-header h3 {
            color: #f9fafb;
        }
        
        .intrebare {
            color: #e5e7eb;
        }
        
        .custom-code-block {
            background: #1f2937;
            border-color: rgba(129,140,248,0.3);
        }
        
        .custom-code-block pre {
            background: #1f2937;
        }
        
        .custom-code-block code {
            color: #e5e7eb;
        }
        
        .drop-zone {
            background: linear-gradient(135deg, #111827, #1f2937);
            border-color: rgba(129,140,248,0.3);
            color: #9ca3af;
        }
        
        .drop-zone.hovered {
            background: linear-gradient(135deg, rgba(129,140,248,0.15), rgba(192,132,252,0.15));
            border-color: #818cf8;
        }
        
        .option {
            background: linear-gradient(135deg, #374151, #1f2937);
            border-color: rgba(129,140,248,0.2);
            color: #d1d5db;
        }
        
        .option:hover {
            background: linear-gradient(135deg, #4b5563, #374151);
            border-color: rgba(129,140,248,0.4);
        }
        
        .modal-content {
            background: linear-gradient(135deg, #1f2937, #111827);
            border-color: rgba(129,140,248,0.3);
        }
        
        .modal-content p {
            color: #9ca3af;
        }
    }
</style>
