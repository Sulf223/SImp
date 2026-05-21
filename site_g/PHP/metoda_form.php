<?php
require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/auth.php';
require_role('admin');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$nume = $categorie = $complexitate = $descriere = $fisier_cpp = "";

if ($id > 0) {
    $sql = "SELECT nume, categorie, complexitate, descriere, fisier_cpp FROM metode WHERE id_metoda = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $rezultat = $stmt->get_result();
        if ($row = $rezultat->fetch_assoc()) {
            $nume = $row['nume'];
            $categorie = $row['categorie'];
            $complexitate = $row['complexitate'];
            $descriere = $row['descriere'];
            $fisier_cpp = $row['fisier_cpp'];
        }
        $stmt->close();
    }
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            Administrare
        </div>
        <h2 class="dash__title"><?php echo $id > 0 ? "Editare <span class='dash__title-accent'>Metodă</span>" : "Adăugare <span class='dash__title-accent'>Metodă Nouă</span>"; ?></h2>
        <p class="dash__lede">Completează detaliile pentru a actualiza baza de date cu algoritmi.</p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Formular Metodă</h3>
                <a href="index.php?page=metode" class="btn btn--ghost btn--sm">Înapoi la listă</a>
            </div>
            
            <form method="post" action="PHP/metoda_salveaza.php" style="display: flex; flex-direction: column; gap: var(--space-4);">
                <?php csrf_field(); ?>
                <input type="hidden" name="id_metoda" value="<?php echo $id; ?>">

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-4);">
                    <div class="form-group">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 0.5rem;">Nume metodă:</label>
                        <input type="text" name="nume" id="nume" value="<?php echo htmlspecialchars($nume); ?>" required style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg);">
                    </div>
                    <div class="form-group">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 0.5rem;">Categorie:</label>
                        <input type="text" name="categorie" id="categorie" value="<?php echo htmlspecialchars($categorie); ?>" style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg);">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-4);">
                    <div class="form-group">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 0.5rem;">Complexitate:</label>
                        <input type="text" name="complexitate" id="complexitate" value="<?php echo htmlspecialchars($complexitate); ?>" style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg);">
                    </div>
                    <div class="form-group">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 0.5rem;">Fișier C++ (în /CPP):</label>
                        <input type="text" name="fisier_cpp" id="fisier_cpp" value="<?php echo htmlspecialchars($fisier_cpp); ?>" style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg);">
                    </div>
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 0.5rem;">Descriere:</label>
                    <textarea name="descriere" id="descriere" rows="5" style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg);"><?php echo htmlspecialchars($descriere); ?></textarea>
                </div>

                <div class="card__actions">
                    <button type="submit" class="btn btn--primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Salvează Metoda
                    </button>
                </div>
            </form>
        </div>

        <div class="card bento__card--accent">
            <div class="card__head">
                <h3 class="card__title-sm">Indicații</h3>
            </div>
            <div class="card__body">
                <p>Asigură-te că fișierul C++ există în folderul <code>site_g/CPP/</code> pentru ca vizualizatorul și compilatorul să funcționeze.</p>
                <p style="margin-top: 1rem; color: var(--color-fg-muted); font-size: var(--text-sm);">Numele fișierului trebuie să includă extensia (ex: <code>bubblesort.cpp</code>).</p>
            </div>
        </div>
    </div>
</div>
