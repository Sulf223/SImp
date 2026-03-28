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
 * Verifică rate limiting pentru login (previne brute-force).
 * @param string $username Username-ul pentru care verificăm
 * @return bool True dacă este permis, False dacă depășește limita
 */
function check_rate_limit($username) {
    $max_attempts = 5;
    $time_window = 900; // 15 minute în secunde
    
    $key = 'login_attempts_' . md5($username);
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
    }
    
    $attempts = $_SESSION[$key];
    $current_time = time();
    
    // Resetăm dacă a trecut fereastra de timp
    if ($current_time - $attempts['first_attempt'] > $time_window) {
        $_SESSION[$key] = ['count' => 1, 'first_attempt' => $current_time];
        return true;
    }
    
    // Incrementăm numărul de încercări
    $_SESSION[$key]['count']++;
    
    if ($_SESSION[$key]['count'] > $max_attempts) {
        return false;
    }
    
    return true;
}

/**
 * Resetează contorul de rate limiting după login cu succes.
 */
function reset_rate_limit($username) {
    $key = 'login_attempts_' . md5($username);
    unset($_SESSION[$key]);
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
