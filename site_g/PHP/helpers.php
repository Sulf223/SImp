<?php
// PHP/helpers.php - Funcții ajutătoare pentru Flash Messages și CSRF

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifică și aplică timeout-ul de sesiune (30 min inactivitate).
 * Pentru endpoint-urile AJAX care trebuie să refuze cererea cu HTTP 401 dacă a expirat.
 * Apelează imediat după session_start().
 */
function enforce_session_timeout_ajax(int $max_inactivity_seconds = 1800): void {
    if (!isset($_SESSION['user_id'])) return; // anonim → nu enforce
    if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $max_inactivity_seconds) {
        session_unset();
        session_destroy();
        http_response_code(401);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'error' => 'Sesiune expirată', 'expired' => true]);
        exit;
    }
    $_SESSION['last_activity'] = time();
}

/**
 * Extrage token-ul CSRF din headere sau POST.
 * FIX [A8]: Fallback pentru nginx unde getallheaders() poate lipsi.
 */
function get_csrf_token_from_request(): string {
    $token = '';
    if (function_exists('getallheaders')) {
        $h = getallheaders();
        if (is_array($h)) {
            // Verificăm atât varianta Case-Sensitive cât și cea lowercase (pentru diverse servere/proxy-uri)
            $token = $h['X-CSRF-Token'] ?? $h['x-csrf-token'] ?? '';
        }
    }
    if (!$token && isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
    }
    if (!$token && isset($_POST['csrf_token'])) {
        $token = $_POST['csrf_token'];
    }
    return is_string($token) ? $token : '';
}

/**
 * Impune timeout de sesiune (30 minute) si actualizeaza last_activity.
 * Pentru pagini UI care fac redirect.
 */
function enforce_session_timeout(bool $redirect_on_expire = true): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        return;
    }

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();
        session_destroy();
        if ($redirect_on_expire) {
            header("Location: index.php?page=login&expired=1");
            exit;
        }
        return;
    }

    $_SESSION['last_activity'] = time();
}

/**
 * Setează un mesaj flash (care va fi afișat o singură dată).
 * @param string $type 'success', 'error', 'info'
 * @param string $message Mesajul de afișat
 */
function set_flash($type, $message) {
    if (!in_array($type, ['success', 'error', 'info'], true)) {
        error_log("set_flash: tip invalid '$type', folosit 'info'");
        $type = 'info';
    }
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Afișează mesajele flash salvate în sesiune și le șterge.
 * POLISH [P4]: Generates modern toast notifications instead of static alerts.
 */
function display_flash() {
    if (!empty($_SESSION['flash_messages'])) {
        echo '<div class="toast-container" id="toast-container">';
        foreach ($_SESSION['flash_messages'] as $msg) {
            $type = $msg['type']; // success, error, info
            $icon = "";
            if ($type === 'success') $icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polyline points="20 6 9 17 4 12"/></svg>';
            elseif ($type === 'error') $icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
            else $icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';

            echo '<div class="toast toast--' . $type . '" role="alert" aria-live="assertive" aria-atomic="true">';
            echo '<div class="toast__icon">' . $icon . '</div>';
            echo '<div class="toast__content">' . htmlspecialchars($msg['message']) . '</div>';
            echo '<button type="button" class="toast__close" aria-label="Închide">&times;</button>';
            echo '</div>';
        }
        echo '</div>';
        // Ștergem mesajele după afișare
        unset($_SESSION['flash_messages']);
    }
}

/**
 * Generează un token CSRF și îl salvează în sesiune.
 * @return string Token-ul generat
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generează un input hidden cu token-ul CSRF.
 */
function csrf_field() {
    $token = generate_csrf_token();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verifică token-ul CSRF primit prin POST.
 * Oprește execuția dacă token-ul este invalid.
 */
function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requestToken = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        if (!is_string($requestToken) || !is_string($sessionToken) || $sessionToken === '' || !hash_equals($sessionToken, $requestToken)) {
            http_response_code(403);
            die('Eroare CSRF: Token invalid sau lipsă. Te rog reîncarcă pagina.');
        }
    }
}

function env_string(string $key, string $default = ''): string {
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }
    return (string)$value;
}

function app_base_url(): string {
    $configured = rtrim(env_string('SITE_URL'), '/');
    if ($configured !== '') {
        return $configured;
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptBase = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/site_g/PHP')), '/');
    $appBase = preg_replace('#/PHP$#', '', $scriptBase) ?: '';
    return $scheme . '://' . $host . $appBase;
}

function app_url(string $path, array $query = []): string {
    $path = ltrim($path, '/');
    $url = app_base_url() . '/' . $path;
    if (!empty($query)) {
        $url .= '?' . http_build_query($query);
    }
    return $url;
}

function email_log_path(): string {
    $configured = env_string('MAIL_LOG_PATH');
    if ($configured !== '') {
        return $configured;
    }
    return dirname(__DIR__) . '/logs/email_log.txt';
}

function log_dev_email(string $to, string $subject, string $text): void {
    $logFile = email_log_path();
    $dir = dirname($logFile);
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    $entry = sprintf(
        "[%s] To: %s | Subject: %s\n%s\n---\n",
        date('Y-m-d H:i:s'),
        $to,
        $subject,
        $text
    );
    @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

function smtp_read($socket): array {
    $response = '';
    $code = 0;
    while (($line = fgets($socket, 515)) !== false) {
        $response .= $line;
        if (preg_match('/^(\d{3})(\s|-)/', $line, $m)) {
            $code = (int)$m[1];
            if ($m[2] === ' ') {
                break;
            }
        }
    }
    return [$code, $response];
}

function smtp_command($socket, string $command, array $acceptedCodes): string {
    fwrite($socket, $command . "\r\n");
    [$code, $response] = smtp_read($socket);
    if (!in_array($code, $acceptedCodes, true)) {
        throw new RuntimeException('SMTP command failed: ' . strtok($command, ' ') . ' -> ' . trim($response));
    }
    return $response;
}

function smtp_send_mail(string $to, string $subject, string $html, string $text): bool {
    $host = env_string('MAIL_HOST');
    if ($host === '') {
        return false;
    }

    $port = (int)env_string('MAIL_PORT', '25');
    $username = env_string('MAIL_USERNAME');
    $password = env_string('MAIL_PASSWORD');
    $from = env_string('MAIL_FROM', 'noreply@offbyone-academy.local');
    $fromName = env_string('MAIL_FROM_NAME', 'OffByOne Academy');
    $encryption = strtolower(env_string('MAIL_ENCRYPTION', $port === 465 ? 'ssl' : 'none'));

    $remote = ($encryption === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
    $socket = @stream_socket_client($remote, $errno, $errstr, 10, STREAM_CLIENT_CONNECT);
    if (!$socket) {
        throw new RuntimeException("SMTP connection failed: {$errstr} ({$errno})");
    }
    stream_set_timeout($socket, 10);

    try {
        [$code, $banner] = smtp_read($socket);
        if ($code !== 220) {
            throw new RuntimeException('SMTP banner invalid: ' . trim($banner));
        }

        smtp_command($socket, 'EHLO offbyone-academy.local', [250]);
        if ($encryption === 'tls') {
            smtp_command($socket, 'STARTTLS', [220]);
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new RuntimeException('SMTP STARTTLS failed');
            }
            smtp_command($socket, 'EHLO offbyone-academy.local', [250]);
        }

        if ($username !== '') {
            smtp_command($socket, 'AUTH LOGIN', [334]);
            smtp_command($socket, base64_encode($username), [334]);
            smtp_command($socket, base64_encode($password), [235]);
        }

        smtp_command($socket, 'MAIL FROM:<' . $from . '>', [250]);
        smtp_command($socket, 'RCPT TO:<' . $to . '>', [250, 251]);
        smtp_command($socket, 'DATA', [354]);

        $boundary = 'b_' . bin2hex(random_bytes(12));
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers = [
            'Date: ' . date(DATE_RFC2822),
            'From: ' . mb_encode_mimeheader($fromName, 'UTF-8') . ' <' . $from . '>',
            'To: <' . $to . '>',
            'Subject: ' . $encodedSubject,
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        ];
        $message =
            implode("\r\n", $headers) . "\r\n\r\n" .
            "--{$boundary}\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n{$text}\r\n\r\n" .
            "--{$boundary}\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n{$html}\r\n\r\n" .
            "--{$boundary}--\r\n";
        $message = preg_replace('/^\./m', '..', $message);
        fwrite($socket, $message . "\r\n.\r\n");
        [$code, $response] = smtp_read($socket);
        if ($code !== 250) {
            throw new RuntimeException('SMTP DATA failed: ' . trim($response));
        }
        smtp_command($socket, 'QUIT', [221]);
        fclose($socket);
        return true;
    } catch (Throwable $e) {
        fclose($socket);
        throw $e;
    }
}

function send_app_mail(string $to, string $subject, string $html, string $text = ''): bool {
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    if ($text === '') {
        $text = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $html)));
    }

    try {
        if (smtp_send_mail($to, $subject, $html, $text)) {
            return true;
        }
    } catch (Throwable $e) {
        error_log('send_app_mail SMTP failed: ' . $e->getMessage());
    }

    log_dev_email($to, $subject, $text);
    return false;
}

/**
 * Regenerează ID-ul de sesiune pentru a preveni session fixation.
 * Apelează după autentificare cu succes.
 */
function regenerate_session() {
    session_regenerate_id(true);
}

/**
 * Asigură existența tabelului pentru rate limiting.
 */
function ensure_rate_limit_table(mysqli $con) {
    $sql = "CREATE TABLE IF NOT EXISTS rate_limit_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        identifier VARCHAR(64) NOT NULL,
        action VARCHAR(40) NOT NULL,
        attempt_count INT DEFAULT 1,
        window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ident_action (identifier, action)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    if (!$con->query($sql)) {
        error_log('ensure_rate_limit_table failed: ' . $con->error);
    }
}

/**
 * Verifică rate limiting pentru o acțiune.
 * @param mysqli $con Conexiunea la DB
 * @param string $action Numele acțiunii (ex: 'login', 'ai_chat')
 * @param string $identifier Identificator unic (IP sau Username)
 * @param int $max_attempts Maxim de încercări
 * @param int $window_seconds Fereastra de timp în secunde
 * @return bool True dacă este permis, False altfel
 */
function check_rate_limit(mysqli $con, $action, $identifier, $max_attempts = 5, $window_seconds = 900) {
    ensure_rate_limit_table($con);
    
    $today = date('Y-m-d H:i:s');
    // FIX [L4]: Utilizare SHA-256 în loc de MD5 pentru hashing identificator (privacy)
    $identifier = hash('sha256', $identifier); 
    
    // Curățăm înregistrările vechi (optional, pentru a menține tabela mică)
    // mysqli_query($con, "DELETE FROM rate_limit_attempts WHERE window_start < NOW() - INTERVAL 1 DAY");

    $sql = "SELECT id, attempt_count, window_start FROM rate_limit_attempts WHERE identifier = ? AND action = ?";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        error_log('check_rate_limit prepare select failed: ' . $con->error);
        return true;
    }
    $stmt->bind_param('ss', $identifier, $action);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();

    if (!$row) {
        // Prima încercare
        $insert = "INSERT INTO rate_limit_attempts (identifier, action, attempt_count, window_start) VALUES (?, ?, 1, NOW())";
        $stmt = $con->prepare($insert);
        if (!$stmt) {
            error_log('check_rate_limit prepare insert failed: ' . $con->error);
            return true;
        }
        $stmt->bind_param('ss', $identifier, $action);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    $id = $row['id'];
    $count = (int)$row['attempt_count'];
    $start = strtotime($row['window_start']);

    if (time() - $start > $window_seconds) {
        // Fereastra a expirat, resetăm
        $update = "UPDATE rate_limit_attempts SET attempt_count = 1, window_start = NOW() WHERE id = ?";
        $stmt = $con->prepare($update);
        if (!$stmt) {
            error_log('check_rate_limit prepare reset failed: ' . $con->error);
            return true;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    // Incrementăm
    $count++;
    $update = "UPDATE rate_limit_attempts SET attempt_count = ? WHERE id = ?";
    $stmt = $con->prepare($update);
    if (!$stmt) {
        error_log('check_rate_limit prepare increment failed: ' . $con->error);
        return true;
    }
    $stmt->bind_param('ii', $count, $id);
    $stmt->execute();
    $stmt->close();

    return $count <= $max_attempts;
}

/**
 * Resetează contorul de rate limiting.
 */
function reset_rate_limit(mysqli $con, $action, $identifier) {
    // FIX [L4]: Aliniere cu check_rate_limit (SHA-256)
    $identifier = hash('sha256', $identifier);
    $sql = "DELETE FROM rate_limit_attempts WHERE identifier = ? AND action = ?";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        error_log('reset_rate_limit prepare failed: ' . $con->error);
        return;
    }
    $stmt->bind_param('ss', $identifier, $action);
    $stmt->execute();
    $stmt->close();
}

/**
 * Verifică token-ul CSRF pentru cereri AJAX (trimis în header).
 * @return bool True dacă token-ul este valid, False altfel
 */
function verify_csrf_ajax() {
    // Verificăm dacă există token în header-ul X-CSRF-Token
    $token = '';
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        if (is_array($headers)) {
            $token = $headers['X-CSRF-Token'] ?? $headers['x-csrf-token'] ?? '';
        }
    }
    if ($token === '') {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    }
    
    if (empty($token) || !isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Returnează token-ul CSRF curent (pentru a-l folosi în JavaScript).
 * @return string Token-ul CSRF
 */
function get_csrf_token() {
    return generate_csrf_token();
}

/**
 * FEATURE [F2]: Extrage logica de validare parolă
 * Verifică dacă parola are minim 8 caractere, și cel puțin o literă și o cifră.
 * @param string $password
 * @return bool True dacă e validă, False altfel
 */
function validate_password_complexity(string $password): bool {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Za-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    return true;
}

/**
 * Înregistrează o acțiune administrativă în admin_audit_log.
 * Apelat din admin_actions.php după fiecare change_role / reset_progress / delete_user.
 *
 * @param mysqli $con Conexiunea MySQLi
 * @param string $action_type Tipul acțiunii ('change_role', 'reset_progress', 'delete_user')
 * @param int|null $target_user_id ID-ul utilizatorului afectat
 * @param string|null $target_username Username-ul utilizatorului afectat (snapshot)
 * @param string|null $details JSON sau text liber cu detalii suplimentare (ex: rolul nou)
 * @return bool true dacă logarea a reușit
 */
function log_admin_action(mysqli $con, $action_type, $target_user_id = null, $target_username = null, $details = null) {
    if (empty($_SESSION['user_id'])) {
        return false;
    }
    $admin_user_id = (int)$_SESSION['user_id'];
    $admin_username = (string)($_SESSION['username'] ?? 'unknown');
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? mb_substr($_SERVER['HTTP_USER_AGENT'], 0, 255) : null;

    $sql = "INSERT INTO admin_audit_log
            (admin_user_id, admin_username, action_type, target_user_id, target_username, details, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param(
            "ississss",
            $admin_user_id, $admin_username, $action_type,
            $target_user_id, $target_username, $details, $ip, $ua
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
    return false;
}
/**
 * Verifică dacă utilizatorul îndeplinește criteriile pentru achievements neacordate
 * și le inserează în user_achievements. Returnează lista achievement-urilor deblocate acum.
 * FEATURE [F5]: Achievements System
 */
function check_and_award_achievements(mysqli $con, int $user_id): array {
    $locked = false;
    $unlocked = [];

    try {
        $locked = $con->begin_transaction();
        if ($locked) {
            if ($lockStmt = $con->prepare("SELECT achievement_id FROM user_achievements WHERE user_id = ? FOR UPDATE")) {
                $lockStmt->bind_param('i', $user_id);
                $lockStmt->execute();
                $lockStmt->close();
            }
        }

        // Obține achievements neacordate încă
        $sql = "SELECT a.* FROM achievements a
                WHERE a.id NOT IN (SELECT achievement_id FROM user_achievements WHERE user_id = ?)";

        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $rs = $stmt->get_result();
            while ($a = $rs->fetch_assoc()) {
                $met = false;
                switch ($a['criteria_type']) {
                    case 'first_login':
                        $met = true;
                        break;
                    case 'grile_count':
                        if ($s = $con->prepare("SELECT COUNT(*) c FROM progres_grile WHERE id_utilizator = ?")) {
                            $s->bind_param('i', $user_id);
                            $s->execute();
                            $row = $s->get_result()->fetch_assoc();
                            $met = $row && (int)$row['c'] >= (int)$a['criteria_value'];
                            $s->close();
                        }
                        break;
                    case 'exercise_count':
                        if ($s = $con->prepare("SELECT COUNT(*) c FROM learning_exercise_progress WHERE user_id = ?")) {
                            $s->bind_param('i', $user_id);
                            $s->execute();
                            $row = $s->get_result()->fetch_assoc();
                            $met = $row && (int)$row['c'] >= (int)$a['criteria_value'];
                            $s->close();
                        }
                        break;
                    case 'algorithm_completed':
                        $meta = $a['criteria_meta'];
                        if ($s2 = $con->prepare("SELECT COUNT(DISTINCT g.id) c FROM progres_grile p JOIN grile_cpp g ON g.id = p.id_grila WHERE p.id_utilizator = ? AND LOWER(g.nume_metoda) LIKE ?")) {
                            $like = '%' . $meta . '%';
                            $s2->bind_param('is', $user_id, $like);
                            $s2->execute();
                            $row = $s2->get_result()->fetch_assoc();
                            $met = $row && (int)$row['c'] >= 1;
                            $s2->close();
                        }
                        break;
                    case 'streak_days':
                        if ($s = $con->prepare("SELECT current_streak FROM user_streak WHERE user_id = ?")) {
                            $s->bind_param('i', $user_id);
                            $s->execute();
                            $row = $s->get_result()->fetch_assoc();
                            if ($row) {
                                $met = (int)$row['current_streak'] >= (int)$a['criteria_value'];
                            }
                            $s->close();
                        }
                        break;
                }
                if ($met) {
                    if ($s3 = $con->prepare("INSERT IGNORE INTO user_achievements (user_id, achievement_id) VALUES (?, ?)")) {
                        $s3->bind_param('ii', $user_id, $a['id']);
                        if ($s3->execute() && $s3->affected_rows > 0) {
                            $unlocked[] = $a;
                        }
                        $s3->close();
                    }
                }
            }
            $stmt->close();
        }

        if ($locked) {
            $con->commit();
        }
    } catch (Throwable $e) {
        if ($locked) {
            try {
                $con->rollback();
            } catch (Throwable $rollbackError) {
                // noop
            }
        }
        error_log('check_and_award_achievements failed: ' . $e->getMessage());
        return [];
    }

    return $unlocked;
}

?>
