<?php
// index.php - Acum este fișierul principal de layout (template)
if (session_status() === PHP_SESSION_NONE) {
    // Configurăm parametrii securizați pentru cookie-urile de sesiune
    session_set_cookie_params([
        'lifetime' => 0, // Cookie expiră la închiderea browser-ului
        'path' => '/',
        'domain' => '', // Autodetectează domeniul
        'secure' => false, // Schimbă în true dacă folosești HTTPS
        'httponly' => true, // Previne accesul prin JavaScript (protecție XSS)
        'samesite' => 'Strict' // Protecție CSRF suplimentară
    ]);
    session_start();
}

// Includem helper-ele (Flash messages, CSRF)
require_once 'PHP/helpers.php';

// Paginile permise pentru a preveni atacuri de tip LFI (Local File Inclusion)
// Am adăugat și o cale către 'pagini/' pentru a păstra structura curată
$pagini_permise = [
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
    'metode' => 'php/lista_metode.php',
    'compilator' => 'php/compilator_online.php',
    'metoda_form' => 'php/metoda_form.php',
    'metoda' => 'php/metoda.php', // Pagină adăugată pentru detalii metodă
    'login' => 'php/login.php',
    'logout' => 'php/logout.php',
    'grile' => 'php/grile.php',
    'grila_interactiva' => 'php/grila_interactiva.php',
    'register' => 'php/register.php',
    'lista_exercitii' => 'PHP/lista_exercitii.php'
];

// Ce pagină încărcăm? Implicit, 'acasa'.
$pagina_curenta = isset($_GET['page']) && isset($pagini_permise[$_GET['page']]) ? $_GET['page'] : 'acasa';
$fisier_de_incarcat = $pagini_permise[$pagina_curenta];

// Paginile pe care nu afișăm widget-ul flotant AI
$pagini_fara_ai_widget = ['login', 'register', 'logout'];
$afiseaza_ai_widget = !in_array($pagina_curenta, $pagini_fara_ai_widget, true);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Portal C++ – Metode de sortare</title>
    <link rel="stylesheet" href="stil.css">

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
                <form method="post" action="php/logout.php" style="display:inline;">
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

<script>
(() => {
    const widget = document.getElementById('ai-widget');
    const toggleBtn = document.getElementById('ai-widget-toggle');
    const badgeEl = document.getElementById('ai-widget-badge');
    const panel = document.getElementById('ai-widget-panel');
    const closeBtn = document.getElementById('ai-widget-close');
    const messagesEl = document.getElementById('ai-widget-messages');
    const form = document.getElementById('ai-widget-form');
    const input = document.getElementById('ai-widget-input');

    if (!widget || !toggleBtn || !panel || !messagesEl || !form || !input) {
        return;
    }

    const STORAGE_KEY = 'ai_widget_history_v1';
    let history = [];
    let unreadCount = 0;
    let typingEl = null;

    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function saveHistory() {
        try {
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(history.slice(-20)));
        } catch (_) {}
    }

    function loadHistory() {
        try {
            const raw = sessionStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            const parsed = JSON.parse(raw);
            if (Array.isArray(parsed)) {
                history = parsed.filter(item => item && item.role && item.text);
            }
        } catch (_) {}
    }

    function addMessage(role, text) {
        const msg = document.createElement('div');
        msg.className = `ai-widget-msg ${role}`;

        const who = role === 'user' ? 'Tu' : 'Profesor AI';
        msg.innerHTML = `<strong>${who}</strong><p>${escapeHtml(text).replace(/\n/g, '<br>')}</p>`;
        messagesEl.appendChild(msg);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function showTypingIndicator() {
        if (typingEl) return;

        typingEl = document.createElement('div');
        typingEl.className = 'ai-widget-msg assistant ai-widget-typing';
        typingEl.innerHTML = `
            <strong>Profesor AI</strong>
            <div class="ai-typing-dots" aria-label="Profesor AI scrie">
                <span></span><span></span><span></span>
            </div>
        `;

        messagesEl.appendChild(typingEl);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function hideTypingIndicator() {
        if (!typingEl) return;
        typingEl.remove();
        typingEl = null;
    }

    function updateUnreadBadge() {
        if (!badgeEl) return;
        if (unreadCount <= 0) {
            badgeEl.hidden = true;
            badgeEl.textContent = '0';
            return;
        }

        badgeEl.hidden = false;
        badgeEl.textContent = unreadCount > 99 ? '99+' : String(unreadCount);
    }

    function markAsRead() {
        unreadCount = 0;
        updateUnreadBadge();
    }

    function renderHistory() {
        messagesEl.innerHTML = '';
        if (history.length === 0) {
            const welcome = 'Salut! Sunt profesorul tău AI de C++. Spune-mi ce nu ai înțeles și te ghidez pas cu pas.';
            addMessage('assistant', welcome);
            history.push({ role: 'assistant', text: welcome });
            saveHistory();
            return;
        }

        history.forEach(item => addMessage(item.role, item.text));
    }

    function openPanel() {
        widget.classList.add('open');
        toggleBtn.setAttribute('aria-expanded', 'true');
        markAsRead();
        input.focus();
    }

    function closePanel() {
        widget.classList.remove('open');
        toggleBtn.setAttribute('aria-expanded', 'false');
    }

    function setLoading(isLoading) {
        form.querySelector('button[type="submit"]').disabled = isLoading;
        input.disabled = isLoading;
    }

    toggleBtn.addEventListener('click', () => {
        if (widget.classList.contains('open')) {
            closePanel();
            return;
        }
        openPanel();
    });
    if (closeBtn) closeBtn.addEventListener('click', closePanel);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closePanel();
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        addMessage('user', text);
        history.push({ role: 'user', text });
        saveHistory();
        input.value = '';
        setLoading(true);
        showTypingIndicator();

        try {
            const response = await fetch('PHP/profesor_ai_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: text,
                    history: history.slice(-10)
                })
            });

            const data = await response.json();
            if (!response.ok || !data.ok) {
                throw new Error(data.error || 'Eroare la comunicarea cu Profesor AI.');
            }

            hideTypingIndicator();
            addMessage('assistant', data.reply);
            history.push({ role: 'assistant', text: data.reply });
            saveHistory();

            if (!widget.classList.contains('open')) {
                unreadCount += 1;
                updateUnreadBadge();
            }
        } catch (error) {
            hideTypingIndicator();
            const errText = `Eroare: ${error.message}`;
            addMessage('assistant', errText);
            history.push({ role: 'assistant', text: errText });
            saveHistory();

            if (!widget.classList.contains('open')) {
                unreadCount += 1;
                updateUnreadBadge();
            }
        } finally {
            setLoading(false);
            input.focus();
        }
    });

    loadHistory();
    renderHistory();
    updateUnreadBadge();
})();
</script>
<?php endif; ?>
</body>
</html>
