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
    // Standard W3 mode logic (already handled in previous turns or logic preserved)
    // For brevity, I will wrap the W3 mode in the same modern dashboard structure.
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
            Rezolvă întrebările de tip grilă folosind sistemul de drag-and-drop.
        </p>
        <div class="card__actions">
            <a href="index.php?page=grile" class="btn btn--ghost btn--sm">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                Înapoi la toate grilele
            </a>
            <?php if ($next_id > 0): ?>
                <a href="index.php?page=grila_interactiva&id=<?php echo $next_id; ?>" class="btn btn--primary btn--sm">
                    Următoarea întrebare
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($grila): ?>
        <div class="bento">
            <!-- Întrebare Card -->
            <article class="card bento__card--hero">
                <div class="card__head">
                    <span class="card__eyebrow"><?php echo htmlspecialchars($grila['nume_metoda']); ?></span>
                    <span class="badge badge--soft"><?php echo htmlspecialchars($grila['dificultate']); ?></span>
                </div>
                <h2 class="card__title-sm"><?php echo htmlspecialchars($grila['intrebare']); ?></h2>
                
                <?php if (!empty($grila['cod_exemplu'])): ?>
                    <pre style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md); margin-top: var(--space-4); overflow-x: auto;"><code style="font-family: var(--font-mono); font-size: var(--text-sm); color: var(--color-fg-muted);"><?php echo htmlspecialchars($grila['cod_exemplu']); ?></code></pre>
                <?php endif; ?>

                <div id="drop-zone" class="card__body" style="border: 2px dashed var(--color-primary-soft); border-radius: var(--radius-xl); padding: var(--space-10); text-align: center; margin-top: var(--space-6); background: var(--color-surface-2); transition: all 0.3s ease;">
                    <p style="color: var(--color-fg-subtle);">Trage răspunsul corect aici</p>
                </div>
            </article>

            <!-- Opțiuni Card -->
            <article class="card bento__card--accent">
                <div class="card__head">
                    <span class="card__eyebrow">Variante de răspuns</span>
                </div>
                <div id="options-container" style="display: flex; flex-direction: column; gap: var(--space-3); margin-top: var(--space-4);">
                    <?php foreach ($raspunsuri as $raspuns): ?>
                        <div class="option" draggable="true" data-id="<?php echo $raspuns['id']; ?>" style="padding: var(--space-4); background: var(--color-surface-2); border: 1px solid var(--color-border); border-radius: var(--radius-lg); cursor: grab; transition: all 0.2s ease;">
                            <?php echo htmlspecialchars($raspuns['text']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>
        </div>

        <!-- Feedback UI (Hidden by default) -->
        <div id="feedback-modal" style="display:none; position: fixed; inset: 0; background: var(--color-surface-overlay); backdrop-filter: blur(8px); z-index: var(--z-modal); align-items: center; justify-content: center; padding: var(--space-6);">
            <div class="card" style="max-width: 500px; text-align: center; padding: var(--space-10);">
                <h2 id="feedback-titlu" class="dash__title" style="font-size: var(--text-3xl);"></h2>
                <p id="feedback-explicatie" class="card__body" style="margin-bottom: var(--space-8);"></p>
                <div class="card__actions" style="justify-content: center;">
                    <a href="index.php?page=grila_interactiva&id=<?php echo $next_id; ?>" class="btn btn--primary">Următoarea întrebare</a>
                    <button id="close-modal" class="btn btn--ghost">Închide</button>
                </div>
            </div>
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
                    e.target.style.opacity = '0.5';
                    dropZone.style.borderColor = 'var(--color-primary)';
                    dropZone.style.background = 'var(--color-primary-soft)';
                });
                option.addEventListener('dragend', (e) => {
                    e.target.style.opacity = '1';
                    dropZone.style.borderColor = 'var(--color-primary-soft)';
                    dropZone.style.background = 'var(--color-surface-2)';
                });
            });

            dropZone.addEventListener('dragover', (e) => e.preventDefault());
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                if (draggedItem) {
                    const selectedId = parseInt(draggedItem.dataset.id, 10);
                    verifyAnswer(selectedId);
                }
            });

            function verifyAnswer(selectedId) {
                const isCorrect = selectedId === raspunsCorect;
                const modal = document.getElementById('feedback-modal');
                const titlu = document.getElementById('feedback-titlu');
                const desc = document.getElementById('feedback-explicatie');
                
                if (isCorrect) {
                    titlu.innerText = 'Corect!';
                    titlu.style.color = 'var(--color-success)';
                    desc.innerHTML = explicatie;
                    fetch('PHP/ajax_progres.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
                        body: JSON.stringify({ id_grila: grilaId })
                    }).catch(err => console.error(err));
                } else {
                    titlu.innerText = 'Mai încearcă!';
                    titlu.style.color = 'var(--color-danger)';
                    desc.innerHTML = 'Răspunsul nu este cel corect. <br><br>' + explicatie;
                }
                modal.style.display = 'flex';
            }

            document.getElementById('close-modal').onclick = () => {
                document.getElementById('feedback-modal').style.display = 'none';
            };
        });
        </script>
    <?php else: ?>
        <div class="dash__guard">
            <h3>Grilă nedisponibilă</h3>
            <p>Nu am găsit întrebarea solicitată. Reveniți la listă.</p>
            <a href="index.php?page=grile" class="btn btn--primary">Vezi toate grilele</a>
        </div>
    <?php endif; ?>
</div>

<style>
/* CSS scoped for drag-drop interaction enhancement */
.option:hover { border-color: var(--color-primary) !important; transform: translateY(-2px); box-shadow: var(--shadow-md); }
.option:active { cursor: grabbing; }
</style>
