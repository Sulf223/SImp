<?php
require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/helpers.php';

header('Content-Type: application/json; charset=UTF-8');

function metoda_sterge_json(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

// Verificăm că request-ul este POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    metoda_sterge_json(400, ['ok' => false, 'error' => 'Metodă invalidă.']);
}

enforce_session_timeout_ajax();

if (empty($_SESSION['user_id']) || ($_SESSION['rol'] ?? 'user') !== 'admin') {
    metoda_sterge_json(403, ['ok' => false, 'error' => 'Acces interzis.']);
}

// Verificăm CSRF fără redirect/text plain.
$requestToken = get_csrf_token_from_request();
$sessionToken = $_SESSION['csrf_token'] ?? '';
if (!is_string($sessionToken) || $sessionToken === '' || $requestToken === '' || !hash_equals($sessionToken, $requestToken)) {
    metoda_sterge_json(403, ['ok' => false, 'error' => 'Token CSRF invalid.']);
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    metoda_sterge_json(400, ['ok' => false, 'error' => 'ID metodă invalid.']);
}

// --- Securizare cu Prepared Statements ---
$sql = "DELETE FROM metode WHERE id_metoda = ?";
$stmt = $con->prepare($sql);
if (!$stmt) {
    error_log("Eroare pregătire ștergere: " . $con->error);
    metoda_sterge_json(500, ['ok' => false, 'error' => 'Nu pot pregăti ștergerea metodei.']);
}

$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    error_log("Eroare la ștergere metoda ID $id: " . $stmt->error);
    $stmt->close();
    metoda_sterge_json(500, ['ok' => false, 'error' => 'Nu pot șterge metoda.']);
}

$deleted = $stmt->affected_rows;
$stmt->close();
metoda_sterge_json(200, ['ok' => true, 'deleted' => $deleted]);
