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
if (!validate_password_complexity($password)) {
    set_flash('error', 'Parola trebuie să aibă minim 8 caractere și să conțină atât litere cât și cifre.');
    header("Location: ../index.php?page=reset_password&token=$token");
    exit;
}

$token_hash = hash('sha256', $token);

$resetOk = false;
$con->begin_transaction();
try {
    $sql = "SELECT id, user_id FROM password_reset_tokens WHERE token_hash = ? AND used_at IS NULL AND expires_at > NOW() FOR UPDATE";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('prepare token lookup failed: ' . $con->error);
    }
    $stmt->bind_param('s', $token_hash);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row) {
        $user_id = (int)$row['user_id'];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql_upd = "UPDATE utilizatori SET parola_hash = ? WHERE id = ?";
        $stmt_upd = $con->prepare($sql_upd);
        if (!$stmt_upd) {
            throw new RuntimeException('prepare password update failed: ' . $con->error);
        }
        $stmt_upd->bind_param('si', $password_hash, $user_id);
        $stmt_upd->execute();
        $resetOk = $stmt_upd->affected_rows >= 0;
        $stmt_upd->close();

        if ($resetOk) {
            $sql_mark = "UPDATE password_reset_tokens SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL";
            $stmt_mark = $con->prepare($sql_mark);
            if (!$stmt_mark) {
                throw new RuntimeException('prepare token mark failed: ' . $con->error);
            }
            $stmt_mark->bind_param('i', $user_id);
            $stmt_mark->execute();
            $stmt_mark->close();
        }
    }
    $con->commit();
} catch (Throwable $e) {
    $con->rollback();
    error_log('reset_password_post failed: ' . $e->getMessage());
}

if ($resetOk) {
    set_flash('success', 'Parola a fost resetată cu succes! Te poți autentifica acum.');
    header('Location: ../index.php?page=login');
} else {
    set_flash('error', 'Link de resetare invalid sau expirat. Te rugăm să ceri altul.');
    header('Location: ../index.php?page=forgot_password');
}
exit;
