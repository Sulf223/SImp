<?php
$cod_sursa = '';
$run_id = isset($_GET['run_id']) ? (int)$_GET['run_id'] : 0;

if ($run_id > 0) {
    require_once __DIR__ . '/conexiune.php';
    $sql = "SELECT fisier_cpp FROM metode WHERE id_metoda = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $run_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $fisier_cpp = $row['fisier_cpp'];
            if (!empty($fisier_cpp)) {
                // FIX [M5]: Protecție Path Traversal și validare cale fișier C++ (utilizând pattern din metoda.php)
                $file_path = __DIR__ . '/../CPP/' . $fisier_cpp;
                $realPath = realpath($file_path);
                $cppDir = realpath(__DIR__ . '/../CPP');

                if ($realPath && strpos($realPath, $cppDir) === 0 && file_exists($file_path)) {
                    $cod_sursa = file_get_contents($file_path);
                }
            }
        }
        $stmt->close();
    }
}

if (empty($cod_sursa)) {
    $cod_sursa = "#include <iostream>\nusing namespace std;\n\nint main() {\n    cout << \"Hello, World!\" << endl;\n    return 0;\n}";
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/>
            </svg>
            Instrumente
        </span>
        <h1 class="dash__title">Compilator <span class="dash__title-accent">Online</span></h1>
        <p class="dash__lede">
            Editor C++ profesional cu execuție instant. Scrie cod, apasă <strong>Run</strong> și vezi rezultatul imediat într-un mediu sigur.
        </p>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- COMPILER: Main hero card -->
        <article class="card bento__card--hero" style="padding: 0; overflow: hidden; min-height: 650px; border: 1px solid var(--color-border);">
            <iframe 
                src="https://onecompiler.com/embed/cpp?hideLanguageSelection=true&hideNew=true&hideNewFileOption=true&availableLanguages=true&hideTitle=true" 
                width="100%" 
                height="650" 
                loading="lazy"
                frameborder="0"
                sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
                style="display: block; border: none; background: #1e1e1e;">
            </iframe>
        </article>

        <!-- SIDEBAR: Instructions -->
        <article class="card bento__card--accent" style="border: 1px solid var(--color-accent-soft); background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(6, 182, 212, 0.02) 100%);">
            <div class="card__head">
                <span class="card__eyebrow" style="color: var(--color-accent);">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/>
                    </svg>
                    Instrucțiuni
                </span>
            </div>
            <div class="card__body">
                <ol style="padding-left: 1.2rem; display: flex; flex-direction: column; gap: 1rem; color: var(--color-fg-muted); font-size: var(--text-sm);">
                    <li>Scrie sau lipește codul C++ în editorul de mai sus.</li>
                    <li>Apasă butonul verde <strong>"Run"</strong> pentru execuție.</li>
                    <li>Output-ul apare instant în panoul inferior al editorului.</li>
                    <li>Dacă ai citiri cu <code>cin</code>, folosește zona <strong>"stdin"</strong>.</li>
                </ol>
            </div>
        </article>

        <!-- FEATURE [F3]: AI Code Feedback UI -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                    </svg>
                    Feedback Profesor AI
                </span>
            </div>
            <div class="card__body" style="display: flex; flex-direction: column; gap: var(--space-4);">
                <p style="color: var(--color-fg-muted); font-size: var(--text-sm);">Lipește codul pe care tocmai l-ai rulat pentru o analiză rapidă a stilului, erorilor și complexității.</p>
                <textarea id="ai-feedback-code" rows="6" placeholder="Lipește codul C++ aici..." style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg); font-family: var(--font-mono); font-size: var(--text-sm); outline: none;"></textarea>
                <div>
                    <button id="btn-ask-feedback" class="btn btn--primary">
                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 20h.01"/><path d="M12 2v14"/><path d="m15 13-3 3-3-3"/></svg>
                        Cere feedback AI
                    </button>
                </div>
                <div id="ai-feedback-response" style="margin-top: var(--space-2);"></div>
            </div>
        </article>
        <script src="JS/ai_code_feedback.js" defer nonce="<?= $nonce ?>"></script>

        <!-- SOURCE CODE: Timeline/Full width if source exists -->

        <?php if (!empty($cod_sursa) && $run_id > 0): ?>
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/>
                    </svg>
                    Cod Sursă Referință
                </span>
                <button type="button" id="copy-btn" class="btn btn--primary btn--sm">
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                    </svg>
                    Copiază Codul
                </button>
            </div>
            <div class="card__body">
                <pre style="margin: 0; background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md); overflow-x: auto; font-family: var(--font-mono); font-size: var(--text-sm); line-height: 1.6; color: var(--color-fg-muted);"><code><?php echo htmlspecialchars($cod_sursa); ?></code></pre>
            </div>
        </article>
        
        <script nonce="<?= $nonce ?>"> // FIX [M2]: Adăugare nonce pentru CSP
        function copySourceCode() {
            const code = <?php echo json_encode($cod_sursa); ?>;
            const btn = document.getElementById('copy-btn');
            if (!btn) return;
            navigator.clipboard.writeText(code).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Copiat!';
                btn.style.background = 'var(--color-success)';
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.style.background = '';
                }, 2000);
            });
        }
        document.getElementById('copy-btn')?.addEventListener('click', copySourceCode);
        </script>
        <?php endif; ?>
    </div>
</div>
