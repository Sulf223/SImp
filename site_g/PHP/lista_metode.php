<?php
require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/auth.php';
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Baza de date
        </div>
        <h2 class="dash__title">Metode de <span class="dash__title-accent">Sortare</span></h2>
        <p class="dash__lede">
            Gestionarea și vizualizarea metodelor de sortare stocate în sistem.
        </p>
    </header>

    <div class="bento">
        <?php if (is_admin()): ?>
        <div class="card card--accent bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Administrare</h3>
                <div class="ai__icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
            </div>
            <div class="card__body">
                <p>Ești logat ca administrator. Poți adăuga, edita sau șterge metode de sortare.</p>
            </div>
            <div class="card__actions">
                <a href="index.php?page=metoda_form" class="btn btn--primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Adaugă metodă nouă
                </a>
            </div>
        </div>
        <?php endif; ?>

        <div class="card bento__card--timeline">
            <div class="card__head">
                <h3 class="card__title">Listă Metode</h3>
            </div>
            <div class="card__body">
                <?php
                // POLISH [P5]: Pagination
                $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
                $limit = 25;
                $offset = ($page - 1) * $limit;

                $total_sql = "SELECT COUNT(*) as count FROM metode";
                $total_res = $con->query($total_sql);
                if (!$total_res) {
                    error_log("lista_metode.php count failed: " . $con->error);
                    $total_rows = 0;
                } else {
                    $total_row = $total_res->fetch_assoc();
                    $total_rows = (int)($total_row['count'] ?? 0);
                    $total_res->free();
                }
                $total_pages = ceil($total_rows / $limit);

                $sql = "SELECT * FROM metode ORDER BY id_metoda LIMIT ? OFFSET ?";
                $stmt_lista = $con->prepare($sql);
                if ($stmt_lista) {
                    $stmt_lista->bind_param("ii", $limit, $offset);
                    if ($stmt_lista->execute()) {
                        $rez = $stmt_lista->get_result();
                    } else {
                        error_log("lista_metode.php list execute failed: " . $stmt_lista->error);
                        $rez = false;
                    }
                } else {
                    error_log("lista_metode.php list prepare failed: " . $con->error);
                    $rez = false;
                }

                if (!$rez) {
                    // FIX [M3]: Eliminare afișare eroare directă către utilizator (Error Disclosure)
                    error_log("Eroare DB în lista_metode.php: " . $con->error);
                    echo "<p class='alert alert--danger'>Eroare internă a serverului. Reîncercați mai târziu.</p>";
                } else if ($rez->num_rows === 0) {
                    // POLISH [P3]: Empty state
                    echo '
                    <div class="empty-state" style="text-align:center; padding: var(--space-12) var(--space-4);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--color-fg-muted)" stroke-width="2" width="48" height="48" style="margin-bottom: var(--space-4);"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        <h3 style="margin-top: var(--space-3); color: var(--color-fg-muted);">Nu există încă metode de sortare</h3>
                        <p style="color: var(--color-fg-subtle); font-size: var(--text-sm);">Adăugați prima metodă pentru a începe popularea bazei de date.</p>
                        '.(is_admin() ? '<a href="index.php?page=metoda_form" class="btn btn--primary btn--sm" style="margin-top: var(--space-4);">Adaugă metodă</a>' : '').'
                    </div>';
                } else {
                    echo '<div style="overflow-x: auto;">';
                    echo '<table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">';
                    echo '<thead style="background: var(--color-surface-2); color: var(--color-fg-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">';
                    echo '<tr><th style="padding: 1rem; text-align: left;">Nume</th><th style="padding: 1rem; text-align: left;">Categorie</th><th style="padding: 1rem; text-align: left;">Complexitate</th><th style="padding: 1rem; text-align: right;">Acțiuni</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = $rez->fetch_assoc()) {
                        $url_detalii = "index.php?page=metoda&id=" . $row['id_metoda'];
                        echo '<tr style="border-bottom: 1px solid var(--color-border); transition: background 0.2s;">';
                        echo '<td style="padding: 1rem;"><strong><a href="'.$url_detalii.'" style="text-decoration: none; color: var(--color-primary);">'.htmlspecialchars($row['nume']).'</a></strong></td>';
                        echo '<td style="padding: 1rem;">'.htmlspecialchars($row['categorie']).'</td>';
                        echo '<td style="padding: 1rem;"><code style="background: var(--color-surface-3); padding: 0.2rem 0.4rem; border-radius: 4px;">'.htmlspecialchars($row['complexitate']).'</code></td>';
                        echo '<td style="padding: 1rem; text-align: right;">';
                        echo '<a href="'.$url_detalii.'" class="btn btn--quiet btn--sm">Detalii</a>';
                        if (is_admin()) {
                            echo '<a href="index.php?page=metoda_form&id='.$row['id_metoda'].'" class="btn btn--ghost btn--sm" style="margin-left: 0.5rem;">Edit</a>';
                            // FIX [H1]: Înlocuire link ștergere cu formular POST pentru protecție CSRF
                            echo '<form action="PHP/metoda_sterge.php" method="POST" style="display:inline;" data-confirm="Sunteți sigur că doriți să ștergeți această metodă?">';
                            echo csrf_field();
                            echo '<input type="hidden" name="id" value="'.$row['id_metoda'].'">';
                            echo '<button type="submit" class="btn btn--quiet btn--sm" style="color: var(--color-danger); /* FIX [UI1]: replaced inexistent --color-error */ margin-left: 0.5rem; background:none; border:none; cursor:pointer; vertical-align: middle;">Șterge</button>';                            echo '</form>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';

                    // POLISH [P5]: Pagination UI
                    if ($total_pages > 1) {
                        echo '<div style="display: flex; justify-content: center; align-items: center; gap: var(--space-4); margin-top: var(--space-6);">';
                        if ($page > 1) {
                            echo '<a href="index.php?page=metode&p='.($page-1).'" class="btn btn--quiet btn--sm">← Anterior</a>';
                        }
                        echo '<span style="font-size: var(--text-xs); color: var(--color-fg-muted);">Pagina <strong>'.$page.'</strong> din '.$total_pages.'</span>';
                        if ($page < $total_pages) {
                            echo '<a href="index.php?page=metode&p='.($page+1).'" class="btn btn--quiet btn--sm">Următor →</a>';
                        }
                        echo '</div>';
                    }
                }
                if (isset($stmt_lista) && $stmt_lista) {
                    $stmt_lista->close();
                }
                ?>
            </div>
        </div>
    </div>
</div>
