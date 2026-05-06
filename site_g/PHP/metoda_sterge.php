<?php
require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/helpers.php';
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

    if ($stmt = $con->prepare($sql)) {
        // 2. Legăm variabila PHP ($id) la placeholder-ul din interogare.
        // "i" specifică faptul că variabila este de tip integer (întreg).
        $stmt->bind_param("i", $id);

        // 3. Executăm interogarea pregătită.
        if ($stmt->execute()) {
            // Ștergerea a avut succes.
        } else {
            // A apărut o eroare la execuție (de ex. probleme de permisiuni, etc.)
            error_log("Eroare la ștergere metoda ID $id: " . $stmt->error);
        }

        // 4. Închidem statement-ul.
        $stmt->close();
    } else {
        // A apărut o eroare la pregătirea interogării
        error_log("Eroare pregătire ștergere: " . $con->error);
    }
}

// La final, redirecționăm utilizatorul înapoi la lista de metode.
// Folosim noul sistem de paginare.
header("Location: ../index.php?page=metode");
exit;
