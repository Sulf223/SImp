<?php
include "conexiune.php";
include "auth.php";

$id_metoda = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_metoda <= 0) { die("ID metodă invalid."); }

$metoda = null;
$sql_metoda = "SELECT nume, categorie, complexitate, descriere, fisier_cpp FROM metode WHERE id_metoda = ?";
if ($stmt_metoda = $con->prepare($sql_metoda)) {
    $stmt_metoda->bind_param("i", $id_metoda);
    $stmt_metoda->execute();
    $rezultat_metoda = $stmt_metoda->get_result();
    if ($row = $rezultat_metoda->fetch_assoc()) { $metoda = $row; }
    $stmt_metoda->close();
}

if ($metoda === null) { die("Metoda nu a fost găsită."); }

$cod_cpp = "";
if (!empty($metoda['fisier_cpp'])) {
    $fisier_cpp_path = '../CPP/' . $metoda['fisier_cpp'];
    if (file_exists($fisier_cpp_path)) {
        $cod_cpp = file_get_contents($fisier_cpp_path);
    } else {
        $cod_cpp = "// Fișierul C++ sursă '" . htmlspecialchars($metoda['fisier_cpp']) . "' nu a fost găsit.";
    }
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/><path d="M8 15h6"/></svg>
            Detalii Metodă
        </div>
        <h2 class="dash__title"><?php echo htmlspecialchars($metoda['nume']); ?> <span class="dash__title-accent">Algoritm</span></h2>
        <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
            <span class="badge badge--soft"><?php echo htmlspecialchars($metoda['categorie']); ?></span>
            <span class="badge badge--soft"><?php echo htmlspecialchars($metoda['complexitate']); ?></span>
        </div>
    </header>

    <div class="bento">
        <div class="card bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Explicație</h3>
            </div>
            <div class="card__body">
                <p style="font-size: 1rem; line-height: 1.6; color: var(--color-fg-muted);"><?php echo nl2br(htmlspecialchars($metoda['descriere'])); ?></p>
            </div>
            <?php if (is_admin()): ?>
            <div class="card__actions">
                <a href="index.php?page=metoda_form&id=<?php echo $id_metoda; ?>" class="btn btn--ghost btn--sm">Editează</a>
                <form method="post" action="PHP/metoda_sterge.php" style="display: inline;" onsubmit="return confirm('Sunteți sigur că doriți să ștergeți această metodă?');">
                    <?php csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo $id_metoda; ?>">
                    <button type="submit" class="btn btn--quiet btn--sm" style="color: var(--color-error);">Șterge</button>
                </form>
            </div>
            <?php endif; ?>
        </div>

        <div class="card bento__card--accent">
             <div class="card__head">
                <h3 class="card__title-sm">Cod Sursă</h3>
                <?php if (!empty($cod_cpp)): ?>
                <button onclick="copyCodeToClipboard()" class="btn btn--quiet btn--sm" title="Copiază codul">📋</button>
                <?php endif; ?>
            </div>
            <div class="card__body" style="overflow: hidden;">
                <?php if (!empty($cod_cpp)): ?>
                <pre style="background: var(--color-surface-2); padding: 1rem; border-radius: 8px; overflow-x: auto; font-family: monospace; font-size: 0.8rem; max-height: 300px; color: var(--color-fg);"><code><?php echo htmlspecialchars($cod_cpp); ?></code></pre>
                <?php else: ?>
                <p style="color: var(--color-fg-subtle); font-size: 0.9rem;">Codul C++ nu este disponibil.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card bento__card--timeline">
            <div class="card__head">
                <h3 class="card__title">Vizualizator Interactiv</h3>
            </div>
            <div class="card__body">
                <div id="sorting-visualizer" class="visualizer-container" data-algorithm="<?php echo htmlspecialchars(strtolower($metoda['nume'])); ?>" style="min-height: 400px; background: var(--color-surface-2); border-radius: 12px;"></div>
            </div>
        </div>
    </div>
</div>
<script src="JS/visualizer.js"></script>
<script>
function copyCodeToClipboard() {
    const codeText = <?php echo json_encode($cod_cpp); ?>;
    navigator.clipboard.writeText(codeText).then(() => {
        alert('Codul a fost copiat în clipboard!');
    });
}
</script>
