<?php
// pagini/invatare.php - Ghiduri de învățare structurate
require_once 'PHP/conexiune.php';
require_once 'PHP/auth.php';
require_once 'PHP/progres_learning.php';

$is_logged_in = is_logged_in();
$id_utilizator = $_SESSION['user_id'] ?? 0;

// FIX [C1]: Optimizare interogări (eliminare N+1) și prevenire SQL Injection prin preluare bulk
$paths = []; 
$by_id = [];
$rs = $con->query("
    SELECT *
    FROM learning_paths
    ORDER BY
        CASE slug
            WHEN 'parcurs-recomandat' THEN 1
            WHEN 'algoritmi-fundamentali' THEN 2
            WHEN 'sorting-basics' THEN 3
            WHEN 'tehnici-algoritmice' THEN 4
            ELSE 99
        END,
        id ASC
");
if ($rs) {
    while ($r = $rs->fetch_assoc()) {
        $r['steps'] = [];
        $by_id[(int)$r['id']] = count($paths);
        $paths[] = $r;
    }
}
$rs = $con->query("SELECT * FROM learning_path_steps ORDER BY path_id, step_order");
if ($rs) {
    while ($s = $rs->fetch_assoc()) {
        $pid = (int)$s['path_id'];
        if (isset($by_id[$pid])) {
            $paths[$by_id[$pid]]['steps'][] = $s;
        }
    }
}

function invatare_e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Learning Paths
        </span>
        <h1 class="dash__title">Drumuri de <span class="dash__title-accent">Învățare</span></h1>
        <p class="dash__lede">Alege un parcurs structurat pentru a stăpâni conceptele de programare, pas cu pas.</p>
    </header>

    <div class="bento learning-paths-grid" style="gap: var(--space-6);">
        <?php foreach ($paths as $path): 
            $pathSlug = (string)$path['slug'];
            $is_featured = $pathSlug === 'parcurs-recomandat';
        ?>
            <article class="card learning-path-card <?php echo $is_featured ? 'learning-path-card--featured' : ''; ?>" style="border: 1px solid var(--color-border); background: var(--color-surface-1);">
                <div class="card__head">
                    <span class="card__eyebrow" style="color: var(--color-primary);"><?php echo invatare_e($path['title']); ?></span>
                    <?php if ($is_featured): ?>
                        <span class="badge badge--soft">Recomandat</span>
                    <?php endif; ?>
                </div>
                <p style="color: var(--color-fg-muted); margin-bottom: var(--space-6);"><?php echo invatare_e($path['description']); ?></p>
                
                <div class="path-timeline" style="position: relative; padding-left: var(--space-8);">
                    <div style="position: absolute; left: 11px; top: 0; bottom: 0; width: 2px; background: var(--color-border-strong);"></div>
                    
                    <?php foreach ($path['steps'] as $index => $step): 
                        $is_quiz = ($step['lesson_slug'] === 'final_quiz');
                        $lesson_slug = (string)$step['lesson_slug'];
                        $step_link = 'index.php?page=' . rawurlencode($lesson_slug);
                        $button_label = 'Deschide etapa';
                        if ($lesson_slug === 'profesor_ai') {
                            $step_link = 'index.php?page=profesor_ai&path_exam=' . rawurlencode($pathSlug);
                            $button_label = 'Antrenează-te cu AI';
                        }
                        if ($is_quiz) {
                            $step_link = 'index.php?page=profesor_ai&path_exam=' . rawurlencode($pathSlug);
                            $button_label = 'Test AI';
                        }
                    ?>
                        <div class="step-item" style="position: relative; margin-bottom: var(--space-6);">
                            <div style="position: absolute; left: -26px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: <?php echo $is_quiz ? 'var(--color-accent)' : 'var(--color-primary)'; ?>; border: 3px solid var(--color-surface-1); box-shadow: 0 0 0 1px var(--color-border-strong);"></div>
                            <h4 style="font-size: var(--text-sm); font-weight: 600; margin-bottom: var(--space-1);"><?php echo invatare_e($step['title']); ?></h4>
                            <div class="card__actions" style="margin-top: var(--space-2);">
                                <a href="<?php echo invatare_e($step_link); ?>" class="btn <?php echo $is_quiz ? 'btn--primary' : 'btn--quiet'; ?> btn--sm"><?php echo invatare_e($button_label); ?></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>
        <?php endforeach; ?>
        
        <!-- SIDEBAR INFO -->
        <article class="card learning-help-card" style="border: 1px solid var(--color-accent-soft); background: var(--color-surface-2);">
            <div class="card__head">
                <span class="card__eyebrow" style="color: var(--color-accent);">Cum funcționează?</span>
            </div>
            <div class="prose" style="font-size: var(--text-sm);">
                <p>Parcursurile sunt ordonate ca să începi cu baza, apoi să treci la sortări, tehnici algoritmice și verificare prin AI.</p>
                <ol style="padding-left: var(--space-4); margin-top: var(--space-4); display: flex; flex-direction: column; gap: var(--space-3);">
                    <li>Începi cu fișele teoretice și exemplele C++.</li>
                    <li>Testezi pașii în laboratorul vizual sau în compilator.</li>
                    <li>Rezolvi grile pentru fixare.</li>
                    <li><strong>Testul AI:</strong> primești întrebări generate pe tema parcursului ales.</li>
                </ol>
            </div>
        </article>
    </div>
</div>
