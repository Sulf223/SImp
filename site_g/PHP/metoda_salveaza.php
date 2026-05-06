<?php
require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/auth.php';
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

    // FIX [L2]: Validare existență fizică și dimensiune fișier C++
    $full_path = __DIR__ . '/../CPP/' . $fisier_cpp;
    if (!is_file($full_path)) {
        die("Fișierul C++ specificat nu există în directorul CPP.");
    }
    if (filesize($full_path) > 1000000) {
        die("Fișierul C++ este prea mare (maxim 1MB).");
    }
}

// Nu mai folosim mysqli_real_escape_string, deoarece prepared statements se ocupă de asta.

if ($id > 0) {
    // --- UPDATE (actualizare) cu Prepared Statement ---
    $sql = "UPDATE metode SET nume=?, categorie=?, complexitate=?, descriere=?, fisier_cpp=? WHERE id_metoda=?";

    if ($stmt = $con->prepare($sql)) {
        // Legăm variabilele PHP la placeholder-urile din interogare
        // "sssssi" - 5 string-uri (s) și 1 integer (i)
        $stmt->bind_param("sssssi", $nume, $categorie, $complexitate, $descriere, $fisier_cpp, $id);

        // Executăm interogarea (exemplu pentru UPDATE)
        if (!$stmt->execute()) {
            error_log("Eroare la actualizare metoda ID $id: " . $stmt->error);
            die("A apărut o eroare la salvarea datelor în baza de date.");
        }
        $stmt->close();
    } else {
        error_log("Eroare la pregătirea interogării de actualizare: " . $con->error);
        die("A apărut o eroare tehnică. Te rugăm să revii mai târziu.");
    }

} else {
    // --- INSERT (inserare) cu Prepared Statement ---
    $sql = "INSERT INTO metode (nume, categorie, complexitate, descriere, fisier_cpp) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $con->prepare($sql)) {
        // Legăm variabilele
        // "sssss" - 5 string-uri
        $stmt->bind_param("sssss", $nume, $categorie, $complexitate, $descriere, $fisier_cpp);

        // Executăm interogarea
        if (!$stmt->execute()) {
            error_log("Eroare la inserare metodă: " . $stmt->error);
            die("A apărut o eroare la salvarea datelor în baza de date.");
        }
        $stmt->close();
    } else {
        error_log("Eroare la pregătirea interogării de inserare: " . $con->error);
        die("A apărut o eroare tehnică. Te rugăm să revii mai târziu.");
    }
}

// Redirecționăm la lista de metode folosind noul sistem
header("Location: ../index.php?page=metode");
exit;
