<?php
// PHP/reset_password_post.php
require_once 'conexiune.php';
require_once 'helpers.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=reset_password');
    exit;
}

verify_csrf();

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

if (empty($token) || strlen($token) !== 64 || !ctype_xdigit($token)) {
    set_flash('error', 'Token invalid.');
    header('Location: ../index.php?page=forgot_password');
    exit;
}

if ($password !== $password_confirm) {
    set_flash('error', 'Parolele nu se potrivesc.');
    header("Location: ../index.php?page=reset_password&token=$token");
    exit;
}

// FEATURE [F1]: Validare complexitate parolă (P0/F2)
if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    set_flash('error', 'Parola trebuie să aibă minim 8 caractere și să conțină atât litere cât și cifre.');
    header("Location: ../index.php?page=reset_password&token=$token");
    exit;
}

$token_hash = hash('sha256', $token);

// Verificare token
$sql = "SELECT id, user_id FROM password_reset_tokens WHERE token_hash = ? AND used_at IS NULL AND expires_at > NOW()";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $token_hash);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    $token_id = $row['id'];
    $user_id = $row['user_id'];
    
    // Hash noua parolă
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Update utilizator
    $sql_upd = "UPDATE utilizatori SET parola_hash = ? WHERE id = ?";
    $stmt_upd = $con->prepare($sql_upd);
    $stmt_upd->bind_param('si', $password_hash, $user_id);
    $stmt_upd->execute();
    $stmt_upd->close();
    
    // Marcare token ca folosit
    $sql_mark = "UPDATE password_reset_tokens SET used_at = NOW() WHERE id = ?";
    $stmt_mark = $con->prepare($sql_mark);
    $stmt_mark->bind_param('i', $token_id);
    $stmt_mark->execute();
    $stmt_mark->close();
    
    set_flash('success', 'Parola a fost resetată cu succes! Te poți autentifica acum.');
    header('Location: ../index.php?page=login');
} else {
    set_flash('error', 'Link de resetare invalid sau expirat. Te rugăm să ceri altul.');
    header('Location: ../index.php?page=forgot_password');
}

$stmt->close();
exit;
