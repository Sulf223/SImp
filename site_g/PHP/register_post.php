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

$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
if (!check_rate_limit($con, 'register', $ip, 5, 3600)) {
    set_flash('error', 'Prea multe încercări de creare cont. Te rugăm să încerci mai târziu.');
    header('Location: ../index.php?page=register');
    exit;
}

// Prelucrăm datele din formular
$username = trim($_POST['username'] ?? '');
$email = mb_strtolower(trim($_POST['email'] ?? ''), 'UTF-8');
// FIX [M9]: Validare lungime username (3-64 caractere)
if (mb_strlen($username) > 64 || mb_strlen($username) < 3) {
    set_flash("error", "Username-ul trebuie să aibă între 3 și 64 de caractere.");
    header("Location: ../index.php?page=register");
    exit;
}

// FEATURE [F1]: Validare email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Adresă de email invalidă.');
    header("Location: ../index.php?page=register");
    exit;
}

$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// 1. Validare simplă pe server-side
if (empty($username) || empty($email) || empty($password)) {
    set_flash('error', 'Toate câmpurile sunt obligatorii.');
    header('Location: ../index.php?page=register');
    exit;
}

if ($password !== $password_confirm) {
    set_flash('error', 'Parolele nu se potrivesc.');
    header('Location: ../index.php?page=register');
    exit;
}

// 1.1 Validare complexitate parolă (P0)
if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    set_flash('error', 'Parola trebuie să aibă minim 8 caractere și să conțină atât litere cât și cifre.');
    header('Location: ../index.php?page=register');
    exit;
}

// 2. Verificăm dacă utilizatorul sau emailul există deja în baza de date
$sql_check = "SELECT id FROM utilizatori WHERE username = ? OR email = ?";
$stmt_check = $con->prepare($sql_check);
if ($stmt_check) {
    $stmt_check->bind_param('ss', $username, $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Utilizatorul există deja - Mesaj generic anti-enumeration (P1)
        set_flash('error', 'Înregistrarea a eșuat. Numele de utilizator sau emailul pot fi deja utilizate.');
        header('Location: ../index.php?page=register');
        exit;
    }
    $stmt_check->close();
}


// 3. Hash-uim parola
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// 4. Inserăm utilizatorul nou în baza de date cu rolul 'user'
$sql_insert = "INSERT INTO utilizatori (username, email, parola_hash, rol) VALUES (?, ?, ?, 'user')";
$stmt_insert = $con->prepare($sql_insert);
if ($stmt_insert) {
    $stmt_insert->bind_param('sss', $username, $email, $password_hash);

    if ($stmt_insert->execute()) {
        set_flash('success', 'Contul a fost creat cu succes! Te rugăm să te autentifici.');
        header('Location: ../index.php?page=login');
    } else {
        set_flash('error', 'A apărut o eroare la crearea contului. Te rugăm să încerci din nou.');
        header('Location: ../index.php?page=register');
    }
    $stmt_insert->close();
}
exit;
?>
