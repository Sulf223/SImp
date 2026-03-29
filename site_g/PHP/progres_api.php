<?php
header('Content-Type: application/json; charset=UTF-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metoda nepermisa']);
    exit;
}

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Autentificare necesara']);
    exit;
}

require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/progres_learning.php';

$payload = json_decode(file_get_contents('php://input'), true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Payload invalid']);
    exit;
}

$action = trim((string)($payload['action'] ?? ''));
$lesson = trim((string)($payload['lesson'] ?? ''));
$userId = (int)$_SESSION['user_id'];
$lessons = get_fundamental_lessons();

if ($lesson !== '' && !isset($lessons[$lesson])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Lectie necunoscuta']);
    exit;
}

if ($action === 'mark_lesson_visit') {
    $title = $lessons[$lesson]['title'] ?? $lesson;
    $link = $lessons[$lesson]['link'] ?? 'index.php?page=sortare';
    track_lesson_visit($con, $userId, $lesson, $title, $link);
    $continue = get_continue_learning($con, $userId);

    echo json_encode([
        'ok' => true,
        'continue' => $continue,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'mark_exercise_complete') {
    $exerciseKey = trim((string)($payload['exerciseKey'] ?? ''));
    if ($exerciseKey === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'exerciseKey lipsa']);
        exit;
    }

    $progress = track_exercise_completion($con, $userId, $lesson, $exerciseKey);
    $stats = get_exercise_stats($con, $userId, $lesson);

    echo json_encode([
        'ok' => true,
        'progress' => $progress,
        'stats' => $stats,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'error' => 'Actiune necunoscuta']);
