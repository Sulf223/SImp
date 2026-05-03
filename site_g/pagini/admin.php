<?php
// pagini/admin.php — Panou de control admin
// 4 secțiuni: Dashboard global, Listă utilizatori, Detalii user, Acțiuni admin
require_once __DIR__ . '/../PHP/auth.php';
require_once __DIR__ . '/../PHP/conexiune.php';

if (!is_admin()) {
    set_flash("error", "Acces interzis. Doar administratorii pot accesa această pagină.");
    header("Location: index.php?page=acasa");
    exit;
}

// --- Tab activ
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
$tab_valide = ['dashboard', 'utilizatori', 'detalii', 'actiuni', 'audit'];
if (!in_array($tab, $tab_valide, true)) { $tab = 'dashboard'; }

// --- DATE GLOBALE pentru dashboard ---
$kpi = [
    'total_users' => 0,
    'total_admini' => 0,
    'inregistrati_7d' => 0,
    'activi_7d' => 0,
    'grile_total' => 0,
    'grile_completate_7d' => 0,
    'exercitii_completate' => 0,
    'metode_total' => 0,
];
$top_users = [];
$top_metode = [];
$activitate_7d = [];

if ($tab === 'dashboard') {
    $r = $con->query("SELECT COUNT(*) c FROM utilizatori");
    if ($r) { $kpi['total_users'] = (int)$r->fetch_assoc()['c']; }

    $r = $con->query("SELECT COUNT(*) c FROM utilizatori WHERE rol = 'admin'");
    if ($r) { $kpi['total_admini'] = (int)$r->fetch_assoc()['c']; }

    $r = $con->query("SELECT COUNT(*) c FROM utilizatori WHERE created_at > NOW() - INTERVAL 7 DAY");
    if ($r) { $kpi['inregistrati_7d'] = (int)$r->fetch_assoc()['c']; }

    $r = $con->query("SELECT COUNT(DISTINCT user_id) c FROM activity_day WHERE activity_date > CURDATE() - INTERVAL 7 DAY");
    if ($r) { $kpi['activi_7d'] = (int)$r->fetch_assoc()['c']; }

    $r = $con->query("SELECT COUNT(*) c FROM grile_cpp");
    if ($r) { $kpi['grile_total'] = (int)$r->fetch_assoc()['c']; }

    $r = $con->query("SELECT COUNT(*) c FROM progres_grile WHERE data_completare > NOW() - INTERVAL 7 DAY");
    if ($r) { $kpi['grile_completate_7d'] = (int)$r->fetch_assoc()['c']; }

    $r = $con->query("SELECT COUNT(*) c FROM learning_exercise_progress");
    if ($r) { $kpi['exercitii_completate'] = (int)$r->fetch_assoc()['c']; }

    $r = $con->query("SELECT COUNT(*) c FROM metode");
    if ($r) { $kpi['metode_total'] = (int)$r->fetch_assoc()['c']; }

    // Top 10 useri după grile rezolvate + exerciții
    $sql = "SELECT u.id, u.username, u.rol,
                   (SELECT COUNT(*) FROM progres_grile pg WHERE pg.id_utilizator = u.id) AS grile,
                   (SELECT COUNT(*) FROM learning_exercise_progress lep WHERE lep.user_id = u.id) AS exercitii,
                   (SELECT current_streak FROM user_streak us WHERE us.user_id = u.id) AS streak
            FROM utilizatori u
            ORDER BY (grile + exercitii) DESC
            LIMIT 10";
    $r = $con->query($sql);
    if ($r) { while ($row = $r->fetch_assoc()) { $top_users[] = $row; } }

    // Top metode după grile rezolvate (pe baza nume_metoda din grile_cpp)
    $sql = "SELECT g.nume_metoda, COUNT(pg.id) AS rezolvate
            FROM grile_cpp g
            LEFT JOIN progres_grile pg ON pg.id_grila = g.id
            GROUP BY g.nume_metoda
            ORDER BY rezolvate DESC
            LIMIT 6";
    $r = $con->query($sql);
    if ($r) { while ($row = $r->fetch_assoc()) { $top_metode[] = $row; } }

    // Activitate ultimele 7 zile (sumă activity_count)
    $sql = "SELECT activity_date, SUM(activity_count) total
            FROM activity_day
            WHERE activity_date > CURDATE() - INTERVAL 7 DAY
            GROUP BY activity_date
            ORDER BY activity_date ASC";
    $r = $con->query($sql);
    if ($r) { while ($row = $r->fetch_assoc()) { $activitate_7d[] = $row; } }
}

// --- DATE pentru tab UTILIZATORI ---
$users_list = [];
$search = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
if ($tab === 'utilizatori') {
    $sql = "SELECT u.id, u.username, u.rol, u.created_at,
                   (SELECT COUNT(*) FROM progres_grile pg WHERE pg.id_utilizator = u.id) AS grile,
                   (SELECT COUNT(*) FROM learning_exercise_progress lep WHERE lep.user_id = u.id) AS exercitii,
                   (SELECT current_streak FROM user_streak us WHERE us.user_id = u.id) AS streak,
                   (SELECT MAX(accessed_at) FROM learning_activity_history h WHERE h.user_id = u.id) AS ultima_activitate
            FROM utilizatori u
            WHERE (? = '' OR u.username LIKE ?)
            ORDER BY u.created_at DESC";
    $like = '%' . $search . '%';
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("ss", $search, $like);
        $stmt->execute();
        $rs = $stmt->get_result();
        while ($row = $rs->fetch_assoc()) { $users_list[] = $row; }
        $stmt->close();
    }
}

// --- DATE pentru tab DETALII (drill-down) ---
$user_detail = null;
$user_grile = [];
$user_exercitii = [];
$user_lesson_progress = [];
$user_activity = [];
$user_streak = null;
$user_id_drill = isset($_GET['user']) ? (int)$_GET['user'] : 0;

if ($tab === 'detalii' && $user_id_drill > 0) {
    if ($stmt = $con->prepare("SELECT id, username, rol, created_at FROM utilizatori WHERE id = ?")) {
        $stmt->bind_param("i", $user_id_drill);
        $stmt->execute();
        $rs = $stmt->get_result();
        $user_detail = $rs->fetch_assoc();
        $stmt->close();
    }

    if ($user_detail) {
        // Grile rezolvate
        if ($stmt = $con->prepare(
            "SELECT g.id, g.nume_metoda, g.dificultate, g.intrebare, pg.data_completare
             FROM progres_grile pg
             JOIN grile_cpp g ON g.id = pg.id_grila
             WHERE pg.id_utilizator = ?
             ORDER BY pg.data_completare DESC")) {
            $stmt->bind_param("i", $user_id_drill);
            $stmt->execute();
            $rs = $stmt->get_result();
            while ($row = $rs->fetch_assoc()) { $user_grile[] = $row; }
            $stmt->close();
        }

        // Exerciții completate
        if ($stmt = $con->prepare(
            "SELECT lesson_slug, exercise_key, completed_at
             FROM learning_exercise_progress
             WHERE user_id = ?
             ORDER BY completed_at DESC")) {
            $stmt->bind_param("i", $user_id_drill);
            $stmt->execute();
            $rs = $stmt->get_result();
            while ($row = $rs->fetch_assoc()) { $user_exercitii[] = $row; }
            $stmt->close();
        }

        // Lecții
        if ($stmt = $con->prepare(
            "SELECT lesson_slug, lesson_title, progress_percent, updated_at
             FROM learning_progress
             WHERE user_id = ?
             ORDER BY updated_at DESC")) {
            $stmt->bind_param("i", $user_id_drill);
            $stmt->execute();
            $rs = $stmt->get_result();
            while ($row = $rs->fetch_assoc()) { $user_lesson_progress[] = $row; }
            $stmt->close();
        }

        // Activity history
        if ($stmt = $con->prepare(
            "SELECT activity_type, title, link_access, accessed_at
             FROM learning_activity_history
             WHERE user_id = ?
             ORDER BY accessed_at DESC LIMIT 30")) {
            $stmt->bind_param("i", $user_id_drill);
            $stmt->execute();
            $rs = $stmt->get_result();
            while ($row = $rs->fetch_assoc()) { $user_activity[] = $row; }
            $stmt->close();
        }

        // Streak
        if ($stmt = $con->prepare(
            "SELECT current_streak, longest_streak, last_activity_date
             FROM user_streak WHERE user_id = ?")) {
            $stmt->bind_param("i", $user_id_drill);
            $stmt->execute();
            $rs = $stmt->get_result();
            $user_streak = $rs->fetch_assoc();
            $stmt->close();
        }
    }
}

// --- DATE pentru tab ACȚIUNI: lista utilizatori simplificată
$users_actions = [];
if ($tab === 'actiuni') {
    $r = $con->query("SELECT id, username, rol, created_at FROM utilizatori ORDER BY created_at DESC");
    if ($r) { while ($row = $r->fetch_assoc()) { $users_actions[] = $row; } }
}

// helper escapare
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 2 4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3z"/>
            </svg>
            Panou administrare
        </span>
        <h1 class="dash__title">Control <span class="dash__title-accent">Admin</span></h1>
        <p class="dash__lede">Vizibilitate completă asupra utilizatorilor, progresului și activității din SImp Portal.</p>

        <!-- TABS -->
        <nav style="display: flex; gap: var(--space-2); margin-top: var(--space-4); flex-wrap: wrap;">
            <a href="index.php?page=admin&tab=dashboard" class="btn btn--<?php echo $tab==='dashboard'?'primary':'quiet'; ?> btn--sm">Dashboard</a>
            <a href="index.php?page=admin&tab=utilizatori" class="btn btn--<?php echo $tab==='utilizatori'?'primary':'quiet'; ?> btn--sm">Utilizatori</a>
            <a href="index.php?page=admin&tab=detalii" class="btn btn--<?php echo $tab==='detalii'?'primary':'quiet'; ?> btn--sm">Detalii user</a>
            <a href="index.php?page=admin&tab=actiuni" class="btn btn--<?php echo $tab==='actiuni'?'primary':'quiet'; ?> btn--sm">Acțiuni</a>
            <a href="index.php?page=admin&tab=audit" class="btn btn--<?php echo $tab==='audit'?'primary':'quiet'; ?> btn--sm">Audit log</a>
            <a href="PHP/admin_export.php?type=users" class="btn btn--ghost btn--sm" style="margin-left:auto;">Export CSV utilizatori</a>
            <a href="PHP/admin_export.php?type=progress" class="btn btn--ghost btn--sm">Export CSV progres</a>
        </nav>
    </header>

<?php if ($tab === 'dashboard'): ?>
    <!-- ===== DASHBOARD ===== -->
    <div class="bento" style="gap: var(--space-6);">
        <!-- KPI cards -->
        <article class="card bento__card--stat admin-card-stat">
            <span class="card__eyebrow">Utilizatori totali</span>
            <h2><?php echo $kpi['total_users']; ?></h2>
            <p>
                <?php echo $kpi['total_admini']; ?> admin · <?php echo $kpi['inregistrati_7d']; ?> noi în ultimele 7 zile
            </p>
        </article>

        <article class="card bento__card--stat admin-card-stat">
            <span class="card__eyebrow">Activi (7 zile)</span>
            <h2 style="color: var(--color-success);"><?php echo $kpi['activi_7d']; ?></h2>
            <p>utilizatori distincți cu activitate recentă</p>
        </article>

        <article class="card bento__card--stat admin-card-stat">
            <span class="card__eyebrow">Grile rezolvate (7 zile)</span>
            <h2 style="color: var(--color-primary);"><?php echo $kpi['grile_completate_7d']; ?></h2>
            <p>din <?php echo $kpi['grile_total']; ?> grile disponibile</p>
        </article>

        <article class="card bento__card--stat admin-card-stat">
            <span class="card__eyebrow">Exerciții completate</span>
            <h2><?php echo $kpi['exercitii_completate']; ?></h2>
            <p><?php echo $kpi['metode_total']; ?> metode în catalog</p>
        </article>

        <!-- Top utilizatori -->
        <article class="card bento__card--hero" style="grid-column: 1 / -1;">
            <div class="card__head"><span class="card__eyebrow">Top 10 utilizatori (după grile + exerciții)</span></div>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Utilizator</th>
                            <th>Rol</th>
                            <th>Grile</th>
                            <th>Exerciții</th>
                            <th>Streak</th>
                            <th style="text-align:right;">Acțiune</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_users as $u): ?>
                        <tr>
                            <td><strong><?php echo h($u['username']); ?></strong></td>
                            <td style="text-align:center;"><span class="badge badge--soft"><?php echo h($u['rol']); ?></span></td>
                            <td style="text-align:center;"><?php echo (int)$u['grile']; ?></td>
                            <td style="text-align:center;"><?php echo (int)$u['exercitii']; ?></td>
                            <td style="text-align:center;"><?php echo (int)($u['streak'] ?? 0); ?> 🔥</td>
                            <td style="text-align:right;">
                                <a href="index.php?page=admin&tab=detalii&user=<?php echo (int)$u['id']; ?>" class="btn btn--quiet btn--sm">Detalii</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($top_users)): ?>
                        <tr><td colspan="6" style="padding: 1rem; text-align:center; color: var(--color-fg-muted);">Nu sunt date.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </article>

        <!-- Top metode -->
        <article class="card" style="border:1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head"><span class="card__eyebrow">Cei mai studiați algoritmi</span></div>
            <div class="card__body">
                <?php if (empty($top_metode)): ?>
                    <p style="color: var(--color-fg-muted);">Nu sunt date.</p>
                <?php else: ?>
                    <?php
                    $maxv = max(array_map(fn($x) => (int)$x['rezolvate'], $top_metode));
                    if ($maxv < 1) { $maxv = 1; }
                    ?>
                    <?php foreach ($top_metode as $m): ?>
                        <div style="margin-bottom: var(--space-3);">
                            <div style="display:flex; justify-content:space-between; font-size:var(--text-sm); margin-bottom:4px;">
                                <span><?php echo h($m['nume_metoda']); ?></span>
                                <strong><?php echo (int)$m['rezolvate']; ?></strong>
                            </div>
                            <div style="background:var(--color-surface-3); height:8px; border-radius:4px; overflow:hidden;">
                                <div style="background:var(--color-primary); height:100%; width:<?php echo round(((int)$m['rezolvate']/$maxv)*100); ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </article>

        <!-- Activitate 7 zile -->
        <article class="card" style="border:1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head"><span class="card__eyebrow">Activitate ultimele 7 zile</span></div>
            <div class="card__body">
                <?php if (empty($activitate_7d)): ?>
                    <p style="color: var(--color-fg-muted);">Nu sunt date.</p>
                <?php else: ?>
                    <?php
                    $maxa = max(array_map(fn($x) => (int)$x['total'], $activitate_7d));
                    if ($maxa < 1) { $maxa = 1; }
                    ?>
                    <div style="display:flex; align-items:flex-end; gap:8px; height:120px;">
                        <?php foreach ($activitate_7d as $d): ?>
                            <div style="flex:1; display:flex; flex-direction:column; align-items:center;">
                                <div style="width:100%; background:var(--color-primary); height:<?php echo round(((int)$d['total']/$maxa)*100); ?>%; border-radius: 4px 4px 0 0;" title="<?php echo (int)$d['total']; ?> activități"></div>
                                <span style="font-size:var(--text-xs); color:var(--color-fg-muted); margin-top:4px;"><?php echo date('d/m', strtotime($d['activity_date'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    </div>

<?php elseif ($tab === 'utilizatori'): ?>
    <!-- ===== UTILIZATORI ===== -->
    <article class="card" style="padding: var(--space-4);">
        <form method="get" action="index.php" style="display:flex; gap: var(--space-2); margin-bottom: var(--space-4); flex-wrap: wrap;">
            <input type="hidden" name="page" value="admin">
            <input type="hidden" name="tab" value="utilizatori">
            <input type="text" name="q" value="<?php echo h($search); ?>" placeholder="Caută după username..." maxlength="64" style="flex:1; padding: 0.5rem 0.75rem; border:1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-surface-2); color: var(--color-fg);">
            <button type="submit" class="btn btn--primary btn--sm">Caută</button>
            <?php if ($search !== ''): ?>
                <a href="index.php?page=admin&tab=utilizatori" class="btn btn--quiet btn--sm">Resetează</a>
            <?php endif; ?>
        </form>

        <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="text-align:left;">ID</th>
                    <th style="text-align:left;">Username</th>
                    <th>Rol</th>
                    <th>Înregistrat</th>
                    <th>Ultima activitate</th>
                    <th>Grile</th>
                    <th>Exerciții</th>
                    <th>Streak</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users_list as $u): ?>
                <tr>
                    <td style="color: var(--color-fg-muted); font-family: var(--font-mono); font-size: var(--text-xs);">#<?php echo (int)$u['id']; ?></td>
                    <td><strong><?php echo h($u['username']); ?></strong></td>
                    <td style="text-align:center;"><span class="badge badge--soft"><?php echo h($u['rol']); ?></span></td>
                    <td style="text-align:center; font-size: var(--text-xs);"><?php echo h($u['created_at'] ? date('d.m.Y', strtotime($u['created_at'])) : '-'); ?></td>
                    <td style="text-align:center; font-size: var(--text-xs);"><?php echo h($u['ultima_activitate'] ? date('d.m.Y H:i', strtotime($u['ultima_activitate'])) : '—'); ?></td>
                    <td style="text-align:center;"><?php echo (int)$u['grile']; ?></td>
                    <td style="text-align:center;"><?php echo (int)$u['exercitii']; ?></td>
                    <td style="text-align:center;"><?php echo (int)($u['streak'] ?? 0); ?></td>
                    <td style="text-align:right;">
                        <a href="index.php?page=admin&tab=detalii&user=<?php echo (int)$u['id']; ?>" class="btn btn--quiet btn--sm">Detalii</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($users_list)): ?>
                <tr><td colspan="9" style="padding: 1rem; text-align:center; color: var(--color-fg-muted);">Niciun utilizator găsit.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </article>

<?php elseif ($tab === 'detalii'): ?>
    <!-- ===== DETALII USER ===== -->
    <?php if (!$user_detail): ?>
        <article class="card" style="border:1px solid var(--color-border); background: var(--color-surface-1); padding: var(--space-6);">
            <h3>Selectează un utilizator din tab-ul <a href="index.php?page=admin&tab=utilizatori">Utilizatori</a> pentru a vedea detaliile.</h3>
        </article>
    <?php else: ?>
        <div class="bento" style="gap: var(--space-6);">
            <article class="card bento__card--hero" style="grid-column: 1 / -1; border:1px solid var(--color-border); background: var(--color-surface-1);">
                <div class="card__head">
                    <span class="card__eyebrow">Profil utilizator</span>
                </div>
                <h2 style="margin: var(--space-2) 0;"><?php echo h($user_detail['username']); ?> <span class="badge badge--soft" style="margin-left: 8px;"><?php echo h($user_detail['rol']); ?></span></h2>
                <p style="color: var(--color-fg-muted); font-size: var(--text-sm);">
                    ID #<?php echo (int)$user_detail['id']; ?> ·
                    Înregistrat: <?php echo h(date('d.m.Y H:i', strtotime($user_detail['created_at']))); ?>
                </p>
                <div style="display:flex; gap: var(--space-4); margin-top: var(--space-4); flex-wrap:wrap;">
                    <div><strong style="font-size:var(--text-2xl);"><?php echo count($user_grile); ?></strong><br><span style="color:var(--color-fg-muted); font-size:var(--text-xs);">Grile rezolvate</span></div>
                    <div><strong style="font-size:var(--text-2xl);"><?php echo count($user_exercitii); ?></strong><br><span style="color:var(--color-fg-muted); font-size:var(--text-xs);">Exerciții</span></div>
                    <div><strong style="font-size:var(--text-2xl);"><?php echo count($user_lesson_progress); ?></strong><br><span style="color:var(--color-fg-muted); font-size:var(--text-xs);">Lecții accesate</span></div>
                    <div><strong style="font-size:var(--text-2xl);"><?php echo (int)($user_streak['current_streak'] ?? 0); ?> 🔥</strong><br><span style="color:var(--color-fg-muted); font-size:var(--text-xs);">Streak curent (max <?php echo (int)($user_streak['longest_streak'] ?? 0); ?>)</span></div>
                </div>
            </article>

            <!-- Grile -->
            <article class="card" style="grid-column: 1 / -1; border:1px solid var(--color-border); background: var(--color-surface-1);">
                <div class="card__head"><span class="card__eyebrow">Grile rezolvate (<?php echo count($user_grile); ?>)</span></div>
                <div class="card__body" style="overflow-x:auto;">
                    <table style="width:100%; border-collapse: collapse;">
                        <thead style="background: var(--color-surface-2); color: var(--color-fg-muted); font-size: var(--text-xs);">
                            <tr><th style="padding:0.5rem; text-align:left;">Metoda</th><th style="padding:0.5rem;">Dificultate</th><th style="padding:0.5rem; text-align:left;">Întrebare</th><th style="padding:0.5rem;">Data</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_grile as $g): ?>
                            <tr style="border-bottom:1px solid var(--color-border); font-size: var(--text-sm);">
                                <td style="padding:0.5rem;"><?php echo h($g['nume_metoda']); ?></td>
                                <td style="padding:0.5rem; text-align:center;"><?php echo h($g['dificultate']); ?></td>
                                <td style="padding:0.5rem; max-width: 400px; overflow:hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo h($g['intrebare']); ?>"><?php echo h(mb_substr($g['intrebare'], 0, 60)); ?>…</td>
                                <td style="padding:0.5rem; text-align:center; font-size: var(--text-xs); color: var(--color-fg-muted);"><?php echo h(date('d.m.Y H:i', strtotime($g['data_completare']))); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($user_grile)): ?>
                            <tr><td colspan="4" style="padding: 1rem; text-align:center; color: var(--color-fg-muted);">Nicio grilă rezolvată.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </article>

            <!-- Exerciții -->
            <article class="card" style="border:1px solid var(--color-border); background: var(--color-surface-1);">
                <div class="card__head"><span class="card__eyebrow">Exerciții (<?php echo count($user_exercitii); ?>)</span></div>
                <div class="card__body" style="max-height: 320px; overflow-y: auto;">
                    <?php foreach ($user_exercitii as $e): ?>
                        <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--color-border); font-size: var(--text-sm);">
                            <strong><?php echo h($e['lesson_slug']); ?></strong> · <?php echo h($e['exercise_key']); ?>
                            <div style="color: var(--color-fg-muted); font-size: var(--text-xs);"><?php echo h(date('d.m.Y H:i', strtotime($e['completed_at']))); ?></div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($user_exercitii)): ?>
                        <p style="color: var(--color-fg-muted);">Nu există exerciții completate.</p>
                    <?php endif; ?>
                </div>
            </article>

            <!-- Activitate recentă -->
            <article class="card" style="border:1px solid var(--color-border); background: var(--color-surface-1);">
                <div class="card__head"><span class="card__eyebrow">Activitate recentă (ultimele 30)</span></div>
                <div class="card__body" style="max-height: 320px; overflow-y: auto;">
                    <?php foreach ($user_activity as $a): ?>
                        <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--color-border); font-size: var(--text-sm);">
                            <strong><?php echo h($a['title']); ?></strong>
                            <div style="color: var(--color-fg-muted); font-size: var(--text-xs);"><?php echo h($a['activity_type']); ?> · <?php echo h(date('d.m.Y H:i', strtotime($a['accessed_at']))); ?></div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($user_activity)): ?>
                        <p style="color: var(--color-fg-muted);">Fără activitate înregistrată.</p>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    <?php endif; ?>

<?php elseif ($tab === 'actiuni'): ?>
    <!-- ===== ACȚIUNI ADMIN ===== -->
    <article class="card" style="border:1px solid var(--color-warning-soft); background: var(--color-surface-1); padding: var(--space-4);">
        <p style="color: var(--color-warning);">⚠ Acțiunile de mai jos sunt ireversibile. Asigură-te că schimbi rolul / resetezi / ștergi utilizatorul corect.</p>
    </article>

    <article class="card" style="border:1px solid var(--color-border); background: var(--color-surface-1); padding: var(--space-4); margin-top: var(--space-4);">
        <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse: collapse;">
            <thead style="background: var(--color-surface-2); color: var(--color-fg-muted); font-size: var(--text-xs); text-transform: uppercase;">
                <tr>
                    <th style="padding: 0.75rem; text-align:left;">User</th>
                    <th style="padding: 0.75rem;">Rol curent</th>
                    <th style="padding: 0.75rem;">Schimbă rol</th>
                    <th style="padding: 0.75rem;">Resetează progres</th>
                    <th style="padding: 0.75rem;">Șterge cont</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users_actions as $u): ?>
                <?php $is_self = ((int)$u['id'] === (int)$_SESSION['user_id']); ?>
                <tr style="border-bottom:1px solid var(--color-border);">
                    <td style="padding: 0.75rem;">
                        <strong><?php echo h($u['username']); ?></strong>
                        <span style="color: var(--color-fg-muted); font-size: var(--text-xs); margin-left: 4px;">#<?php echo (int)$u['id']; ?></span>
                    </td>
                    <td style="padding: 0.75rem; text-align:center;"><span class="badge badge--soft"><?php echo h($u['rol']); ?></span></td>
                    <td style="padding: 0.75rem; text-align:center;">
                        <?php if ($is_self): ?>
                            <span style="color: var(--color-fg-muted); font-size: var(--text-xs);">— (cont propriu)</span>
                        <?php else: ?>
                        <form method="post" action="PHP/admin_actions.php" style="display:inline;" onsubmit="return confirm('Schimbă rolul utilizatorului <?php echo h($u['username']); ?>?');">
                            <?php csrf_field(); ?>
                            <input type="hidden" name="action" value="change_role">
                            <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                            <select name="new_role" style="padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); background: var(--color-surface-2); color: var(--color-fg); border: 1px solid var(--color-border);">
                                <option value="user" <?php echo $u['rol']==='user'?'selected':''; ?>>user</option>
                                <option value="admin" <?php echo $u['rol']==='admin'?'selected':''; ?>>admin</option>
                            </select>
                            <button type="submit" class="btn btn--quiet btn--sm">Aplică</button>
                        </form>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0.75rem; text-align:center;">
                        <form method="post" action="PHP/admin_actions.php" style="display:inline;" onsubmit="return confirm('Resetează TOT progresul pentru <?php echo h($u['username']); ?>? Această acțiune este ireversibilă.');">
                            <?php csrf_field(); ?>
                            <input type="hidden" name="action" value="reset_progress">
                            <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                            <button type="submit" class="btn btn--quiet btn--sm" style="color: var(--color-warning);">Reset</button>
                        </form>
                    </td>
                    <td style="padding: 0.75rem; text-align:center;">
                        <?php if ($is_self): ?>
                            <span style="color: var(--color-fg-muted); font-size: var(--text-xs);">— (cont propriu)</span>
                        <?php else: ?>
                        <form method="post" action="PHP/admin_actions.php" style="display:inline;" onsubmit="return confirm('ȘTERGE definitiv contul <?php echo h($u['username']); ?>? Această acțiune NU poate fi anulată.');">
                            <?php csrf_field(); ?>
                            <input type="hidden" name="action" value="delete_user">
                            <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                            <button type="submit" class="btn btn--quiet btn--sm" style="color: var(--color-danger);">Șterge</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($users_actions)): ?>
                <tr><td colspan="5" style="padding: 1rem; text-align:center; color: var(--color-fg-muted);">Niciun utilizator în baza de date.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </article>
<?php elseif ($tab === 'audit'): ?>
    <!-- ===== AUDIT LOG ===== -->
    <article class="card" style="padding: var(--space-4);">
        <p style="color: var(--color-fg-muted); font-size: var(--text-sm); margin-bottom: var(--space-3);">
            Înregistrează toate acțiunile administrative (schimbări de rol, resetări de progres, ștergeri de cont). Util pentru forensics și pentru a verifica activitatea altor admini.
        </p>
        <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="text-align:left;">Data</th>
                    <th style="text-align:left;">Admin</th>
                    <th>Acțiune</th>
                    <th style="text-align:left;">Țintă</th>
                    <th style="text-align:left;">Detalii</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // POLISH [P5]: Pagination for Audit Log
                $page_audit = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
                $limit_audit = 25;
                $offset_audit = ($page_audit - 1) * $limit_audit;

                $total_audit_sql = "SELECT COUNT(*) as count FROM admin_audit_log";
                $total_audit_res = $con->query($total_audit_sql);
                $total_audit_row = $total_audit_res->fetch_assoc();
                $total_audit_rows = $total_audit_row['count'];
                $total_audit_pages = ceil($total_audit_rows / $limit_audit);

                $logs = [];
                if ($stmt_logs = $con->prepare("SELECT * FROM admin_audit_log ORDER BY created_at DESC LIMIT ? OFFSET ?")) {
                    $stmt_logs->bind_param("ii", $limit_audit, $offset_audit);
                    $stmt_logs->execute();
                    $r = $stmt_logs->get_result();
                    if ($r) { while ($row = $r->fetch_assoc()) { $logs[] = $row; } }
                    $stmt_logs->close();
                }
                ?>
                <?php foreach ($logs as $l): ?>
                <tr style="font-size: var(--text-sm);">
                    <td style="font-size: var(--text-xs); color: var(--color-fg-muted); white-space: nowrap;"><?php echo h(date('d.m.Y H:i:s', strtotime($l['created_at']))); ?></td>
                    <td><strong><?php echo h($l['admin_username']); ?></strong></td>
                    <td style="text-align:center;">
                        <?php
                        $color_map = ['change_role' => 'warning', 'reset_progress' => 'primary', 'delete_user' => 'danger'];
                        $col = $color_map[$l['action_type']] ?? 'fg-muted';
                        ?>
                        <span style="padding: 2px 8px; border-radius: 4px; background: var(--color-<?php echo $col; ?>-soft); color: var(--color-<?php echo $col; ?>); font-size: var(--text-xs);"><?php echo h($l['action_type']); ?></span>
                    </td>
                    <td><?php echo h($l['target_username'] ?? '—'); ?> <?php if ($l['target_user_id']): ?><span style="color:var(--color-fg-muted); font-size:var(--text-xs);">#<?php echo (int)$l['target_user_id']; ?></span><?php endif; ?></td>
                    <td style="font-family: var(--font-mono); font-size: var(--text-xs); color: var(--color-fg-muted);"><?php echo h($l['details'] ?? ''); ?></td>
                    <td style="text-align:center; font-size: var(--text-xs); color: var(--color-fg-muted);"><?php echo h($l['ip_address'] ?? '—'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?>
                <tr><td colspan="6" style="padding: 1rem; text-align:center; color: var(--color-fg-muted);">Nicio acțiune înregistrată încă.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>

        <?php 
        // POLISH [P5]: Pagination UI
        if ($total_audit_pages > 1): ?>
            <div style="display: flex; justify-content: center; align-items: center; gap: var(--space-4); margin-top: var(--space-6);">
                <?php if ($page_audit > 1): ?>
                    <a href="index.php?page=admin&tab=audit&p=<?php echo ($page_audit-1); ?>" class="btn btn--quiet btn--sm">← Anterior</a>
                <?php endif; ?>
                <span style="font-size: var(--text-xs); color: var(--color-fg-muted);">Pagina <strong><?php echo $page_audit; ?></strong> din <?php echo $total_audit_pages; ?></span>
                <?php if ($page_audit < $total_audit_pages): ?>
                    <a href="index.php?page=admin&tab=audit&p=<?php echo ($page_audit+1); ?>" class="btn btn--quiet btn--sm">Următor →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </article>
<?php endif; ?>


</div>
