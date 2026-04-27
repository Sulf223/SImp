<?php
include "conexiune.php";
include "auth.php"; 
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
                $sql = "SELECT * FROM metode ORDER BY id_metoda";
                $rez = mysqli_query($con, $sql);

                if (!$rez) {
                    echo "<p class='alert alert-error'>Eroare la interogare: " . htmlspecialchars(mysqli_error($con)) . "</p>";
                } else {
                    echo '<div style="overflow-x: auto;">';
                    echo '<table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">';
                    echo '<thead style="background: var(--color-surface-2); color: var(--color-fg-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">';
                    echo '<tr><th style="padding: 1rem; text-align: left;">Nume</th><th style="padding: 1rem; text-align: left;">Categorie</th><th style="padding: 1rem; text-align: left;">Complexitate</th><th style="padding: 1rem; text-align: right;">Acțiuni</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($rez)) {
                        $url_detalii = "index.php?page=metoda&id=" . $row['id_metoda'];
                        echo '<tr style="border-bottom: 1px solid var(--color-border); transition: background 0.2s;" onmouseover="this.style.background=\'var(--color-surface-2)\'" onmouseout="this.style.background=\'transparent\'">';
                        echo '<td style="padding: 1rem;"><strong><a href="'.$url_detalii.'" style="text-decoration: none; color: var(--color-primary);">'.htmlspecialchars($row['nume']).'</a></strong></td>';
                        echo '<td style="padding: 1rem;">'.htmlspecialchars($row['categorie']).'</td>';
                        echo '<td style="padding: 1rem;"><code style="background: var(--color-surface-3); padding: 0.2rem 0.4rem; border-radius: 4px;">'.htmlspecialchars($row['complexitate']).'</code></td>';
                        echo '<td style="padding: 1rem; text-align: right;">';
                        echo '<a href="'.$url_detalii.'" class="btn btn--quiet btn--sm">Detalii</a>';
                        if (is_admin()) {
                            echo '<a href="index.php?page=metoda_form&id='.$row['id_metoda'].'" class="btn btn--ghost btn--sm" style="margin-left: 0.5rem;">Edit</a>';
                            echo '<a href="PHP/metoda_sterge.php?id='.$row['id_metoda'].'" class="btn btn--quiet btn--sm" style="color: var(--color-error); margin-left: 0.5rem;" onclick="return confirm(\'Sunteți sigur că doriți să ștergeți această metodă?\');">Șterge</a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
