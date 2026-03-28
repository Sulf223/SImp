<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Includem fișierul de conexiune la baza de date și helpers
require_once 'conexiune.php';
require_once 'helpers.php';

// Verificăm dacă request-ul este de tip POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Dacă nu este POST, redirecționăm către pagina de înregistrare
    header('Location: ../index.php?page=register');
    exit;
}

// Verificăm CSRF
verify_csrf();

// Prelucrăm datele din formular
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// 1. Validare simplă pe server-side
if (empty($username) || empty($password)) {
    set_flash('error', 'Numele de utilizator și parola sunt obligatorii.');
    header('Location: ../index.php?page=register');
    exit;
}

if ($password !== $password_confirm) {
    set_flash('error', 'Parolele nu se potrivesc.');
    header('Location: ../index.php?page=register');
    exit;
}

// 2. Verificăm dacă utilizatorul există deja în baza de date
$sql_check = "SELECT id FROM utilizatori WHERE username = ?";
$stmt_check = mysqli_prepare($con, $sql_check);
mysqli_stmt_bind_param($stmt_check, 's', $username);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    // Utilizatorul există deja
    set_flash('error', 'Numele de utilizator ' . htmlspecialchars($username) . ' este deja folosit.');
    header('Location: ../index.php?page=register');
    exit;
}
mysqli_stmt_close($stmt_check);


// 3. Hash-uim parola
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// 4. Inserăm utilizatorul nou în baza de date cu rolul 'user'
$sql_insert = "INSERT INTO utilizatori (username, parola_hash, rol) VALUES (?, ?, 'user')";
$stmt_insert = mysqli_prepare($con, $sql_insert);
mysqli_stmt_bind_param($stmt_insert, 'ss', $username, $password_hash);

if (mysqli_stmt_execute($stmt_insert)) {
    set_flash('success', 'Contul a fost creat cu succes! Te rugăm să te autentifici.');
    header('Location: ../index.php?page=login');
} else {
    set_flash('error', 'A apărut o eroare la crearea contului. Te rugăm să încerci din nou.');
    header('Location: ../index.php?page=register');
}
mysqli_stmt_close($stmt_insert);
exit;
?>