<?php
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/helpers.php';

enforce_session_timeout_ajax();

if (!is_logged_in()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Autentificare necesară.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metodă nepermisă.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!verify_csrf_ajax()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Token CSRF invalid.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$input = is_array($input) ? $input : [];

$grilaId = (int)($input['id_grila'] ?? 0);
$selectedAnswer = (int)($input['selected_answer'] ?? 0);
$isCorrect = !empty($input['is_correct']) ? 1 : 0;
$userId = (int)$_SESSION['user_id'];

if (!check_rate_limit($con, 'quiz_attempt', 'user_' . $userId, 180, 3600)) {
    http_response_code(429);
    echo json_encode(['ok' => false, 'error' => 'Prea multe încercări într-un timp scurt.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($grilaId <= 0 || $selectedAnswer < 1 || $selectedAnswer > 4) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Date invalide pentru încercare.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$con->query("CREATE TABLE IF NOT EXISTS quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    grila_id INT NOT NULL,
    selected_answer TINYINT NOT NULL,
    is_correct TINYINT(1) NOT NULL DEFAULT 0,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_quiz_attempt_user_time (user_id, attempted_at),
    INDEX idx_quiz_attempt_grila (grila_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$check = $con->prepare("SELECT 1 FROM grile_cpp WHERE id = ? LIMIT 1");
if (!$check) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Nu pot valida grila.'], JSON_UNESCAPED_UNICODE);
    exit;
}
$check->bind_param('i', $grilaId);
$check->execute();
$exists = $check->get_result()->fetch_assoc();
$check->close();

if (!$exists) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Grila nu există.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $con->prepare("INSERT INTO quiz_attempts (user_id, grila_id, selected_answer, is_correct) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Nu pot salva încercarea.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param('iiii', $userId, $grilaId, $selectedAnswer, $isCorrect);
$ok = $stmt->execute();
$stmt->close();

echo json_encode(['ok' => (bool)$ok], JSON_UNESCAPED_UNICODE);
