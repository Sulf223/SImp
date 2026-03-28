<?php
include "conexiune.php";
include "auth.php";
include "helpers.php";
require_role('admin');

// Verificăm că request-ul este POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Metodă invalidă. Folosește formularul de ștergere.');
}

// Verificăm CSRF
verify_csrf();

// Verificăm dacă primim un ID și dacă este un număr întreg valid
if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
    $id = $_POST['id'];

    // --- Securizare cu Prepared Statements ---

    // 1. Pregătim interogarea SQL cu un placeholder (?) în loc de valoarea directă.
    // Acest lucru separă logica SQL de date, prevenind interpretarea datelor ca fiind cod SQL.
    $sql = "DELETE FROM metode WHERE id_metoda = ?";

    if ($stmt = mysqli_prepare($con, $sql)) {
        // 2. Legăm variabila PHP ($id) la placeholder-ul din interogare.
        // "i" specifică faptul că variabila este de tip integer (întreg).
        mysqli_stmt_bind_param($stmt, "i", $id);

        // 3. Executăm interogarea pregătită.
        if (mysqli_stmt_execute($stmt)) {
            // Ștergerea a avut succes.
        } else {
            // A apărut o eroare la execuție (de ex. probleme de permisiuni, etc.)
            // Într-o aplicație reală, aici ai loga eroarea.
            // echo "Eroare la ștergere: " . mysqli_stmt_error($stmt);
        }

        // 4. Închidem statement-ul.
        mysqli_stmt_close($stmt);
    } else {
        // A apărut o eroare la pregătirea interogării
        // echo "Eroare: " . mysqli_error($con);
    }
}

// La final, redirecționăm utilizatorul înapoi la lista de metode.
// Folosim noul sistem de paginare.
header("Location: ../index.php?page=metode");
exit;
