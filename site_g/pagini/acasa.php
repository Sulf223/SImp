<?php
/* ============================================================================
   acasa.php — Dashboard (redesign Engineering-Modern, Bento Grid)
   PHP logic preserved 1:1 from previous version.
   Visual layer rebuilt on top of:
     - CSS/modern_vars.css      (design tokens)
     - CSS/dashboard_modern.css (component styles)
   Icon set: Lucide (inlined as SVG).
   ============================================================================ */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    ?>
            <div data-component="dashboard-modern">
        <div class="dash__guard">
            <h3>Acces restricționat</h3>
            <p>Trebuie să fii autentificat pentru a accesa Panoul de Control.</p>
            <a href="index.php?page=login" class="btn btn--primary">
                Mergi la logare
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    <?php
    return;
}

require_once __DIR__ . '/../PHP/conexiune.php';
require_once __DIR__ . '/../PHP/progres_learning.php';

$userId   = (int)$_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username'] ?? 'Student', ENT_QUOTES, 'UTF-8');

$continueData          = get_continue_learning($con, $userId);
$progres_curent        = (int)($continueData['progress_percent'] ?? 0);
$lectie_curenta_titlu  = (string)($continueData['lesson_title'] ?? 'Bubble Sort (Metoda Bulelor)');
$lectie_curenta_link   = (string)($continueData['link'] ?? 'index.php?page=sort_bubble');
$lectie_curenta_slug   = (string)($continueData['lesson_slug'] ?? 'sort_bubble');

$stats        = get_exercise_stats($con, $userId, $lectie_curenta_slug);
$recentItems  = get_recent_activity($con, $userId, 3);
$streakInfo   = get_streak($con, $userId);

$algoritm_zilei_titlu = 'Merge Sort (Interclasare)';
$algoritm_zilei_desc  = 'Azi aprofundăm o tehnică eficientă (Divide et Impera) cu complexitate O(n log n).';

/* Derived display values (no business logic — purely presentation) */
$exDone   = (int)($stats['done']  ?? 0);
$exTotal  = (int)($stats['total'] ?? 0);
$nrRecent = is_array($recentItems) ? count($recentItems) : 0;

$tableExists = function (string $table) use ($con): bool {
    $safeTable = $con->real_escape_string($table);
    $result = $con->query("SHOW TABLES LIKE '{$safeTable}'");
    if (!$result) return false;
    $exists = $result->num_rows > 0;
    $result->free();
    return $exists;
};

$lessons = function_exists('get_fundamental_lessons') ? get_fundamental_lessons() : [];
$totalLessons = max(1, count($lessons));
$completedLessons = 0;
$avgProgress = 0;
if ($stmt = $con->prepare("SELECT COUNT(*) AS total_started, SUM(progress_percent >= 100) AS completed, AVG(progress_percent) AS avg_progress FROM learning_progress WHERE user_id = ?")) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: [];
    $completedLessons = (int)($row['completed'] ?? 0);
    $avgProgress = (int)round((float)($row['avg_progress'] ?? 0));
    $stmt->close();
}

$totalGrile = 0;
$solvedGrile = 0;
$quizAttempts = 0;
$quizCorrect = 0;
$quizAccuracy = 0;
$lastWrongQuiz = null;

if ($res = $con->query("SELECT COUNT(*) AS c FROM grile_cpp")) {
    $totalGrile = (int)($res->fetch_assoc()['c'] ?? 0);
    $res->free();
}
if ($stmt = $con->prepare("SELECT COUNT(*) AS c FROM progres_grile WHERE id_utilizator = ?")) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $solvedGrile = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);
    $stmt->close();
}
if ($tableExists('quiz_attempts')) {
    if ($stmt = $con->prepare("SELECT COUNT(*) AS attempts, SUM(is_correct = 1) AS correct FROM quiz_attempts WHERE user_id = ?")) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: [];
        $quizAttempts = (int)($row['attempts'] ?? 0);
        $quizCorrect = (int)($row['correct'] ?? 0);
        $quizAccuracy = $quizAttempts > 0 ? (int)round(($quizCorrect / $quizAttempts) * 100) : 0;
        $stmt->close();
    }
    if ($stmt = $con->prepare("SELECT g.id, g.intrebare, g.nume_metoda FROM quiz_attempts qa JOIN grile_cpp g ON g.id = qa.grila_id WHERE qa.user_id = ? AND qa.is_correct = 0 ORDER BY qa.attempted_at DESC LIMIT 1")) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $lastWrongQuiz = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
    }
}

$nextAction = [
    'title' => 'Continuă lecția curentă',
    'description' => $lectie_curenta_titlu,
    'link' => $lectie_curenta_link,
    'label' => 'Reia lecția',
];
if ($lastWrongQuiz) {
    $nextAction = [
        'title' => 'Reia ultima grilă greșită',
        'description' => $lastWrongQuiz['nume_metoda'] . ' · ' . mb_strimwidth((string)$lastWrongQuiz['intrebare'], 0, 90, '...', 'UTF-8'),
        'link' => 'index.php?page=grila_interactiva&id=' . (int)$lastWrongQuiz['id'],
        'label' => 'Repară greșeala',
    ];
} elseif ($progres_curent >= 100 && $solvedGrile < $totalGrile) {
    $nextAction = [
        'title' => 'Testează ce ai învățat',
        'description' => 'Ai lecția curentă completă; următorul pas bun este o grilă.',
        'link' => 'index.php?page=grile',
        'label' => 'Mergi la grile',
    ];
}
?>

<div data-component="dashboard-modern">

    <!-- ============================================================
         HEADER
         ============================================================ -->
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M7 7h10v10"/><path d="M7 17 17 7"/>
            </svg>
            Dashboard personal
        </span>
        <h1 class="dash__title">
            Salutare, <span class="dash__title-accent"><?php echo $username; ?></span>
            <?php if ($streakInfo['current'] > 0): ?>
                <span class="badge badge--soft" style="vertical-align: middle; margin-left: var(--space-3); color: var(--color-warning); border-color: var(--color-warning-soft);">
                    🔥 <?php echo $streakInfo['current']; ?> zile streak
                </span>
            <?php endif; ?>
        </h1>
        <p class="dash__lede">
            Continuă de unde ai rămas sau explorează un algoritm nou. Progresul tău este salvat automat.
        </p>
    </header>

    <!-- ============================================================
         BENTO GRID
         ============================================================ -->
    <div class="bento">

        <?php if (function_exists('is_admin') && is_admin()): ?>
        <!-- ── CARD ADMIN: vizibil doar pentru administratori ───── -->
        <article class="card bento__card--accent" style="grid-column: 1 / -1; border: 1px solid var(--color-warning-soft); background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30%; right: -10%; width: 240px; height: 240px; background: radial-gradient(circle, var(--color-warning-soft) 0%, transparent 70%); opacity: 0.4; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: var(--color-warning); display: inline-flex; align-items: center; gap: var(--space-2);">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Panou administrare
                </span>
            </div>
            <h3 style="font-size: var(--text-lg); margin: var(--space-2) 0; position: relative; z-index: 1;">Vezi activitatea utilizatorilor</h3>
            <p style="color: var(--color-fg-muted); font-size: var(--text-sm); margin-bottom: var(--space-4); position: relative; z-index: 1;">
                Acces rapid la statistici globale, lista de utilizatori cu progresul lor complet, drill-down per cont, audit log al acțiunilor și export CSV.
            </p>
            <div class="card__actions" style="position: relative; z-index: 1; display: flex; flex-wrap: wrap; gap: var(--space-2);">
                <a href="index.php?page=admin&tab=dashboard" class="btn btn--primary btn--sm">
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <a href="index.php?page=admin&tab=utilizatori" class="btn btn--quiet btn--sm">Utilizatori</a>
                <a href="index.php?page=admin&tab=audit" class="btn btn--quiet btn--sm">Audit log</a>
                <a href="PHP/admin_export.php?type=users" class="btn btn--ghost btn--sm" style="margin-left: auto;">Export CSV</a>
            </div>
        </article>
        <?php endif; ?>

        <!-- ── HERO: Continue learning ────────────────────────── -->
        <article class="card card--hero bento__card--hero">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/>
                    </svg>
                    Continuă învățarea
                </span>
                <span class="badge badge--soft"><?php echo $progres_curent; ?>% complet</span>
            </div>

            <h2 class="card__title">
                <?php echo htmlspecialchars($lectie_curenta_titlu, ENT_QUOTES, 'UTF-8'); ?>
            </h2>

            <p class="card__meta">
                <?php echo $exDone; ?> din <?php echo $exTotal; ?> exerciții rezolvate la această lecție
            </p>

            <div class="progress" role="progressbar" aria-valuenow="<?php echo $progres_curent; ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Progres lecție curentă">
                <div class="progress__bar" style="width: <?php echo $progres_curent; ?>%;"></div>
            </div>

            <div class="card__actions">
                <a href="<?php echo htmlspecialchars($lectie_curenta_link, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn--primary">
                    Reia lecția
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
                <a href="index.php?page=sortare" class="btn btn--ghost">
                    Vezi toate metodele
                </a>
            </div>
        </article>

        <article class="card bento__card--timeline dashboard-focus-card">
            <header class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/>
                    </svg>
                    Focus pentru azi
                </span>
            </header>
            <div class="dashboard-focus-grid">
                <section>
                    <span class="stat__label">Următorul pas</span>
                    <h3><?php echo htmlspecialchars($nextAction['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($nextAction['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <a href="<?php echo htmlspecialchars($nextAction['link'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn--primary btn--sm"><?php echo htmlspecialchars($nextAction['label'], ENT_QUOTES, 'UTF-8'); ?></a>
                </section>
                <section>
                    <span class="stat__label">Progres total lecții</span>
                    <strong><?php echo $completedLessons; ?> / <?php echo $totalLessons; ?></strong>
                    <p>Media progresului: <?php echo $avgProgress; ?>%</p>
                </section>
                <section>
                    <span class="stat__label">Grile</span>
                    <strong><?php echo $solvedGrile; ?> / <?php echo $totalGrile; ?></strong>
                    <p>Rezolvate corect în banca oficială.</p>
                </section>
                <section>
                    <span class="stat__label">Acuratețe</span>
                    <strong><?php echo $quizAttempts > 0 ? $quizAccuracy . '%' : '—'; ?></strong>
                    <p><?php echo $quizAttempts > 0 ? $quizCorrect . ' corecte din ' . $quizAttempts . ' încercări.' : 'Apare după primele încercări.'; ?></p>
                </section>
            </div>
        </article>

        <!-- ── ACCENT: Algoritmul zilei ───────────────────────── -->
        <article class="card card--accent bento__card--accent">
            <span class="card__eyebrow">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
                </svg>
                Algoritmul zilei
            </span>

            <h3 class="card__title-sm">
                <?php echo htmlspecialchars($algoritm_zilei_titlu, ENT_QUOTES, 'UTF-8'); ?>
            </h3>

            <p class="card__body">
                <?php echo htmlspecialchars($algoritm_zilei_desc, ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <div class="card__actions">
                <a href="index.php?page=sort_merge" class="link-arrow">
                    Descoperă metoda
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </article>

        <!-- ── STAT 1: Exerciții rezolvate ────────────────────── -->
        <article class="card card--stat bento__card--stat">
            <span class="stat__label">Exerciții rezolvate</span>
            <span class="stat__value">
                <?php echo $exDone; ?><span class="stat__unit">/ <?php echo $exTotal; ?></span>
            </span>
            <span class="stat__sub">la lecția curentă</span>
        </article>

        <!-- ── STAT 2: Progres lecție ─────────────────────────── -->
        <article class="card card--stat bento__card--stat">
            <span class="stat__label">Progres lecție</span>
            <span class="stat__value">
                <?php echo $progres_curent; ?><span class="stat__unit">%</span>
            </span>
            <span class="stat__delta stat__delta--up">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                </svg>
                pe drumul cel bun
            </span>
        </article>

        <!-- ── STAT 3: Activități recente ─────────────────────── -->
        <article class="card card--stat bento__card--stat">
            <span class="stat__label">Activități recente</span>
            <span class="stat__value">
                <?php echo $nrRecent; ?>
            </span>
            <span class="stat__sub">în ultimele zile</span>
        </article>

        <!-- ── AI: Profesor AI shortcut ───────────────────────── -->
        <article class="card card--ai bento__card--ai">
            <div class="ai__icon-wrap">
                <svg class="icon icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                    <path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/>
                </svg>
            </div>
            <h3 class="card__title-sm">Profesor AI</h3>
            <p class="card__body">
                Pune întrebări despre C++ și primește indicii pas-cu-pas, fără soluții directe.
            </p>
            <div class="card__actions">
                <a href="index.php?page=profesor_ai" class="btn btn--ghost btn--sm">
                    Deschide chat
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </article>

        <!-- ── TIMELINE: ultimele activități ──────────────────── -->
        <article class="card card--timeline bento__card--timeline">
            <header class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Ultimele activități
                </span>
                <a href="index.php?page=laborator_vizual" class="link-arrow">
                    Vezi toate
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </header>

            <?php if (!empty($recentItems)): ?>
                <ul class="timeline">
                    <?php foreach ($recentItems as $item): ?>
                        <li class="timeline__item">
                            <span class="timeline__icon" aria-hidden="true">
                                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                </svg>
                            </span>
                            <div class="timeline__body">
                                <span class="timeline__title">
                                    <?php echo htmlspecialchars((string)$item['title'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span class="timeline__meta">
                                    <?php echo htmlspecialchars((string)$item['activity_type'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                            <a href="<?php echo htmlspecialchars((string)$item['link_access'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn--quiet btn--sm">
                                Reia
                                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                                </svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="empty-state">
                    <span class="empty-state__icon" aria-hidden="true">
                        <svg class="icon icon--lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                            <path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                            <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                            <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>
                        </svg>
                    </span>
                    <p>Nu ai activitate salvată încă. Începe prima lecție și construiește-ți istoricul.</p>
                    <a href="index.php?page=sort_bubble" class="btn btn--primary">
                        Începe cu Bubble Sort
                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </article>

    </div>
</div>
