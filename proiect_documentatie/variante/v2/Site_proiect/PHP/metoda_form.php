<?php
include "conexiune.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$nume = $categorie = $complexitate = $descriere = $fisier_cpp = "";

if ($id > 0) {
    $sql = "SELECT * FROM metode WHERE id_metoda = $id";
    $rez = mysqli_query($con, $sql);
    if ($rez && mysqli_num_rows($rez) == 1) {
        $row = mysqli_fetch_assoc($rez);
        $nume = $row['nume'];
        $categorie = $row['categorie'];
        $complexitate = $row['complexitate'];
        $descriere = $row['descriere'];
        $fisier_cpp = $row['fisier_cpp'];
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id>0 ? "Editare metodă" : "Adăugare metodă"; ?></title>
    <link rel="stylesheet" href="../stil.css">
    <script src="../js/validare.js"></script>
</head>
<body>
<header>
    <h1><?php echo $id>0 ? "Editare metodă" : "Adăugare metodă"; ?></h1>
</header>

<nav>
    <ul>
        <li><a href="lista_metode.php">Înapoi la listă</a></li>
    </ul>
</nav>

<main>
<form method="post" action="metoda_salveaza.php" onsubmit="return valideazaMetoda();">
    <input type="hidden" name="id_metoda" value="<?php echo $id; ?>">

    <label>Nume metodă:</label><br>
    <input type="text" name="nume" id="nume" value="<?php echo htmlspecialchars($nume); ?>"><br><br>

    <label>Categorie:</label><br>
    <input type="text" name="categorie" id="categorie" value="<?php echo htmlspecialchars($categorie); ?>"><br><br>

    <label>Complexitate:</label><br>
    <input type="text" name="complexitate" id="complexitate" value="<?php echo htmlspecialchars($complexitate); ?>"><br><br>

    <label>Fișier C++ (numele din folderul cpp):</label><br>
    <input type="text" name="fisier_cpp" id="fisier_cpp" value="<?php echo htmlspecialchars($fisier_cpp); ?>"><br><br>

    <label>Descriere (explicație simplă):</label><br>
    <textarea name="descriere" id="descriere" rows="5" cols="60"><?php
        echo htmlspecialchars($descriere);
    ?></textarea><br><br>

    <input type="submit" value="Salvează">
</form>
</main>

<footer>
    <p>&copy; 2025 Portal C++ – Metode de sortare</p>
</footer>
</body>
</html>
