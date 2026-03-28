<?php
include "conexiune.php";

$id  = isset($_POST['id_metoda']) ? (int)$_POST['id_metoda'] : 0;
$nume = trim($_POST['nume'] ?? "");
$categorie = trim($_POST['categorie'] ?? "");
$complexitate = trim($_POST['complexitate'] ?? "");
$descriere = trim($_POST['descriere'] ?? "");
$fisier_cpp = trim($_POST['fisier_cpp'] ?? "");

if ($nume === "") {
    die("Numele metodei este obligatoriu!");
}

$nume = mysqli_real_escape_string($con, $nume);
$categorie = mysqli_real_escape_string($con, $categorie);
$complexitate = mysqli_real_escape_string($con, $complexitate);
$descriere = mysqli_real_escape_string($con, $descriere);
$fisier_cpp = mysqli_real_escape_string($con, $fisier_cpp);

if ($id > 0) {
    $sql = "UPDATE metode
            SET nume='$nume',
                categorie='$categorie',
                complexitate='$complexitate',
                descriere='$descriere',
                fisier_cpp='$fisier_cpp'
            WHERE id_metoda=$id";
} else {
    $sql = "INSERT INTO metode (nume, categorie, complexitate, descriere, fisier_cpp)
            VALUES ('$nume','$categorie','$complexitate','$descriere','$fisier_cpp')";
}

if (mysqli_query($con, $sql)) {
    header("Location: lista_metode.php");
    exit;
} else {
    echo "Eroare la salvare: " . mysqli_error($con);
}
