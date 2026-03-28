<?php
include "conexiune.php";
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Exerciții C++ – Metode de sortare</title>
    <link rel="stylesheet" href="../stil.css">
    <script src="../js/exercitii.js"></script>
</head>
<body>
<header>
    <h1>Exerciții C++ – Metode de sortare</h1>
</header>

<nav>
    <ul>
        <li><a href="../index.php">Acasă</a></li>
        <li><a href="lista_metode.php">Metode (BD)</a></li>
    </ul>
</nav>

<main>
    <h2>Exerciții în baza de date</h2>
<?php
$sql = "SELECT e.id_exercitiu, e.titlu, e.nivel, m.nume AS metoda
        FROM exercitii e
        JOIN metode m ON e.id_metoda = m.id_metoda
        ORDER BY e.id_exercitiu";
$rez = mysqli_query($con, $sql);

if (!$rez) {
    echo "<p>Eroare interogare: " . mysqli_error($con) . "</p>";
} else {
    echo "<table>";
    echo "<tr><th>ID</th><th>Titlu</th><th>Metodă</th><th>Nivel</th></tr>";
    while ($row = mysqli_fetch_assoc($rez)) {
        echo "<tr>";
        echo "<td>".$row['id_exercitiu']."</td>";
        echo "<td>".htmlspecialchars($row['titlu'])."</td>";
        echo "<td>".htmlspecialchars($row['metoda'])."</td>";
        echo "<td>".htmlspecialchars($row['nivel'])."</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>

    <h2>Exerciții interactive (tip W3Schools)</h2>
    <p>
        Mai jos sunt câteva exerciții simple în care trebuie să completezi bucăți
        lipsă de cod C++ pentru metodele de sortare.
    </p>

      <div id="exercitiu-container"></div>

    <button onclick="verificaExercitiu()">Verifică</button>
    <button onclick="afiseazaAjutor()">Ajutor</button>

    <p id="feedback"></p>
    <p id="hint" class="hint"></p>

    <button onclick="urmatorulExercitiu()">Următorul exercițiu</button>

</main>

<footer>
    <p>&copy; 2025 Portal C++ – Metode de sortare</p>
</footer>
</body>
</html>
