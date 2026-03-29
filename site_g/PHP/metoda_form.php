<?php
// Acest fișier este acum inclus în index.php
include "conexiune.php";
include "auth.php";
require_role('admin');

// Preluăm ID-ul din URL, dacă există, și ne asigurăm că e un număr întreg
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Inițializăm variabilele pentru a nu avea erori în formular
$nume = $categorie = $complexitate = $descriere = $fisier_cpp = "";

// Dacă avem un ID, înseamnă că edităm o metodă existentă.
// Preluăm datele din baza de date în mod securizat.
if ($id > 0) {
    // --- SELECT cu Prepared Statement ---
    $sql = "SELECT nume, categorie, complexitate, descriere, fisier_cpp FROM metode WHERE id_metoda = ?";

    if ($stmt = mysqli_prepare($con, $sql)) {
        // Legăm ID-ul la interogare
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Executăm
        mysqli_stmt_execute($stmt);

        // Preluăm rezultatele
        $rezultat = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($rezultat)) {
            // Atribuim valorile găsite variabilelor noastre
            $nume = $row['nume'];
            $categorie = $row['categorie'];
            $complexitate = $row['complexitate'];
            $descriere = $row['descriere'];
            $fisier_cpp = $row['fisier_cpp'];
        } else {
            // Dacă nu găsim un ID, ar fi bine să oprim execuția
            die("Eroare: Nu a fost găsită nicio metodă cu acest ID.");
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Eroare la pregătirea interogării SELECT.");
    }
}
?>

<section>
    <h2><?php echo $id > 0 ? "Editare metodă" : "Adăugare metodă nouă"; ?></h2>
    <p>
        <a href="index.php?page=metode">Înapoi la lista de metode</a>
    </p>

    <form method="post" action="PHP/metoda_salveaza.php" onsubmit="return valideazaMetoda();">
        <?php csrf_field(); ?>
        <input type="hidden" name="id_metoda" value="<?php echo $id; ?>">

        <label>Nume metodă:</label><br>
        <input type="text" name="nume" id="nume" value="<?php echo htmlspecialchars($nume); ?>" required><br><br>

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

        <input type="submit" value="Salvează" class="btn btn-primary">
    </form>
</section>

<!-- TODO: Asigură-te că fișierul js/validare.js este încărcat corect.
     Poți adăuga tag-ul <script> aici sau, ideal, în fișierul principal index.php,
     într-o secțiune de scripturi. -->
<script src="js/validare.js"></script>
