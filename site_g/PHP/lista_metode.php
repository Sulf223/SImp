<?php
// Acest fișier este acum inclus în index.php
// Conexiunea la baza de date și funcțiile de autentificare sunt necesare
include "conexiune.php";
include "auth.php"; // Includem auth.php pentru funcțiile is_logged_in() și is_admin()

?>

<section>
    <h2>Metode de sortare (din baza de date)</h2>
    <?php if (is_admin()): // Afișăm butonul de adăugare doar pentru admini ?>
        <p>
            <a href="index.php?page=metoda_form" class="btn btn-primary">Adaugă o metodă nouă</a>
        </p>
    <?php endif; ?>

    <?php
    $sql = "SELECT * FROM metode ORDER BY id_metoda";
    $rez = mysqli_query($con, $sql);

    if (!$rez) {
        echo "<p>Eroare la interogare: " . htmlspecialchars(mysqli_error($con)) . "</p>";
    } else {
        echo '<div class="table-wrapper">';
        echo "<table>";
        echo "<thead><tr><th>Nume</th><th>Categorie</th><th>Complexitate</th><th>Acțiuni</th></tr></thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_assoc($rez)) {
            $url_detalii = "index.php?page=metoda&id=" . $row['id_metoda'];
            echo "<tr>";
            // Transformăm numele într-un link către pagina de detalii
            echo "<td><strong><a href=\"$url_detalii\">".htmlspecialchars($row['nume'])."</a></strong></td>";
            echo "<td>".htmlspecialchars($row['categorie'])."</td>";
            echo "<td><code>".htmlspecialchars($row['complexitate'])."</code></td>";
            echo "<td>";
            echo "<a href=\"$url_detalii\">Vezi detalii</a>";
            if (is_admin()) { // Afișăm link-urile de editare și ștergere doar pentru admini
                echo " | <a href=\"index.php?page=metoda_form&id=".$row['id_metoda']."\">Editează</a>";
                echo " | <a href=\"PHP/metoda_sterge.php?id=".$row['id_metoda']."\" onclick=\"return confirm('Sunteți sigur că doriți să ștergeți această metodă?');\">Șterge</a>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
    ?>
</section>