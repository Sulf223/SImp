<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) { header('Location: index.php?page=login'); exit; }
require_once __DIR__ . '/../PHP/conexiune.php';
require_once __DIR__ . '/../PHP/progres_learning.php';

$userId = (int)$_SESSION['user_id'];

// Fetch user info
$stmt = mysqli_prepare($con, "SELECT username, display_name, bio, avatar_seed, theme_pref, created_at FROM utilizatori WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: [];
mysqli_stmt_close($stmt);

$displayName = htmlspecialchars($user['display_name'] ?? $user['username'] ?? 'Student');
$bio = htmlspecialchars($user['bio'] ?? '');
$avatarSeed = $user['avatar_seed'] ?? $user['username'] ?? 'default';
$avatarUrl = "https://api.dicebear.com/7.x/identicon/svg?seed=" . urlencode($avatarSeed);

$streak = get_streak($con, $userId);
$heatmap = get_activity_heatmap($con, $userId, 26);

$totalActivities = array_sum($heatmap);
$activeDays = count($heatmap);
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
      <img src="<?= $avatarUrl ?>" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; background: var(--color-surface-2);">
      <h3 class="card__title-sm"><?= $displayName ?></h3>
      <p class="card__meta">@<?= htmlspecialchars($user['username']) ?> · membru din <?= date('M Y', strtotime($user['created_at'])) ?></p>
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
    
    <!-- Heatmap (col-span-12) -->
    <article class="card bento__card--timeline">
      <header class="card__head">
        <span class="card__eyebrow">Ultimele 26 săptămâni</span>
      </header>
      <div id="heatmap-container" data-heatmap='<?= json_encode($heatmap) ?>' style="overflow-x: auto; padding: var(--space-4) 0;">
        <!-- generat de JS -->
      </div>
    </article>
  </div>
</div>

<script>
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
