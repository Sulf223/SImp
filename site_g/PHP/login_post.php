<?php
// Procesare login
require_once __DIR__ . "/conexiune.php";
require_once __DIR__ . "/helpers.php"; // Includem helpers pentru set_flash și verify_csrf

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificăm CSRF
verify_csrf();

$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    set_flash('error', 'Completă utilizator și parolă');
    header('Location: ../index.php?page=login');
    exit;
}

// Verificăm rate limiting (P1 - Mutat în DB și bazat pe IP)
$user_ip = $_SERVER['REMOTE_ADDR'] ?: 'unknown';
if (!check_rate_limit($con, 'login', $user_ip, 5, 900)) {
    set_flash('error', 'Prea multe încercări eșuate. Te rog așteaptă 15 minute.');
    header('Location: ../index.php?page=login');
    exit;
}

// Căutăm utilizatorul în tabelul `utilizatori` folosind prepared statements
$sql = "SELECT id, username, parola_hash, rol FROM utilizatori WHERE username = ? LIMIT 1";
$stmt = mysqli_prepare($con, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    // Verificăm parola (hash)
    if ($user && password_verify($password, $user['parola_hash'])) {
        // Regenerează session ID pentru securitate
        regenerate_session();
        
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['rol'] = $user['rol'] ?? 'user';
        
        // Resetăm rate limiting la login cu succes
        reset_rate_limit($con, 'login', $user_ip);

        set_flash('success', 'Te-ai autentificat cu succes!');
        header('Location: ../index.php?page=metode');
        exit;
    }
}

// Dacă am ajuns aici: user sau parolă greșite
set_flash('error', 'Utilizator sau parolă incorecte');
header('Location: ../index.php?page=login');
exit;
