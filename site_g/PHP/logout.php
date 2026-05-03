<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificăm CSRF pentru logout (trebuie să fie POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    // FIX [SEC]: Invalidare completă a sesiunii — date + cookie pe browser
    $_SESSION = [];

    // Șterge explicit cookie-ul de sesiune din browser
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    // Pornim o sesiune nouă DOAR pentru mesajul flash
    session_start();
    session_regenerate_id(true);
    set_flash('success', 'Ai fost delogat cu succes!');

    header('Location: ../index.php?page=acasa');
    exit;
} else {
    // Dacă nu e POST, redirecționăm — nu afișăm pagină cu instrucțiuni
    header('Location: ../index.php?page=acasa');
    exit;
}
