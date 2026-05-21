<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) { header('Location: index.php?page=login'); exit; }
require_once __DIR__ . '/../PHP/conexiune.php';
require_once __DIR__ . '/../PHP/progres_learning.php';

$userId = (int)$_SESSION['user_id'];
if (function_exists('ensure_learning_tables')) {
    ensure_learning_tables($con);
}

$user = [];
$stmt = $con->prepare("SELECT username, display_name, bio, avatar_seed, theme_pref, created_at FROM utilizatori WHERE id = ?");
if ($stmt) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc() ?: [];
    $stmt->close();
} else {
    error_log('profil.php: failed to load user profile: ' . $con->error);
}
$user = array_merge([
    'username' => 'Student',
    'display_name' => null,
    'bio' => null,
    'avatar_seed' => null,
    'theme_pref' => null,
    'created_at' => date('Y-m-d'),
], $user);

$displayName = htmlspecialchars($user['display_name'] ?? $user['username'] ?? 'Student');
$bio = htmlspecialchars($user['bio'] ?? '');
$avatarSeed = $user['avatar_seed'] ?? $user['username'] ?? 'default';
$avatarUrl = "https://api.dicebear.com/7.x/identicon/svg?seed=" . urlencode($avatarSeed);

$streak = get_streak($con, $userId);
$heatmap = get_activity_heatmap($con, $userId, 26);

$totalActivities = array_sum($heatmap);
$activeDays = count(array_filter($heatmap, static fn($count) => (int)$count > 0));

$lessons = function_exists('get_fundamental_lessons') ? get_fundamental_lessons() : [];
$totalLessons = max(1, count($lessons));
$completedLessons = 0;
$avgLessonProgress = 0;
if ($stmt = $con->prepare("SELECT SUM(progress_percent >= 100) AS completed, AVG(progress_percent) AS avg_progress FROM learning_progress WHERE user_id = ?")) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: [];
    $completedLessons = (int)($row['completed'] ?? 0);
    $avgLessonProgress = (int)round((float)($row['avg_progress'] ?? 0));
    $stmt->close();
}

$totalGrile = 0;
$solvedGrile = 0;
$quizAttempts = 0;
$quizCorrect = 0;
$quizAccuracy = 0;
$aiQuizStats = [
    'total' => 0,
    'avg_percent' => 0,
    'best_percent' => 0,
    'latest_percent' => null,
];
$aiQuizHistory = [];
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
if ($stmt = $con->prepare("SELECT COUNT(*) AS attempts, SUM(is_correct = 1) AS correct FROM quiz_attempts WHERE user_id = ?")) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: [];
    $quizAttempts = (int)($row['attempts'] ?? 0);
    $quizCorrect = (int)($row['correct'] ?? 0);
    $quizAccuracy = $quizAttempts > 0 ? (int)round(($quizCorrect / $quizAttempts) * 100) : 0;
    $stmt->close();
} else {
    error_log('profil.php: failed to load quiz attempts: ' . $con->error);
}
if ($stmt = $con->prepare("SELECT COUNT(*) AS total, AVG(percent) AS avg_percent, MAX(percent) AS best_percent FROM ai_quiz_attempts WHERE user_id = ?")) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: [];
    $aiQuizStats['total'] = (int)($row['total'] ?? 0);
    $aiQuizStats['avg_percent'] = (int)round((float)($row['avg_percent'] ?? 0));
    $aiQuizStats['best_percent'] = (int)round((float)($row['best_percent'] ?? 0));
    $stmt->close();
} else {
    error_log('profil.php: failed to load AI quiz stats: ' . $con->error);
}
if ($stmt = $con->prepare("SELECT path_slug, score, total, percent, created_at FROM ai_quiz_attempts WHERE user_id = ? ORDER BY created_at DESC LIMIT 10")) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $aiQuizHistory[] = $row;
    }
    $stmt->close();
} else {
    error_log('profil.php: failed to load AI quiz history: ' . $con->error);
}
if (!empty($aiQuizHistory)) {
    $aiQuizStats['latest_percent'] = (int)round((float)$aiQuizHistory[0]['percent']);
}

$nextProfileRecommendation = 'Continuă lecțiile de sortare și rulează câteva grile după fiecare algoritm.';
if ($completedLessons >= $totalLessons && $solvedGrile < $totalGrile) {
    $nextProfileRecommendation = 'Ai parcurs lecțiile principale; merită să crești scorul la grile.';
} elseif ($quizAttempts > 0 && $quizAccuracy < 70) {
    $nextProfileRecommendation = 'Revino la grilele greșite și citește explicația după fiecare răspuns.';
} elseif ($avgLessonProgress >= 70) {
    $nextProfileRecommendation = 'Încearcă Laboratorul Vizual și Comparațiile ca să legi teoria de execuție.';
}

// FEATURE [F5]: Achievements
$sql_ach = "SELECT a.*, ua.unlocked_at IS NOT NULL AS unlocked
            FROM achievements a
            LEFT JOIN user_achievements ua ON ua.achievement_id = a.id AND ua.user_id = ?
            ORDER BY ua.unlocked_at DESC, a.id ASC";
$achievements = [];
if ($stmt = $con->prepare($sql_ach)) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $achievements[] = $row;
    }
    $stmt->close();
} else {
    error_log('profil.php: failed to load achievements: ' . $con->error);
}
?>

<div data-component="dashboard-modern">
  <header class="dash__header">
    <span class="dash__eyebrow">
      <svg class="icon"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v2"/></svg>
      Profil
    </span>
    <h1 class="dash__title">Salut, <span class="dash__title-accent"><?= $displayName ?></span></h1>
    <p class="dash__lede"><?= $bio ?: 'Adaugă o descriere despre tine din setări.' ?></p>
  </header>
  
  <div class="bento">
    <!-- Avatar + info card (col-span-4) -->
    <article class="card bento__card--accent">
      <img src="<?= htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; background: var(--color-surface-2);">
      <h3 class="card__title-sm"><?= $displayName ?></h3>
      <p class="card__meta">@<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?> · membru din <?= date('M Y', strtotime($user['created_at'])) ?></p>
      <div class="card__actions">
        <a href="#" class="btn btn--ghost btn--sm">Editează profil (În curând)</a>
      </div>
    </article>
    
    <!-- Streak card (col-span-4) -->
    <article class="card bento__card--stat">
      <span class="stat__label">🔥 STREAK ACTUAL</span>
      <span class="stat__value"><?= $streak['current'] ?> <span class="stat__unit">zile</span></span>
      <span class="stat__sub">Cel mai lung: <?= $streak['longest'] ?> zile</span>
    </article>
    
    <!-- Stats card (col-span-4) -->
    <article class="card bento__card--stat">
      <span class="stat__label">ACTIVITATE TOTALĂ</span>
      <span class="stat__value"><?= $totalActivities ?></span>
      <span class="stat__sub">în <?= $activeDays ?> zile active</span>
    </article>

    <article class="card bento__card--timeline profile-summary-card">
      <header class="card__head">
        <span class="card__eyebrow">Rezumat progres</span>
      </header>
      <div class="profile-summary-grid">
        <section>
          <span class="stat__label">Algoritmi parcurși</span>
          <strong><?= $completedLessons ?> / <?= $totalLessons ?></strong>
          <p>Media lecțiilor: <?= $avgLessonProgress ?>%</p>
        </section>
        <section>
          <span class="stat__label">Grile rezolvate</span>
          <strong><?= $solvedGrile ?> / <?= $totalGrile ?></strong>
          <p>Banca oficială de întrebări.</p>
        </section>
        <section>
          <span class="stat__label">Acuratețe</span>
          <strong><?= $quizAttempts > 0 ? $quizAccuracy . '%' : '—' ?></strong>
          <p><?= $quizAttempts > 0 ? $quizCorrect . ' corecte din ' . $quizAttempts . ' încercări.' : 'Apare după primele grile.' ?></p>
        </section>
        <section>
          <span class="stat__label">Recomandare</span>
          <strong>Următorul pas</strong>
          <p><?= htmlspecialchars($nextProfileRecommendation, ENT_QUOTES, 'UTF-8') ?></p>
        </section>
      </div>
    </article>

    <article class="card bento__card--timeline ai-progress-card">
      <header class="card__head">
        <span class="card__eyebrow">Evoluție teste AI</span>
        <a href="index.php?page=profesor_ai" class="btn btn--ghost btn--sm">Dă un test AI</a>
      </header>
      <?php if (empty($aiQuizHistory)): ?>
        <p class="card__body">Încă nu ai teste AI finalizate. După primul test, aici apar scorurile și evoluția.</p>
      <?php else: ?>
        <div class="ai-progress-summary">
          <section>
            <span class="stat__label">Teste AI</span>
            <strong><?= (int)$aiQuizStats['total'] ?></strong>
          </section>
          <section>
            <span class="stat__label">Ultimul scor</span>
            <strong><?= (int)$aiQuizStats['latest_percent'] ?>%</strong>
          </section>
          <section>
            <span class="stat__label">Media</span>
            <strong><?= (int)$aiQuizStats['avg_percent'] ?>%</strong>
          </section>
          <section>
            <span class="stat__label">Cel mai bun</span>
            <strong><?= (int)$aiQuizStats['best_percent'] ?>%</strong>
          </section>
        </div>
        <div class="ai-progress-chart ai-progress-chart--wide" aria-label="Evoluția ultimelor teste AI">
          <?php foreach (array_reverse($aiQuizHistory) as $attempt):
              $percent = max(3, min(100, (int)round((float)$attempt['percent'])));
          ?>
            <div class="ai-progress-bar" title="<?= htmlspecialchars($attempt['score'] . '/' . $attempt['total'] . ' · ' . $attempt['path_slug'] . ' · ' . date('d.m H:i', strtotime($attempt['created_at'])), ENT_QUOTES, 'UTF-8') ?>">
              <span style="height: <?= $percent ?>%;"></span>
              <small><?= $percent ?>%</small>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </article>
    
    <!-- Heatmap (col-span-12) -->
    <article class="card bento__card--timeline">
      <header class="card__head">
        <span class="card__eyebrow">Ultimele 26 săptămâni</span>
      </header>
      <div id="heatmap-container" data-heatmap='<?= htmlspecialchars(json_encode($heatmap), ENT_QUOTES, 'UTF-8') ?>' style="overflow-x: auto; padding: var(--space-4) 0;">
        <!-- generat de JS -->
      </div>
    </article>

    <!-- FEATURE [F5]: Achievements UI (col-span-12) -->
    <article class="card bento__card--timeline">
      <header class="card__head">
        <span class="card__eyebrow">
          <svg class="icon"><path d="M12 15l-2 5-9-5 9-5 2 5Z"/><path d="M12 15l2 5 9-5-9-5-2 5Z"/></svg>
          Realizări
        </span>
      </header>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: var(--space-4); margin-top: var(--space-4);">
        <?php foreach ($achievements as $ach): 
            $opacity = $ach['unlocked'] ? '1' : '0.4';
            $filter = $ach['unlocked'] ? 'none' : 'grayscale(100%)';
            $bg = $ach['unlocked'] ? 'linear-gradient(135deg, rgba(110, 86, 207, 0.1) 0%, rgba(110, 86, 207, 0.02) 100%)' : 'var(--color-surface-2)';
            $border = $ach['unlocked'] ? '1px solid var(--color-primary-soft)' : '1px dashed var(--color-border)';
        ?>
        <div style="border: <?= $border ?>; background: <?= $bg ?>; padding: var(--space-4); border-radius: var(--radius-md); opacity: <?= $opacity ?>; filter: <?= $filter ?>; transition: all 0.2s; display: flex; flex-direction: column; gap: var(--space-2); text-align: center;">
            <div style="font-size: 2rem; margin: 0 auto; color: var(--color-primary);">
                <?php
                $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><circle cx="12" cy="12" r="10"/></svg>';
                if ($ach['icon'] === 'star') $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
                if ($ach['icon'] === 'sun') $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y2="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
                if ($ach['icon'] === 'check-circle') $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
                if ($ach['icon'] === 'award') $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>';
                if ($ach['icon'] === 'crown') $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"/></svg>';
                if ($ach['icon'] === 'code') $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>';
                if ($ach['icon'] === 'zap') $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>';
                if ($ach['icon'] === 'layers') $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>';
                if ($ach['icon'] === 'flame') $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>';
                echo $iconSvg;
                ?>
            </div>
            <strong style="font-size: var(--text-sm); color: var(--color-fg);"><?= htmlspecialchars($ach['title']) ?></strong>
            <span style="font-size: var(--text-xs); color: var(--color-fg-muted); line-height: 1.4;"><?= htmlspecialchars($ach['description']) ?></span>
            <?php if ($ach['unlocked']): ?>
                <span style="font-size: 10px; color: var(--color-success); font-weight: 600; margin-top: auto; padding-top: var(--space-2);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12" style="vertical-align: middle;"><polyline points="20 6 9 17 4 12"/></svg> Deblocat</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </article>
  </div>
</div>

<script nonce="<?= $nonce ?>">
// FIX [M2]: Adăugare nonce pentru CSP
(function() {
  const container = document.getElementById('heatmap-container');
  if (!container) return;
  const data = JSON.parse(container.dataset.heatmap);
  const weeks = 26;
  const today = new Date();
  const startDate = new Date(today);
  startDate.setDate(startDate.getDate() - weeks * 7);
  // align to Monday
  while (startDate.getDay() !== 1) startDate.setDate(startDate.getDate() - 1);
  
  let html = '<svg width="' + (weeks * 14 + 30) + '" height="120" style="font-family: var(--font-mono); font-size: 10px;">';
  for (let w = 0; w < weeks; w++) {
    for (let d = 0; d < 7; d++) {
      const date = new Date(startDate);
      date.setDate(date.getDate() + w * 7 + d);
      const iso = date.toISOString().slice(0, 10);
      const count = data[iso] || 0;
      let opacity = 0;
      if (count > 0) opacity = Math.min(0.2 + count * 0.15, 1);
      const fill = count > 0 ? `rgba(110, 86, 207, ${opacity})` : 'var(--color-surface-2)';
      html += `<rect x="${w * 14}" y="${d * 14}" width="12" height="12" rx="2" fill="${fill}"><title>${iso}: ${count}</title></rect>`;
    }
  }
  html += '</svg>';
  container.innerHTML = html;
})();
</script>
