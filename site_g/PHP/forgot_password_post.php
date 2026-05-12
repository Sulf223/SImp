<?php
// PHP/forgot_password_post.php
require_once 'conexiune.php';
require_once 'helpers.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=forgot_password');
    exit;
}

verify_csrf();

$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
// Rate limit: 3 requests per hour per IP
if (!check_rate_limit($con, 'pwd_reset', $ip, 3, 3600)) {
    set_flash('error', 'Prea multe cereri. Te rugăm să încerci din nou mai târziu.');
    header('Location: ../index.php?page=forgot_password');
    exit;
}

$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Adresă de email invalidă.');
    header('Location: ../index.php?page=forgot_password');
    exit;
}

$normalizedEmail = mb_strtolower($email, 'UTF-8');
if (!check_rate_limit($con, 'pwd_reset_email', $normalizedEmail, 3, 3600)) {
    set_flash('error', 'Prea multe cereri pentru această adresă. Te rugăm să încerci din nou mai târziu.');
    header('Location: ../index.php?page=forgot_password');
    exit;
}

// FEATURE [F1]: Anti-enumeration
$success_msg = 'Dacă adresa există în sistem, vei primi un link pentru resetarea parolei.';

$sql = "SELECT id FROM utilizatori WHERE email = ?";
$stmt = $con->prepare($sql);
$row = null;
if (!$stmt) {
    error_log('forgot_password_post prepare user lookup failed: ' . $con->error);
} else {
    $stmt->bind_param('s', $normalizedEmail);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
}

if ($row) {
    $user_id = (int)$row['id'];
    
    // Generare token
    $token = bin2hex(random_bytes(32));
    $token_hash = hash('sha256', $token);
    
    $con->begin_transaction();
    try {
        // Un singur token activ per utilizator: tokenurile vechi devin invalide.
        $stmt_expire = $con->prepare("UPDATE password_reset_tokens SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL");
        if (!$stmt_expire) {
            throw new RuntimeException('prepare expire tokens failed: ' . $con->error);
        }
        $stmt_expire->bind_param('i', $user_id);
        $stmt_expire->execute();
        $stmt_expire->close();

        $sql_token = "INSERT INTO password_reset_tokens (user_id, token_hash, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        $stmt_token = $con->prepare($sql_token);
        if (!$stmt_token) {
            throw new RuntimeException('prepare insert token failed: ' . $con->error);
        }
        $stmt_token->bind_param('is', $user_id, $token_hash);
        $stmt_token->execute();
        $stmt_token->close();
        $con->commit();

        $link = app_url('index.php', ['page' => 'reset_password', 'token' => $token]);
        $subject = 'Resetare parolă OffByOne Academy';
        $text = "Ai cerut resetarea parolei pentru contul tău OffByOne Academy.\n\nLinkul este valabil 1 oră:\n{$link}\n\nDacă nu ai cerut tu resetarea, ignoră acest mesaj.";
        $html = '<p>Ai cerut resetarea parolei pentru contul tău <strong>OffByOne Academy</strong>.</p>'
              . '<p><a href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '">Resetează parola</a></p>'
              . '<p>Linkul este valabil 1 oră. Dacă nu ai cerut tu resetarea, ignoră acest mesaj.</p>';
        send_app_mail($normalizedEmail, $subject, $html, $text);
    } catch (Throwable $e) {
        $con->rollback();
        error_log('forgot_password_post token/email failed: ' . $e->getMessage());
    }
}

set_flash('success', $success_msg);
header('Location: ../index.php?page=forgot_password');
exit;
