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

// FEATURE [F1]: Anti-enumeration
$success_msg = 'Dacă adresa există în sistem, vei primi un link pentru resetarea parolei.';

$sql = "SELECT id FROM utilizatori WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    $user_id = (int)$row['id'];
    
    // Generare token
    $token = bin2hex(random_bytes(32));
    $token_hash = hash('sha256', $token);
    
    // Inserare token în DB
    $sql_token = "INSERT INTO password_reset_tokens (user_id, token_hash, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
    $stmt_token = $con->prepare($sql_token);
    $stmt_token->bind_param('is', $user_id, $token_hash);
    if ($stmt_token->execute()) {
        // Trimitere email (Mockup in log file for WAMP)
        $log_dir = __DIR__ . '/../storage';
        if (!is_dir($log_dir)) { mkdir($log_dir, 0755, true); }
        $log_file = $log_dir . '/email_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        $link = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/SImp/site_g/index.php?page=reset_password&token=" . $token;
        $log_content = "[$timestamp] To: $email | Subject: Resetare parolă SImp | Link: $link\n";
        file_put_contents($log_file, $log_content, FILE_APPEND);
    }
    $stmt_token->close();
}

$stmt->close();

set_flash('success', $success_msg);
header('Location: ../index.php?page=forgot_password');
exit;
