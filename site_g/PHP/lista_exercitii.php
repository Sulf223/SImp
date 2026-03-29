<?php
// Acest fișier este acum inclus în index.php
include __DIR__ . '/conexiune.php';
?>

<section>
    <h2>Exerciții din baza de date</h2>
    <?php
    $sql = "SELECT e.id_exercitiu, e.titlu, e.nivel, m.nume AS metoda
            FROM exercitii e
            JOIN metode m ON e.id_metoda = m.id_metoda
            ORDER BY e.id_exercitiu";
    $rez = mysqli_query($con, $sql);

    if (!$rez) {
        echo "<p>Eroare la interogare: " . htmlspecialchars(mysqli_error($con)) . "</p>";
    } else {
        echo '<div class="table-wrapper">';
        echo "<table>";
        echo "<thead><tr><th>ID</th><th>Titlu</th><th>Metodă</th><th>Nivel</th></tr></thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_assoc($rez)) {
            echo "<tr>";
            echo "<td>".$row['id_exercitiu']."</td>";
            echo "<td>".htmlspecialchars($row['titlu'])."</td>";
            echo "<td>".htmlspecialchars($row['metoda'])."</td>";
            echo "<td>".htmlspecialchars($row['nivel'])."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
    ?>
</section>

<section>
    <h2>Exercitii interactive pe lectii</h2>
    <p>
        Exercițiile tip W3Schools pentru algoritmii fundamentali au fost mutate direct
        în fiecare lecție (Bubble, Selection, Insertion, Quick, Merge, Counting)
        pentru progres contextual și contorizare corectă.
    </p>
    <div class="hero-actions">
        <a href="index.php?page=sortare" class="btn btn-primary">Mergi la lectii fundamentale</a>
    </div>
</section>

<section id="ex-rec-bt" class="mt-4">
    <h2>Exerciții interactive: Recursivitate și Backtracking</h2>
    <p>
        Completează spațiile lipsă pentru probleme clasice de recursivitate și backtracking.
    </p>

    <div id="exercitiu-avansat-container">
        <!-- Conținutul exercițiului avansat va fi încărcat aici de JavaScript -->
    </div>

    <div class="hero-actions">
        <button onclick="verificaExercitiuAvansat()" class="btn btn-primary">Verifică</button>
        <button onclick="afiseazaAjutorAvansat()" class="btn btn-ghost">Ajutor</button>
        <button onclick="urmatorulExercitiuAvansat()" class="btn">Următorul exercițiu</button>
    </div>

    <p id="feedback-avansat"></p>
    <p id="hint-avansat" class="hint" style="display: none;"></p>
</section>

<section class="mt-4">
    <h2>Laborator vizual pentru toate algoritmele</h2>
    <p>
        Alege un algoritm și urmărește pas cu pas cum funcționează.
        Sunt incluse sortări, recursivitate (factorial/fibonacci) și backtracking (permutări).
    </p>

    <div id="algorithms-lab" class="visualizer-container" data-mode="all"></div>
</section>

<!-- Includem JS-ul necesar pentru interactivitate direct aici -->
<!-- Calea este acum relativă la index.php -->
<script src="JS/exercitii_avansate.js"></script>
<script src="JS/visualizer.js"></script>