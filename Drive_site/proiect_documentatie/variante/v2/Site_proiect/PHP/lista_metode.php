<?php
include "conexiune.php";
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Metode de sortare (BD)</title>
    <link rel="stylesheet" href="../stil.css">
</head>
<body>
<header>
    <h1>Metode de sortare (din baza de date)</h1>
</header>

<nav>
    <ul>
        <li><a href="../index.php">Acasă</a></li>
        <li><a href="metoda_form.php">Adaugă metodă</a></li>
    </ul>
</nav>

<main>
<?php
$sql = "SELECT * FROM metode ORDER BY nume";
$rez = mysqli_query($con, $sql);

if (!$rez) {
    echo "<p>Eroare interogare: " . mysqli_error($con) . "</p>";
} else {
    echo "<table>";
    echo "<tr><th>ID</th><th>Nume</th><th>Categorie</th><th>Complexitate</th><th>Fișier C++</th><th>Acțiuni</th></tr>";
    while ($row = mysqli_fetch_assoc($rez)) {
        echo "<tr>";
        echo "<td>".$row['id_metoda']."</td>";
        echo "<td>".htmlspecialchars($row['nume'])."</td>";
        echo "<td>".htmlspecialchars($row['categorie'])."</td>";
        echo "<td>".htmlspecialchars($row['complexitate'])."</td>";
        echo "<td>";
        if (!empty($row['fisier_cpp'])) {
            echo "<a href=\"../cpp/".htmlspecialchars($row['fisier_cpp'])."\" target=\"_blank\">".
                 htmlspecialchars($row['fisier_cpp'])."</a>";
        }
        echo "</td>";
        echo "<td>
                <a href=\"metoda_form.php?id=".$row['id_metoda']."\">Editează</a> |
                <a href=\"metoda_sterge.php?id=".$row['id_metoda']."\" onclick=\"return confirm('Sigur ștergi?');\">Șterge</a>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
</main>

<footer>
    <p>&copy; 2025 Portal C++ – Metode de sortare</p>
</footer>
</body>
</html>
