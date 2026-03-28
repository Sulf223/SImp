<?php
include "conexiune.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $sql = "DELETE FROM metode WHERE id_metoda = $id";
    mysqli_query($con, $sql);
}

header("Location: lista_metode.php");
exit;
