<?php
// index.php - Acum este fișierul principal de layout (template)
if (session_status() === PHP_SESSION_NONE) {
    // Configurăm parametrii securizați pentru cookie-urile de sesiune
    session_set_cookie_params([
        'lifetime' => 0, // Cookie expiră la închiderea browser-ului
        'path' => '/',
        'domain' => '', // Autodetectează domeniul
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // Automatizat: true doar dacă suntem pe HTTPS
        'httponly' => true, // Previne accesul prin JavaScript (protecție XSS)
        'samesite' => 'Strict' // Protecție CSRF suplimentară
    ]);
    session_start();
}

// CSP compatibil cu scripturile inline existente din proiect.
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; frame-src https://onecompiler.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com;");

// Includem helper-ele (Flash messages, CSRF)
require_once 'PHP/helpers.php';

// Paginile permise pentru a preveni atacuri de tip LFI (Local File Inclusion)
// Am adăugat și o cale către 'pagini/' pentru a păstra structura curată
$pagini_permise = [
    'bun_venit' => 'pagini/bun_venit.php',
    'acasa' => 'pagini/acasa.php',
    'algoritmi' => 'pagini/algoritmi.php',
    'profesor_ai' => 'pagini/profesor_ai.php',
    'sortare' => 'pagini/sortare.php',
    'algoritmi_avansati' => 'pagini/algoritmi_avansati.php',
    'recursivitate' => 'pagini/recursivitate.php',
    'backtracking' => 'pagini/backtracking.php',
    'greedy' => 'pagini/greedy.php',
    'divide_et_impera' => 'pagini/divide_et_impera.php',
    'sort_bubble' => 'pagini/sort_bubble.php',
    'sort_selection' => 'pagini/sort_selection.php',
    'sort_insertion' => 'pagini/sort_insertion.php',
    'sort_quick' => 'pagini/sort_quick.php',
    'sort_merge' => 'pagini/sort_merge.php',
    'sort_counting' => 'pagini/sort_counting.php',
    'comparatii_sortare' => 'pagini/comparatii_sortare.php',
    'metode' => 'PHP/lista_metode.php',
    'compilator' => 'PHP/compilator_online.php',
    'metoda_form' => 'PHP/metoda_form.php',
    'metoda' => 'PHP/metoda.php', // Pagină adăugată pentru detalii metodă
    'login' => 'PHP/login.php',
    'logout' => 'PHP/logout.php',
    'grile' => 'PHP/grile.php',
    'grila_interactiva' => 'PHP/grila_interactiva.php',
    'register' => 'PHP/register.php',
    'lista_exercitii' => 'PHP/lista_exercitii.php',
    'changelog' => 'pagini/changelog.php'
];

// Ce pagină încărcăm implicit?
// - utilizator neautentificat: bun_venit
// - utilizator autentificat: acasa (dashboard)
$pagina_implicita = !empty($_SESSION['user_id']) ? 'acasa' : 'bun_venit';
$pagina_curenta = isset($_GET['page']) && isset($pagini_permise[$_GET['page']]) ? $_GET['page'] : $pagina_implicita;
$fisier_de_incarcat = $pagini_permise[$pagina_curenta];

// Paginile pe care nu afișăm widget-ul flotant AI
$pagini_fara_ai_widget = ['bun_venit', 'login', 'register', 'logout'];
$afiseaza_ai_widget = !in_array($pagina_curenta, $pagini_fara_ai_widget, true);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal C++ – Metode de sortare</title>

    <!-- Font Inter de la Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/modern_vars.css">
    <link rel="stylesheet" href="stil.css">
    <link rel="stylesheet" href="CSS/dashboard_modern.css">
    <?php if ($pagina_curenta === 'bun_venit'): ?>
        <link rel="stylesheet" href="CSS/bun_venit.css">
    <?php endif; ?>
    <style>
        /* Fix: Anulează pointer-events din stil.css pentru a face butoanele clickabile */
        nav, 
        nav * {
            pointer-events: auto !important;
        }
        nav a, 
        nav button,
        .btn,
        button {
            pointer-events: auto !important;
            cursor: pointer !important;
        }
        /* Asigură-te că linkurile sunt clickabile */
        a[href] {
            pointer-events: auto !important;
        }
        /* Dezactiveaza pointer-events: none universal */
        *:not(.ai-widget-panel):not(.ai-widget) {
            pointer-events: auto !important;
        }
        /* Reactivează pointer-events: none doar pentru ai-widget când e închis */
        .ai-widget-panel {
            pointer-events: none;
        }
        .ai-widget.open .ai-widget-panel {
            pointer-events: auto;
        }
    </style>
</head>
<body data-theme="dark" style="background: var(--color-bg); color: var(--color-fg); font-family: var(--font-sans); margin: 0; min-height: 100vh; display: flex; flex-direction: column;">

<nav class="site-nav">
    <!-- LOGO -->
    <div class="site-nav__brand">
        <svg class="icon icon--lg" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/></svg>
        <div style="display: flex; flex-direction: column; gap: 0;">
            <span style="font-weight: 700; font-size: var(--text-lg); letter-spacing: var(--tracking-tight); color: var(--color-fg);">SImp <span class="site-nav__brand-accent">Portal</span></span>
            <span style="font-size: var(--text-xs); color: var(--color-fg-muted); letter-spacing: var(--tracking-wide); text-transform: uppercase;">C++ Learning Hub</span>
        </div>
    </div>
    
    <!-- MENU + THEME TOGGLE -->
    <ul class="site-nav__menu">
        <li><a href="index.php?page=bun_venit" class="btn btn--quiet btn--sm" style="font-size: var(--text-sm);">Bun venit</a></li>
        <li><a href="index.php?page=acasa" class="btn btn--quiet btn--sm" style="font-size: var(--text-sm);">Acasă</a></li>
        <li><a href="index.php?page=algoritmi" class="btn btn--quiet btn--sm" style="font-size: var(--text-sm);">Algoritmi</a></li>
        <li><a href="index.php?page=comparatii_sortare" class="btn btn--quiet btn--sm" style="font-size: var(--text-sm);">Comparații</a></li>
        <li><a href="index.php?page=profesor_ai" class="btn btn--quiet btn--sm" style="font-size: var(--text-sm);">Profesor AI</a></li>
        <li><a href="index.php?page=lista_exercitii" class="btn btn--quiet btn--sm" style="font-size: var(--text-sm);">Exerciții</a></li>
        <li><a href="index.php?page=compilator" class="btn btn--quiet btn--sm" style="font-size: var(--text-sm);">Compilator</a></li>
        <li><a href="index.php?page=grile" class="btn btn--quiet btn--sm" style="font-size: var(--text-sm);">Grile</a></li>
        
        <!-- THEME TOGGLE -->
        <li style="margin-left: var(--space-2); border-left: 1px solid var(--color-border); padding-left: var(--space-2);">
            <button id="theme-toggle" class="btn btn--ghost btn--sm" title="Toggle dark/light mode" style="display: flex; align-items: center; gap: var(--space-1);">
                <svg id="theme-icon-sun" class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg id="theme-icon-moon" class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>
        </li>
        
        <!-- AUTH -->
        <?php if (!empty($_SESSION['user_id'])): ?>
            <li style="margin-left: var(--space-2); border-left: 1px solid var(--color-border); padding-left: var(--space-2);"><span class="badge badge--soft"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span></li>
            <li>
                <form method="post" action="PHP/logout.php" style="display:inline; margin: 0;">
                    <?php csrf_field(); ?>
                    <button type="submit" class="btn btn--ghost btn--sm">Logout</button>
                </form>
            </li>
        <?php else: ?>
            <li style="margin-left: var(--space-2);"><a href="index.php?page=login" class="btn btn--ghost btn--sm">Login</a></li>
            <li><a href="index.php?page=register" class="btn btn--primary btn--sm">Cont Nou</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main style="flex: 1; padding: var(--space-6); max-width: var(--measure-wide); margin: 0 auto; width: 100%;">
    <?php
    display_flash();
    if (isset($_GET['msg']) && $_GET['msg'] === 'logout_success') {
        echo '<div class="alert alert--success" style="margin-bottom: var(--space-4); padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-success-soft); color: var(--color-success); border: 1px solid var(--color-success);">Ați fost delogat cu succes!</div>';
    }

    if (file_exists($fisier_de_incarcat)) {
        include $fisier_de_incarcat;
    } else {
        echo '
        <div data-component="dashboard-modern">
            <div class="dash__guard" style="max-width: 560px; padding: var(--space-12);">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 64px; height: 64px; color: var(--color-fg-subtle); margin: 0 auto var(--space-5);">
                    <path d="M9 10a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                    <path d="M15 10a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                    <path d="M9 17c-1.5-2.5 1.5-2.5 3-2.5s4.5 0 3 2.5"/>
                    <circle cx="12" cy="12" r="10"/>
                </svg>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-3);">Pagina nu a fost găsită</h2>
                <p style="color: var(--color-fg-muted); margin-bottom: var(--space-6);">URL-ul cerut nu există în portal. Verifică linkul sau revino acasă.</p>
                <div style="display: flex; gap: var(--space-3); justify-content: center;">
                    <a href="index.php" class="btn btn--primary">
                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Acasă
                    </a>
                    <a href="javascript:history.back()" class="btn btn--ghost">Înapoi</a>
                </div>
            </div>
        </div>';
    }
    ?>
</main>

<footer class="site-footer">
    <div style="max-width: var(--measure-prose); margin: 0 auto; display: flex; flex-direction: column; gap: var(--space-4);">
        <p style="margin: 0; color: var(--color-fg-muted);">&copy; <?php echo date('Y'); ?> <strong>SImp Portal</strong> &mdash; Mediul tău modern de învățare C++.</p>
        <div style="display: flex; justify-content: center; gap: var(--space-4); color: var(--color-fg-subtle); font-size: 10px; text-transform: uppercase; letter-spacing: var(--tracking-widest);">
            <span>Engineering Design</span>
            <span style="color: var(--color-border-strong);">|</span>
            <span>Performance Optimized</span>
            <span style="color: var(--color-border-strong);">|</span>
            <span>Interactive Labs</span>
        </div>
        <div style="text-align: center; margin-top: var(--space-2);">
            <a href="index.php?page=changelog" class="link-arrow" style="font-size: var(--text-xs); color: var(--color-primary); text-decoration: none;">Changelog →</a>
        </div>
    </div>
</footer>

<?php if ($afiseaza_ai_widget): ?>
<div id="ai-widget" class="ai-widget">
    <button id="ai-widget-toggle" class="ai-widget-toggle" type="button" aria-label="Deschide chat Profesor AI" aria-expanded="false" style="position: relative;">
        <svg class="ai-widget-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
            <path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/>
        </svg>
        <span id="ai-widget-status-dot" style="position: absolute; bottom: 4px; right: 4px; width: 10px; height: 10px; border-radius: 50%; background: var(--color-fg-disabled); border: 2px solid var(--color-surface-1); transition: background 0.3s;"></span>
        <span id="ai-widget-badge" class="ai-widget-badge" hidden>0</span>
    </button>

    <section id="ai-widget-panel" class="ai-widget-panel" aria-label="Chat Profesor AI">
        <header class="ai-widget-header">
            <div>
                <h3>Profesor AI C++</h3>
                <p>Îți explică pas cu pas, în română</p>
            </div>
            <button id="ai-widget-close" class="ai-widget-close" type="button" aria-label="Închide chat">×</button>
        </header>

        <div id="ai-widget-messages" class="ai-widget-messages" aria-live="polite"></div>

        <form id="ai-widget-form" class="ai-widget-form" autocomplete="off">
            <textarea
                id="ai-widget-input"
                rows="2"
                maxlength="1200"
                placeholder="Scrie întrebarea ta aici..."
                required
            ></textarea>
            <button type="submit" class="btn btn--primary btn-primary">
                Trimite
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
                </svg>
            </button>
        </form>
    </section>
</div>

<script src="JS/ai_widget.js" defer></script>
<?php endif; ?>
</body>
<script>
// THEME TOGGLE
(function() {
  const button = document.getElementById('theme-toggle');
  const sunIcon = document.getElementById('theme-icon-sun');
  const moonIcon = document.getElementById('theme-icon-moon');
  const html = document.documentElement;

  function getTheme() {
    return localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  }

  function setTheme(theme) {
    localStorage.setItem('theme', theme);
    html.setAttribute('data-theme', theme);
    updateIcons(theme);
  }

  function updateIcons(theme) {
    if (theme === 'dark') {
      sunIcon.style.display = 'none';
      moonIcon.style.display = 'block';
    } else {
      sunIcon.style.display = 'block';
      moonIcon.style.display = 'none';
    }
  }

  // Initialize on page load
  const initialTheme = getTheme();
  setTheme(initialTheme);

  // Toggle on button click
  button.addEventListener('click', () => {
    const current = html.getAttribute('data-theme') || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';
    setTheme(next);
  });
})();
</script>
</html>
