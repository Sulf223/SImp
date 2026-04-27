<?php
$cod_sursa = '';
$run_id = isset($_GET['run_id']) ? (int)$_GET['run_id'] : 0;

if ($run_id > 0) {
    include_once 'conexiune.php';
    $sql = "SELECT fisier_cpp FROM metode WHERE id_metoda = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $run_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $fisier_cpp = $row['fisier_cpp'];
            if (!empty($fisier_cpp)) {
                $nume_fisier_sigur = basename($fisier_cpp);
                $file_path = '../CPP/' . $nume_fisier_sigur;
                if (file_exists($file_path)) {
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
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            Instrumente
        </div>
        <h2 class="dash__title">Compilator <span class="dash__title-accent">Online</span></h2>
        <p class="dash__lede">
            Editor C++ profesional cu execuție instant. Scrie cod, apasă <strong>Run</strong> și vezi rezultatul imediat!
        </p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero" style="padding: 0; overflow: hidden; min-height: 600px;">
            <iframe 
                src="https://onecompiler.com/embed/cpp?hideLanguageSelection=true&hideNew=true&hideNewFileOption=true&availableLanguages=true&hideTitle=true" 
                width="100%" 
                height="600" 
                loading="lazy"
                frameborder="0"
                style="display: block; border: none;">
            </iframe>
        </div>

        <div class="card bento__card--accent">
            <div class="card__head">
                <h3 class="card__title-sm">Instrucțiuni</h3>
            </div>
            <div class="card__body">
                <ol style="padding-left: 1.2rem; display: flex; flex-direction: column; gap: 0.8rem; color: var(--color-fg-muted); font-size: var(--text-sm);">
                    <li>Scrie sau lipește codul C++ în editor.</li>
                    <li>Apasă butonul verde <strong>"Run"</strong>.</li>
                    <li>Output-ul apare instant în panoul de jos.</li>
                    <li>Pentru input (cin), folosește zona <strong>"stdin"</strong> înainte să apeși Run.</li>
                </ol>
            </div>
        </div>

        <?php if (!empty($cod_sursa) && $run_id > 0): ?>
        <div class="card bento__card--timeline">
            <div class="card__head">
                <h3 class="card__title">Cod Sursă Metodă</h3>
                <button onclick="copySourceCode()" class="btn btn--primary btn--sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    Copiază Codul
                </button>
            </div>
            <div class="card__body">
                <pre style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md); overflow-x: auto; font-family: monospace; font-size: 0.85rem;"><code><?php echo htmlspecialchars($cod_sursa); ?></code></pre>
            </div>
        </div>
        
        <script>
        function copySourceCode() {
            const code = <?php echo json_encode($cod_sursa); ?>;
            navigator.clipboard.writeText(code).then(() => {
                const btn = event.currentTarget;
                const original = btn.innerHTML;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><polyline points="20 6 9 17 4 12"/></svg> Copiat!';
                setTimeout(() => {
                    btn.innerHTML = original;
                }, 2000);
            });
        }
        </script>
        <?php endif; ?>
    </div>
</div>
