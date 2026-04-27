<?php
include_once 'conexiune.php';
include_once 'auth.php';

$is_logged_in = is_logged_in();
$id_utilizator = $_SESSION['user_id'] ?? 0;
$progres = [];

$teste_rapide = [
    ['titlu' => 'Test Recursivitate', 'descriere' => 'Cazuri de bază, apeluri recursive și complexitate.', 'set' => 'recursivitate'],
    ['titlu' => 'Test Backtracking', 'descriere' => 'Validare, pas înapoi și spațiul soluțiilor.', 'set' => 'backtracking'],
    ['titlu' => 'Test Greedy + D.E.I.', 'descriere' => 'Alegerea locală optimă și subprobleme.', 'set' => 'fundamentali'],
    ['titlu' => 'Test Sortări (mix)', 'descriere' => 'Bubble, Selection, Insertion, Quick, Merge.', 'set' => 'sortari']
];

$sql_grile = "SELECT id, nume_metoda, dificultate, intrebare FROM grile_cpp";
$stmt_grile = $con->prepare($sql_grile);
$stmt_grile->execute();
$result_grile = $stmt_grile->get_result();
$grile = $result_grile->fetch_all(MYSQLI_ASSOC);
$stmt_grile->close();

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
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            Evaluare
        </div>
        <h2 class="dash__title">Grile <span class="dash__title-accent">C++</span></h2>
        <p class="dash__lede">Testează-ți cunoștințele despre algoritmi prin grile interactive și teste rapide.</p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Teste Rapide</h3>
            </div>
            <div class="card__body">
                <p>Teste stil W3Schools, funcționează fără login.</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                    <?php foreach ($teste_rapide as $test): ?>
                        <div class="card card--stat">
                            <span class="stat__label"><?php echo htmlspecialchars($test['titlu']); ?></span>
                            <p style="font-size: 0.8rem; margin: 0.5rem 0; color: var(--color-fg-muted);"><?php echo htmlspecialchars($test['descriere']); ?></p>
                            <a href="index.php?page=grila_interactiva&mode=w3&set=<?php echo urlencode($test['set']); ?>" class="btn btn--ghost btn--sm">Începe</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card bento__card--accent card--ai">
            <div class="ai__icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h3 class="card__title-sm">Progresul Tău</h3>
            <?php if (!$is_logged_in): ?>
                <p class="card__body">Autentifică-te pentru a salva progresul la grilele din baza de date.</p>
                <div class="card__actions">
                    <a href="index.php?page=login" class="btn btn--primary btn--sm">Login</a>
                </div>
            <?php else: ?>
                <div class="stat__value"><?php echo count($progres); ?> / <?php echo count($grile); ?></div>
                <p class="stat__sub">Grile rezolvate cu succes.</p>
            <?php endif; ?>
        </div>

        <div class="card bento__card--timeline">
            <div class="card__head">
                <h3 class="card__title">Grile din Baza de Date</h3>
            </div>
            <div class="card__body">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead style="background: var(--color-surface-2); color: var(--color-fg-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                            <tr>
                                <?php if ($is_logged_in): ?><th style="padding: 1rem; text-align: center;">Status</th><?php endif; ?>
                                <th style="padding: 1rem; text-align: left;">Întrebare</th>
                                <th style="padding: 1rem; text-align: left;">Metodă</th>
                                <th style="padding: 1rem; text-align: left;">Dificultate</th>
                                <th style="padding: 1rem; text-align: right;">Acțiuni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grile as $grila): 
                                $is_completat = $is_logged_in && in_array($grila['id'], $progres);
                            ?>
                                <tr style="border-bottom: 1px solid var(--color-border); transition: background 0.2s;" onmouseover="this.style.background='var(--color-surface-2)'" onmouseout="this.style.background='transparent'">
                                    <?php if ($is_logged_in): ?>
                                    <td style="padding: 1rem; text-align: center;">
                                        <?php if ($is_completat): ?>
                                            <span style="color: var(--color-success);">✔</span>
                                        <?php else: ?>
                                            <span style="color: var(--color-fg-subtle);">☐</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                    <td style="padding: 1rem; color: var(--color-fg);"><?php echo htmlspecialchars($grila['intrebare']); ?></td>
                                    <td style="padding: 1rem;"><span class="badge badge--soft"><?php echo htmlspecialchars($grila['nume_metoda']); ?></span></td>
                                    <td style="padding: 1rem;">
                                        <span class="badge"><?php echo htmlspecialchars($grila['dificultate']); ?></span>
                                    </td>
                                    <td style="padding: 1rem; text-align: right;">
                                        <?php if ($is_logged_in): ?>
                                            <a href="index.php?page=grila_interactiva&id=<?php echo $grila['id']; ?>" class="btn btn--quiet btn--sm">
                                                <?php echo $is_completat ? 'Reia' : 'Rezolvă'; ?>
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?page=login" class="btn btn--ghost btn--sm">Login</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
