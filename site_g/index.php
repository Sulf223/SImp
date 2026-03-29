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
    'metode' => 'PHP/lista_metode.php',
    'compilator' => 'PHP/compilator_online.php',
    'metoda_form' => 'PHP/metoda_form.php',
    'metoda' => 'PHP/metoda.php', // Pagină adăugată pentru detalii metodă
    'login' => 'PHP/login.php',
    'logout' => 'PHP/logout.php',
    'grile' => 'PHP/grile.php',
    'grila_interactiva' => 'PHP/grila_interactiva.php',
    'register' => 'PHP/register.php',
    'lista_exercitii' => 'PHP/lista_exercitii.php'
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
    <link rel="stylesheet" href="stil.css">
    <?php if ($pagina_curenta === 'bun_venit'): ?>
        <link rel="stylesheet" href="CSS/bun_venit.css">
    <?php endif; ?>

    <!-- (opțional) font Poppins de la Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <h1>Portal C++ – Metode de sortare</h1>
    <p>Un mini-laborator online pentru studenți: cod, explicații și exerciții interactive</p>
</header>

<nav>
    <ul>
        <!-- Link-urile au fost actualizate pentru a folosi noul sistem de paginare -->
        <li><a href="index.php?page=bun_venit">Bun venit</a></li>
        <li><a href="index.php?page=acasa">Acasă</a></li>
        <li><a href="index.php?page=algoritmi">Algoritmi</a></li>
        <li><a href="index.php?page=profesor_ai">Profesor AI</a></li>
        <li><a href="index.php?page=lista_exercitii">Exerciții</a></li>
        <li><a href="index.php?page=compilator">Compilator C++ online</a></li>
        <li><a href="index.php?page=grile">Grile Interactive</a></li>
        <!-- Am eliminat link-ul către frames.html -->
        <?php if (!empty($_SESSION['user_id'])): ?>
            <li style="margin-left:auto"><a href="#">Utilizator: <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></a></li>
            <li>
                <form method="post" action="PHP/logout.php" style="display:inline;">
                    <?php csrf_field(); ?>
                    <button type="submit" class="btn-logout" style="background:none;border:none;color:inherit;cursor:pointer;font:inherit;padding:8px 16px;">Logout</button>
                </form>
            </li>
        <?php else: ?>
            <li style="margin-left:auto"><a href="index.php?page=register">Creare cont</a></li>
            <li><a href="index.php?page=login">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    <?php
    // Afișăm mesajele flash (erori, succes)
    display_flash();

    // Afișează un mesaj de succes la delogare
    if (isset($_GET['msg']) && $_GET['msg'] === 'logout_success') {
        echo '<div class="alert alert-success">Ați fost delogat cu succes!</div>';
    }

    // Aici se încarcă dinamic conținutul paginii cerute
    if (file_exists($fisier_de_incarcat)) {
        include $fisier_de_incarcat;
    } else {
        // Afișează o eroare dacă fișierul nu este găsit
        echo "<h2>Eroare 404</h2>";
        echo "<p>Pagina cerută nu a fost găsită. Verificați URL-ul.</p>";
    }
    ?>
</main>

<footer>
    <p>&copy; 2025 Portal C++ – Metode de sortare</p>
</footer>

<?php if ($afiseaza_ai_widget): ?>
<div id="ai-widget" class="ai-widget">
    <button id="ai-widget-toggle" class="ai-widget-toggle" type="button" aria-label="Deschide chat Profesor AI" aria-expanded="false">
        <span class="ai-widget-icon">AI</span>
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
            <button type="submit" class="btn btn-primary">Trimite</button>
        </form>
    </section>
</div>

<script src="JS/ai_widget.js" defer></script>
<?php endif; ?>
</body>
</html>
