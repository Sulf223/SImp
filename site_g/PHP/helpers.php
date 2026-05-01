<?php
// PHP/helpers.php - Funcții ajutătoare pentru Flash Messages și CSRF

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Setează un mesaj flash (care va fi afișat o singură dată).
 * @param string $type 'success', 'error', 'info'
 * @param string $message Mesajul de afișat
 */
function set_flash($type, $message) {
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Afișează mesajele flash salvate în sesiune și le șterge.
 */
function display_flash() {
    if (!empty($_SESSION['flash_messages'])) {
        foreach ($_SESSION['flash_messages'] as $msg) {
            $class = 'alert';
            if ($msg['type'] === 'error') $class .= ' alert-error';
            elseif ($msg['type'] === 'success') $class .= ' alert-success';
            else $class .= ' alert-info';
            
            echo '<div class="' . $class . '">' . htmlspecialchars($msg['message']) . '</div>';
        }
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
    echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Verifică token-ul CSRF primit prin POST.
 * Oprește execuția dacă token-ul este invalid.
 */
function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Eroare CSRF: Token invalid sau lipsă. Te rog reîncarcă pagina.');
        }
    }
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
    mysqli_query($con, $sql);
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
    $identifier = md5($identifier); // Hash identifier for privacy if it's an IP
    
    // Curățăm înregistrările vechi (optional, pentru a menține tabela mică)
    // mysqli_query($con, "DELETE FROM rate_limit_attempts WHERE window_start < NOW() - INTERVAL 1 DAY");

    $sql = "SELECT id, attempt_count, window_start FROM rate_limit_attempts WHERE identifier = ? AND action = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $identifier, $action);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$row) {
        // Prima încercare
        $insert = "INSERT INTO rate_limit_attempts (identifier, action, attempt_count, window_start) VALUES (?, ?, 1, NOW())";
        $stmt = mysqli_prepare($con, $insert);
        mysqli_stmt_bind_param($stmt, 'ss', $identifier, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    }

    $id = $row['id'];
    $count = (int)$row['attempt_count'];
    $start = strtotime($row['window_start']);

    if (time() - $start > $window_seconds) {
        // Fereastra a expirat, resetăm
        $update = "UPDATE rate_limit_attempts SET attempt_count = 1, window_start = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($con, $update);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    }

    // Incrementăm
    $count++;
    $update = "UPDATE rate_limit_attempts SET attempt_count = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $update);
    mysqli_stmt_bind_param($stmt, 'ii', $count, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $count <= $max_attempts;
}

/**
 * Resetează contorul de rate limiting.
 */
function reset_rate_limit(mysqli $con, $action, $identifier) {
    $identifier = md5($identifier);
    $sql = "DELETE FROM rate_limit_attempts WHERE identifier = ? AND action = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $identifier, $action);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

/**
 * Verifică token-ul CSRF pentru cereri AJAX (trimis în header).
 * @return bool True dacă token-ul este valid, False altfel
 */
function verify_csrf_ajax() {
    // Verificăm dacă există token în header-ul X-CSRF-Token
    $headers = getallheaders();
    $token = $headers['X-CSRF-Token'] ?? $headers['x-csrf-token'] ?? '';
    
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
?>
