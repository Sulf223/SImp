<?php
// Acest fișier afișează detaliile pentru o singură metodă de sortare.
include "conexiune.php";
include "auth.php"; // Include auth.php for is_admin()

// 1. Preluăm și validăm ID-ul metodei din URL
$id_metoda = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_metoda <= 0) {
    die("ID metodă invalid.");
}

// 2. Preluăm detaliile metodei din baza de date în mod securizat
$metoda = null;
$sql_metoda = "SELECT nume, categorie, complexitate, descriere, fisier_cpp FROM metode WHERE id_metoda = ?";
if ($stmt_metoda = $con->prepare($sql_metoda)) { // Folosim $con
    $stmt_metoda->bind_param("i", $id_metoda);
    $stmt_metoda->execute();
    $rezultat_metoda = $stmt_metoda->get_result();
    if ($row = $rezultat_metoda->fetch_assoc()) {
        $metoda = $row;
    }
    $stmt_metoda->close();
}

// Dacă nu am găsit metoda, afișăm o eroare
if ($metoda === null) {
    die("Metoda nu a fost găsită în baza de date.");
}

// 3. Citirea fișierului C++
$cod_cpp = "";
$fisier_cpp_path = "";
if (!empty($metoda['fisier_cpp'])) {
    $fisier_cpp_path = '../CPP/' . $metoda['fisier_cpp'];
    if (file_exists($fisier_cpp_path)) {
        $cod_cpp = file_get_contents($fisier_cpp_path);
    } else {
        $cod_cpp = "// Fișierul C++ sursă '" . htmlspecialchars($metoda['fisier_cpp']) . "' nu a fost găsit.";
    }
}
?>

<section class="method-hero">
    <div class="method-header">
        <h2><?php echo htmlspecialchars($metoda['nume']); ?></h2>
        <div class="method-badges">
            <span class="badge badge-category"><?php echo htmlspecialchars($metoda['categorie']); ?></span>
            <span class="badge badge-complexity"><?php echo htmlspecialchars($metoda['complexitate']); ?></span>
        </div>
    </div>
    
    <div class="method-description">
        <div class="description-icon">📖</div>
        <div class="description-content">
            <h4>Descriere și Explicație</h4>
            <p><?php echo nl2br(htmlspecialchars($metoda['descriere'])); ?></p>
        </div>
    </div>
</section>

<!-- Secțiune Vizualizare -->
<section class="mt-4">
    <h3>Vizualizare Algoritm</h3>
    <!-- Pasăm numele metodei către JS prin data-algorithm -->
    <div id="sorting-visualizer" 
         class="visualizer-container"
         data-algorithm="<?php echo htmlspecialchars(strtolower($metoda['nume'])); ?>">
        <!-- Canvas-ul va fi generat aici de JS -->
    </div>
    <script src="JS/visualizer.js"></script>
</section>

<section class="code-section">
    <div class="code-header">
        <div class="code-title">
            <span class="code-icon">💻</span>
            <h3>Cod Sursă C++</h3>
        </div>
        <?php if (!empty($cod_cpp)): ?>
        <button onclick="copyCodeToClipboard()" class="copy-code-btn" title="Copiază codul">
            📋 Copiază
        </button>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($cod_cpp)): ?>
        <div class="custom-code-block">
            <pre><code><?php echo htmlspecialchars($cod_cpp); ?></code></pre>
        </div>
        
        <script>
        function copyCodeToClipboard() {
            const codeText = <?php echo json_encode($cod_cpp); ?>;
            navigator.clipboard.writeText(codeText).then(() => {
                const btn = event.target;
                const originalText = btn.innerHTML;
                btn.innerHTML = '✅ Copiat!';
                btn.style.background = '#10b981';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
                }, 2000);
            }).catch(err => {
                alert('Eroare la copiere: ' + err);
            });
        }

        // (Undo) Removed Compiler Explorer integration
        </script>
    <?php else: ?>
        <div class="no-code-message">
            <p>⚠️ Codul C++ pentru această metodă nu este disponibil.</p>
        </div>
    <?php endif; ?>
</section>

<?php if (is_admin()): ?>
<section class="mt-4">
    <h3>Administrare Metodă</h3>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="index.php?page=metoda_form&id=<?php echo $id_metoda; ?>" class="btn btn-ghost">Editează Metoda</a>
        
        <form method="post" action="PHP/metoda_sterge.php" style="display: inline;" onsubmit="return confirm('Sunteți sigur că doriți să ștergeți această metodă?');">
            <?php csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $id_metoda; ?>">
            <button type="submit" class="btn btn-ghost" style="background:none;border:2px solid #667eea;color:#667eea;cursor:pointer;font:inherit;padding:10px 20px;border-radius:12px;">Șterge Metoda</button>
        </form>
    </div>
</section>
<?php endif; ?>
