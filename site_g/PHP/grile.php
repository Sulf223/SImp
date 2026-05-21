<?php
require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/auth.php';

$is_logged_in = is_logged_in();
$id_utilizator = $_SESSION['user_id'] ?? 0;
$progres = [];

$teste_rapide = [
    ['titlu' => 'Recursivitate', 'descriere' => 'Cazuri de bază, apeluri recursive și stivă.', 'set' => 'recursivitate', 'color' => '#f97316'],
    ['titlu' => 'Backtracking', 'descriere' => 'Validare, pas înapoi și spațiul soluțiilor.', 'set' => 'backtracking', 'color' => '#6366f1'],
    ['titlu' => 'Greedy + D.E.I.', 'descriere' => 'Alegerea locală optimă și subprobleme.', 'set' => 'fundamentali', 'color' => '#10b981'],
    ['titlu' => 'Sortări (mix)', 'descriere' => 'Bubble, Selection, Insertion, Quick, Merge.', 'set' => 'sortari', 'color' => '#6e56cf']
];

$sql_grile = "SELECT id, nume_metoda, dificultate, intrebare, doc_link FROM grile_cpp";
$stmt_grile = $con->prepare($sql_grile);
$grile = [];
if ($stmt_grile && $stmt_grile->execute()) {
    $result_grile = $stmt_grile->get_result();
    $grile = $result_grile ? $result_grile->fetch_all(MYSQLI_ASSOC) : [];
    $stmt_grile->close();
} else {
    error_log('grile.php: failed to load grile_cpp: ' . $con->error);
}

if ($is_logged_in) {
    $sql_progres = "SELECT id_grila FROM progres_grile WHERE id_utilizator = ?";
    $stmt_progres = $con->prepare($sql_progres);
    $stmt_progres->bind_param("i", $id_utilizator);
    $stmt_progres->execute();
    $result_progres = $stmt_progres->get_result();
    $progres = array_column($result_progres->fetch_all(MYSQLI_ASSOC), 'id_grila');
    $stmt_progres->close();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>
            Evaluare
        </span>
        <h1 class="dash__title">Grile <span class="dash__title-accent">C++</span></h1>
        <p class="dash__lede">Testează-ți cunoștințele prin grile interactive și teste rapide. Monitorizează-ți progresul pe măsură ce avansezi în învățare.</p>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- QUICK TESTS: Hero area -->
        <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/>
                    </svg>
                    Teste Rapide (Fără Cont)
                </span>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--space-4); margin-top: var(--space-2);">
                <?php foreach ($teste_rapide as $test): ?>
                    <div class="card card--stat" style="border: 1px solid color-mix(in srgb, <?php echo $test['color']; ?> 30%, transparent); background: color-mix(in srgb, <?php echo $test['color']; ?> 5%, var(--color-surface-2));">
                        <span class="stat__label" style="color: <?php echo $test['color']; ?>;"><?php echo htmlspecialchars($test['titlu']); ?></span>
                        <p style="font-size: var(--text-xs); margin: var(--space-2) 0; color: var(--color-fg-muted); line-height: 1.5;"><?php echo htmlspecialchars($test['descriere']); ?></p>
                        <div class="card__actions">
                            <a href="index.php?page=grila_interactiva&mode=w3&set=<?php echo urlencode($test['set']); ?>" class="btn btn--ghost btn--sm" style="border-color: <?php echo $test['color']; ?>; color: <?php echo $test['color']; ?>;">Începe testul</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>

        <!-- PROGRESS: Sidebar -->
        <article class="card bento__card--accent" style="border: 1px solid var(--color-primary-soft); background: linear-gradient(135deg, var(--color-primary-soft) 0%, transparent 100%);">
            <div class="card__head">
                <span class="card__eyebrow" style="color: var(--color-primary);">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 20v-6M6 20V10M18 20V4"/>
                    </svg>
                    Statistici Progres
                </span>
            </div>
            <?php if (!$is_logged_in): ?>
                <p class="card__body">Autentifică-te pentru a salva progresul și a vedea scorurile obținute la testele oficiale.</p>
                <div class="card__actions" style="margin-top: var(--space-4);">
                    <a href="index.php?page=login" class="btn btn--primary btn--sm">Autentificare</a>
                </div>
            <?php else: ?>
                <div style="margin-top: var(--space-4);">
                    <div class="stat__value"><?php echo count($progres); ?> / <?php echo count($grile); ?></div>
                    <p class="stat__sub">Întrebări rezolvate corect</p>
                    
                    <?php 
                        $procent = count($grile) > 0 ? (count($progres) / count($grile)) * 100 : 0;
                    ?>
                    <div style="height: 6px; background: var(--color-surface-3); border-radius: 3px; margin-top: var(--space-4); overflow: hidden;">
                        <div style="height: 100%; width: <?php echo $procent; ?>%; background: var(--color-primary); box-shadow: 0 0 10px var(--color-primary-glow);"></div>
                    </div>
                </div>
            <?php endif; ?>
        </article>

        <!-- DATABASE GRILES: Full width -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M6.5 18H20"/>
                    </svg>
                    Bancă de Întrebări (Database)
                </span>
            </div>
            
            <div class="table-wrapper" style="overflow-x: auto; margin-top: var(--space-4);">
                <?php if (empty($grile)): ?>
                    <!-- POLISH [P3]: Empty state -->
                    <div class="empty-state" style="text-align:center; padding: var(--space-12) var(--space-4);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--color-fg-muted)" stroke-width="2" width="48" height="48" style="margin-bottom: var(--space-4);"><path d="M12 20h.01"/><path d="M12 16h.01"/><path d="M12 12h.01"/><path d="M12 8h.01"/><path d="M12 4h.01"/><path d="M8 20h.01"/><path d="M8 16h.01"/><path d="M8 12h.01"/><path d="M8 8h.01"/><path d="M8 4h.01"/><path d="M16 20h.01"/><path d="M16 16h.01"/><path d="M16 12h.01"/><path d="M16 8h.01"/><path d="M16 4h.01"/></svg>
                        <h3 style="margin-top: var(--space-3); color: var(--color-fg-muted);">Nu există încă grile în bază</h3>
                        <p style="color: var(--color-fg-subtle); font-size: var(--text-sm);">Grilele vor apărea aici odată ce sunt adăugate de către administratori.</p>
                    </div>
                <?php else: ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-border);">
                            <?php if ($is_logged_in): ?><th style="padding: var(--space-3); text-align: center; color: var(--color-fg-subtle); font-size: 10px; text-transform: uppercase;">Status</th><?php endif; ?>
                            <th style="padding: var(--space-3); text-align: left; color: var(--color-fg-subtle); font-size: 10px; text-transform: uppercase;">Întrebare</th>
                            <th style="padding: var(--space-3); text-align: left; color: var(--color-fg-subtle); font-size: 10px; text-transform: uppercase;">Algoritm</th>
                            <th style="padding: var(--space-3); text-align: left; color: var(--color-fg-subtle); font-size: 10px; text-transform: uppercase;">Dificultate</th>
                            <th style="padding: var(--space-3); text-align: left; color: var(--color-fg-subtle); font-size: 10px; text-transform: uppercase;">Documentație</th>
                            <th style="padding: var(--space-3); text-align: right; color: var(--color-fg-subtle); font-size: 10px; text-transform: uppercase;">Acțiune</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grile as $grila): 
                            $is_completat = $is_logged_in && in_array($grila['id'], $progres);
                        ?>
                            <tr style="border-bottom: 1px solid var(--color-border); transition: background 0.2s;">
                                <?php if ($is_logged_in): ?>
                                <td style="padding: var(--space-4); text-align: center;">
                                    <?php if ($is_completat): ?>
                                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    <?php else: ?>
                                        <div style="width: 12px; height: 12px; border: 1.5px solid var(--color-border-strong); border-radius: 3px; margin: auto;"></div>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                                <td style="padding: var(--space-4); color: var(--color-fg); font-size: var(--text-sm); max-width: 400px;"><?php echo htmlspecialchars($grila['intrebare']); ?></td>
                                <td style="padding: var(--space-4);"><span class="badge badge--soft"><?php echo htmlspecialchars($grila['nume_metoda']); ?></span></td>
                                <td style="padding: var(--space-4);">
                                    <?php 
                                        $dif = $grila['dificultate'] ?? 'Usor';
                                        $dif_label = $dif;
                                        if ($dif === 'Usor') {
                                            $dif_style = 'background: var(--color-success-soft); color: var(--color-success);';
                                        } elseif ($dif === 'Mediu') {
                                            $dif_style = 'background: var(--color-warning-soft); color: var(--color-warning);';
                                        } else {
                                            $dif_style = 'background: var(--color-danger-soft); color: var(--color-danger);';
                                        }
                                    ?>
                                    <span class="badge" style="font-size: 10px; <?php echo $dif_style; ?>">
                                        <?php echo htmlspecialchars($dif_label); ?>
                                    </span>
                                </td>
                                <td style="padding: var(--space-4); text-align: left;">
                                    <?php if (!empty($grila['doc_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($grila['doc_link']); ?>" target="_blank" class="btn btn--quiet btn--sm">Vezi doc</a>
                                    <?php else: ?>
                                        <span style="color: var(--color-fg-muted); font-size: 12px;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: var(--space-4); text-align: right;">
                                    <?php if ($is_logged_in): ?>
                                        <a href="index.php?page=grila_interactiva&id=<?php echo (int)$grila['id']; ?>" class="btn btn--quiet btn--sm" style="color: var(--color-primary); pointer-events: auto !important;">
                                            <?php echo $is_completat ? 'Reia' : 'Rezolvă'; ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="index.php?page=login" class="btn btn--ghost btn--sm" style="pointer-events: auto !important;">Login</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </article>
    </div>
</div>
