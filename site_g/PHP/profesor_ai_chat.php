<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metoda nepermisă.']);
    exit;
}

// Verificăm Rate Limiting pe baza adresei IP și sesiunii
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_identifier = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : $_SERVER['REMOTE_ADDR'];
$rate_limit_key = 'rate_limit_' . md5($user_identifier);
$rate_limit_messages = (int)(getenv('RATE_LIMIT_MESSAGES') ?: 20);
$rate_limit_window = (int)(getenv('RATE_LIMIT_WINDOW') ?: 3600);

// Inițializăm sau actualizăm contorul de rate limiting
$current_time = time();
if (!isset($_SESSION[$rate_limit_key])) {
    $_SESSION[$rate_limit_key] = [
        'count' => 0,
        'window_start' => $current_time
    ];
}

$rate_data = $_SESSION[$rate_limit_key];

// Verificăm dacă fereastra de timp a expirat
if ($current_time - $rate_data['window_start'] > $rate_limit_window) {
    // Resetează contorul dacă fereastra a expirat
    $rate_data = [
        'count' => 0,
        'window_start' => $current_time
    ];
}

// Incrementez contorul
$rate_data['count']++;
$_SESSION[$rate_limit_key] = $rate_data;

// Verificez dacă utilizatorul a depășit limita
if ($rate_data['count'] > $rate_limit_messages) {
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

$apiKey = getenv('GEMINI_API_KEY');
if (!$apiKey && isset($_ENV['GEMINI_API_KEY'])) {
    $apiKey = $_ENV['GEMINI_API_KEY'];
}

if (!$apiKey && isset($_SERVER['GEMINI_API_KEY'])) {
    $apiKey = $_SERVER['GEMINI_API_KEY'];
}

if (!$apiKey) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Lipsește cheia API. Configurează variabila de mediu GEMINI_API_KEY pe server.'
    ]);
    exit;
}

$systemPrompt = "Ești un profesor de programare C++ experimentat, răbdător și încurajator. Obiectivul tău este să ajuți elevii să învețe. Când un elev îți pune o întrebare sau îți arată un cod greșit, NU îi da soluția directă imediat. Explică-i conceptul, arată-i unde greșește și ghidează-l cu indicii pentru a găsi singur răspunsul corect. Folosește exemple scurte de cod pentru a ilustra teoria. Vorbește în limba română.";

$contents = [];
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

        $contents[] = [
            'role' => $role === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => $text]],
        ];
    }
}

$contents[] = [
    'role' => 'user',
    'parts' => [['text' => $message]],
];

$payload = [
    'system_instruction' => [
        'parts' => [
            ['text' => $systemPrompt]
        ]
    ],
    'contents' => $contents,
    'generationConfig' => [
        'temperature' => 0.6,
        'maxOutputTokens' => 700,
    ]
];

$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . urlencode($apiKey);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$curlErr = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => 'Eroare rețea către model: ' . $curlErr]);
    exit;
}

$data = json_decode($response, true);

if ($httpCode >= 400) {
    $err = $data['error']['message'] ?? 'Răspuns invalid de la model.';
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => $err]);
    exit;
}

$reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
$reply = trim((string)$reply);

if ($reply === '') {
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => 'Modelul nu a returnat text.']);
    exit;
}

echo json_encode(['ok' => true, 'reply' => $reply], JSON_UNESCAPED_UNICODE);
