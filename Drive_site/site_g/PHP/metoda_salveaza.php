<?php
include "conexiune.php";
include "auth.php";
include "helpers.php";
require_role('admin');

// Verificăm CSRF
verify_csrf();

// Preluăm datele din formularul POST
$id  = isset($_POST['id_metoda']) ? (int)$_POST['id_metoda'] : 0;
$nume = trim($_POST['nume'] ?? "");
$categorie = trim($_POST['categorie'] ?? "");
$complexitate = trim($_POST['complexitate'] ?? "");
$descriere = trim($_POST['descriere'] ?? "");
$fisier_cpp = trim($_POST['fisier_cpp'] ?? "");

// Validare simplă
if (empty($nume)) {
    die("Numele metodei este obligatoriu!");
}

// Validare fisier_cpp pentru a preveni path traversal
if (!empty($fisier_cpp)) {
    // Permitem doar nume de fișiere simple (fără /, \, ..)
    if (preg_match('/[\\\\\/:*?"<>|]/', $fisier_cpp) || strpos($fisier_cpp, '..') !== false) {
        die("Numele fișierului C++ este invalid. Folosește doar litere, cifre, punct și liniuță.");
    }
    // Verificăm extensia
    if (!str_ends_with(strtolower($fisier_cpp), '.cpp')) {
        die("Fișierul trebuie să aibă extensia .cpp");
    }
}

// Nu mai folosim mysqli_real_escape_string, deoarece prepared statements se ocupă de asta.

if ($id > 0) {
    // --- UPDATE (actualizare) cu Prepared Statement ---
    $sql = "UPDATE metode SET nume=?, categorie=?, complexitate=?, descriere=?, fisier_cpp=? WHERE id_metoda=?";

    if ($stmt = mysqli_prepare($con, $sql)) {
        // Legăm variabilele PHP la placeholder-urile din interogare
        // "sssssi" - 5 string-uri (s) și 1 integer (i)
        mysqli_stmt_bind_param($stmt, "sssssi", $nume, $categorie, $complexitate, $descriere, $fisier_cpp, $id);

        // Executăm interogarea
        if (!mysqli_stmt_execute($stmt)) {
            die("Eroare la actualizare: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Eroare la pregătirea interogării de actualizare: " . mysqli_error($con));
    }

} else {
    // --- INSERT (inserare) cu Prepared Statement ---
    $sql = "INSERT INTO metode (nume, categorie, complexitate, descriere, fisier_cpp) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($con, $sql)) {
        // Legăm variabilele
        // "sssss" - 5 string-uri
        mysqli_stmt_bind_param($stmt, "sssss", $nume, $categorie, $complexitate, $descriere, $fisier_cpp);

        // Executăm interogarea
        if (!mysqli_stmt_execute($stmt)) {
            die("Eroare la inserare: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Eroare la pregătirea interogării de inserare: " . mysqli_error($con));
    }
}

// Redirecționăm la lista de metode folosind noul sistem
header("Location: ../index.php?page=metode");
exit;
