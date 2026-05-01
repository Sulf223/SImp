<?php
header('Content-Type: application/json; charset=UTF-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'helpers.php';
require_once 'conexiune.php';

// Verificăm CSRF pentru cereri AJAX
if (!verify_csrf_ajax()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Eroare CSRF: Cerere neautorizată.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metoda nepermisă.']);
    exit;
}

$user_identifier = !empty($_SESSION['user_id']) ? 'user_' . $_SESSION['user_id'] : $_SERVER['REMOTE_ADDR'];
$rate_limit_messages = (int)(getenv('RATE_LIMIT_MESSAGES') ?: 20);
$rate_limit_window = (int)(getenv('RATE_LIMIT_WINDOW') ?: 3600);

if (!check_rate_limit($con, 'ai_chat', $user_identifier, $rate_limit_messages, $rate_limit_window)) {
    http_response_code(429);
    echo json_encode([
        'ok' => false,
        'error' => 'Prea multe mesaje în scurt timp. Te rugăm să aștepți înainte de a trimite alt mesaj.'
    ]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Payload JSON invalid.']);
    exit;
}

$message = trim((string)($input['message'] ?? ''));
$history = $input['history'] ?? [];

if ($message === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Mesajul nu poate fi gol.']);
    exit;
}

$apiKey = getenv('GROQ_API_KEY');
if (!$apiKey && isset($_ENV['GROQ_API_KEY'])) {
    $apiKey = $_ENV['GROQ_API_KEY'];
}

if (!$apiKey && isset($_SERVER['GROQ_API_KEY'])) {
    $apiKey = $_SERVER['GROQ_API_KEY'];
}

if (!$apiKey) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Lipsește cheia API. Configurează variabila de mediu GROQ_API_KEY pe server.'
    ]);
    exit;
}

$model = trim((string)(getenv('GROQ_MODEL') ?: 'llama-3.3-70b-versatile'));
$systemPrompt = "Ești un profesor de programare C++ experimentat, răbdător și încurajator. Obiectivul tău este să ajuți elevii să învețe. Când un elev îți pune o întrebare sau îți arată un cod greșit, NU îi da soluția directă imediat. Explică-i conceptul, arată-i unde greșește și ghidează-l cu indicii pentru a găsi singur răspunsul corect. Folosește exemple scurte de cod pentru a ilustra teoria. Vorbește în limba română.";

$messages = [
    [
        'role' => 'system',
        'content' => $systemPrompt,
    ],
];

if (is_array($history)) {
    foreach ($history as $item) {
        if (!is_array($item)) {
            continue;
        }

        $role = (string)($item['role'] ?? 'user');
        $text = trim((string)($item['text'] ?? ''));
        if ($text === '') {
            continue;
        }

        $messages[] = [
            'role' => $role === 'assistant' ? 'assistant' : 'user',
            'content' => $text,
        ];
    }
}

$messages[] = [
    'role' => 'user',
    'content' => $message,
];

$payload = [
    'model' => $model,
    'messages' => $messages,
    'temperature' => 0.6,
    'max_tokens' => 700,
];

$url = 'https://api.groq.com/openai/v1/chat/completions';
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$curlErr = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => 'Eroare rețea către Groq: ' . $curlErr]);
    exit;
}

$data = json_decode($response, true);

if ($httpCode >= 400) {
    $err = trim((string)($data['error']['message'] ?? 'Răspuns invalid de la Groq.'));
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => $err]);
    exit;
}

$reply = trim((string)($data['choices'][0]['message']['content'] ?? ''));
if ($reply === '') {
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => 'Modelul nu a returnat text.']);
    exit;
}

echo json_encode(['ok' => true, 'reply' => $reply, 'model' => $model], JSON_UNESCAPED_UNICODE);
