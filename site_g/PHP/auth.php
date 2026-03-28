<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifică dacă utilizatorul este logat.
 * @return bool
 */
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Verifică dacă utilizatorul logat are rolul de admin.
 * @return bool
 */
function is_admin(): bool {
    return is_logged_in() && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

/**
 * Restricționează accesul la o pagină doar pentru anumiți utilizatori.
 *
 * @param string $role Rolul necesar ('user' sau 'admin'). Dacă este 'user', permite și adminilor.
 */
function require_role(string $role = 'user'): void {
    if (!is_logged_in()) {
        header("Location: ../index.php?page=login&required_auth=true");
        exit;
    }

    $user_role = $_SESSION['rol'] ?? 'user';

    // Dacă rolul necesar este 'admin', doar adminii au voie.
    if ($role === 'admin' && $user_role !== 'admin') {
        http_response_code(403);
        // Poți crea o pagină dedicată pentru erori de acces
        die("Acces interzis. Doar administratorii pot accesa această pagină.");
    }
    
    // Dacă rolul necesar este 'user', oricine logat (user sau admin) are voie.
    // Această condiție este implicit acoperită de verificarea inițială `is_logged_in`.
}

// O funcție wrapper pentru a menține compatibilitatea cu codul vechi
function require_login(): void {
    require_role('user');
}
