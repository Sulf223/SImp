<?php
require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/auth.php';

$id_metoda = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$metoda = null;
$metoda_error = '';

if ($id_metoda <= 0) {
    $metoda_error = 'ID metodă invalid.';
} else {
    $sql_metoda = "SELECT nume, categorie, complexitate, descriere, fisier_cpp FROM metode WHERE id_metoda = ?";
    if ($stmt_metoda = $con->prepare($sql_metoda)) {
        $stmt_metoda->bind_param("i", $id_metoda);
        $stmt_metoda->execute();
        $rezultat_metoda = $stmt_metoda->get_result();
        if ($row = $rezultat_metoda->fetch_assoc()) { $metoda = $row; }
        $stmt_metoda->close();
    }

    if ($metoda === null) {
        $metoda_error = 'Metoda nu a fost găsită.';
    }
}

if ($metoda_error !== '') {
    ?>
    <div data-component="dashboard-modern">
        <div class="dash__guard" style="max-width: 560px; padding: var(--space-12);">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 64px; height: 64px; color: var(--color-fg-subtle); margin: 0 auto var(--space-5);">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v5"/>
                <path d="M12 16h.01"/>
            </svg>
            <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-3);">Metodă indisponibilă</h2>
            <p style="color: var(--color-fg-muted); margin-bottom: var(--space-6);"><?= htmlspecialchars($metoda_error) ?></p>
            <a href="index.php?page=metode" class="btn btn--primary">Înapoi la metode</a>
        </div>
    </div>
    <?php
    return;
}

$cod_cpp = "";
if (!empty($metoda['fisier_cpp'])) {
    $fisier_cpp_path = '../CPP/' . $metoda['fisier_cpp'];
    
    // Securitate: Symbolic link check + Path Traversal (P1)
    $realPath = realpath($fisier_cpp_path);
    $cppDir = realpath(__DIR__ . '/../CPP');
    
    if ($realPath && strpos($realPath, $cppDir) === 0 && file_exists($fisier_cpp_path)) {
        $cod_cpp = file_get_contents($fisier_cpp_path);
    } else {
        $cod_cpp = "// Fișierul C++ sursă '" . htmlspecialchars($metoda['fisier_cpp']) . "' este invalid sau nu a fost găsit.";
    }
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M6.5 18H20"/>
            </svg>
            Detalii Metodă
        </span>
        <h1 class="dash__title"><?php echo htmlspecialchars($metoda['nume']); ?> <span class="dash__title-accent">Algoritm</span></h1>
        <div style="display: flex; gap: var(--space-2); margin-top: var(--space-3);">
            <span class="badge badge--soft"><?php echo htmlspecialchars($metoda['categorie']); ?></span>
            <span class="badge badge--soft" style="font-family: var(--font-mono);"><?php echo htmlspecialchars($metoda['complexitate']); ?></span>
        </div>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- EXPLANATION -->
        <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    Descriere și Explicație
                </span>
            </div>
            <div class="prose" style="font-size: var(--text-sm);">
                <?php echo nl2br(htmlspecialchars($metoda['descriere'])); ?>
            </div>
            <?php if (is_admin()): ?>
            <div class="card__actions" style="margin-top: auto; padding-top: var(--space-6);">
                <a href="index.php?page=metoda_form&id=<?php echo $id_metoda; ?>" class="btn btn--ghost btn--sm">Editează</a>
                <form method="post" action="PHP/metoda_sterge.php" style="display: inline;" onsubmit="return confirm('Ești sigur că vrei să ștergi această metodă?');">
                    <?php csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo $id_metoda; ?>">
                    <button type="submit" class="btn btn--quiet btn--sm" style="color: var(--color-danger);">Șterge</button>
                </form>
            </div>
            <?php endif; ?>
        </article>

        <!-- CODE -->
        <article class="card bento__card--accent" style="border: 1px solid var(--color-primary-soft); background: var(--color-surface-2); position: relative; overflow: hidden;">
             <div class="card__head">
                <span class="card__eyebrow" style="color: var(--color-primary);">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/>
                    </svg>
                    Cod Sursă C++
                </span>
                <?php if (!empty($cod_cpp)): ?>
                <button onclick="copyCode()" id="copy-btn" class="btn btn--quiet btn--sm" aria-label="Copiază codul C++">
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                    </svg>
                </button>
                <?php endif; ?>
            </div>
            <div class="card__body" style="padding: 0;">
                <?php if (!empty($cod_cpp)): ?>
                <pre style="margin: 0; background: transparent; padding: var(--space-4); overflow-x: auto; font-family: var(--font-mono); font-size: var(--text-xs); line-height: 1.6; color: var(--color-fg-muted); max-height: 400px;"><code><?php echo htmlspecialchars($cod_cpp); ?></code></pre>
                <?php else: ?>
                <div style="padding: var(--space-6); text-align: center; color: var(--color-fg-subtle);">Sursa indisponibilă</div>
                <?php endif; ?>
            </div>
        </article>

        <!-- VISUALIZER -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12h4l3 9L9 3l-3 9H2"/>
                    </svg>
                    Vizualizator Interactiv
                </span>
            </div>
            <div class="card__body" style="background: var(--color-surface-2); border-radius: var(--radius-lg); overflow: hidden;">
                <div id="sorting-visualizer" class="visualizer-container" data-algorithm="<?php echo htmlspecialchars(strtolower($metoda['nume'])); ?>" style="min-height: 450px;"></div>
            </div>
        </article>
    </div>
</div>

<script src="JS/visualizer.js?v=20260521-audit-polish" nonce="<?= $nonce ?? '' ?>"></script>
<script nonce="<?= $nonce ?? '' ?>"> // FIX [M2]: Adăugare nonce pentru CSP
function copyCode() {
    const code = <?php echo json_encode($cod_cpp); ?>;
    const btn = document.getElementById('copy-btn');
    navigator.clipboard.writeText(code).then(() => {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
        setTimeout(() => { btn.innerHTML = originalHtml; }, 2000);
    });
}
</script>
