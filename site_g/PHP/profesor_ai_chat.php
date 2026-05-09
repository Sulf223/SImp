<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once 'helpers.php';
require_once 'documentation_context.php';

// FIX [A2]: Session timeout pentru AJAX
enforce_session_timeout_ajax();

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

if (mb_strlen($message, 'UTF-8') > 1200) {
    http_response_code(413);
    echo json_encode(['ok' => false, 'error' => 'Mesajul este prea lung (maxim 1200 caractere).']);
    exit;
}

// FIX [L1]: Sursă unică pentru API Key (getenv). Eliminare fallback la $_ENV/$_SERVER.
$apiKey = getenv('GROQ_API_KEY') ?: '';

if ($apiKey === '') {
    http_response_code(503);
    echo json_encode([
        'ok' => false,
        'error' => 'Serviciul AI este momentan indisponibil (API key lipsă).'
    ]);
    exit;
}

$model = trim((string)(getenv('GROQ_MODEL') ?: 'llama-3.3-70b-versatile'));

$historyQuery = '';
if (is_array($history)) {
    foreach (array_slice($history, -4) as $item) {
        if (is_array($item) && (($item['role'] ?? 'user') === 'user')) {
            $historyQuery .= ' ' . (string)($item['text'] ?? '');
        }
    }
}
$docContext = documentation_context_for_query($message . ' ' . $historyQuery, 7000, 5);
$sourceList = !empty($docContext['sources']) ? implode(', ', $docContext['sources']) : 'niciun fișier găsit';
$contextText = $docContext['text'] !== ''
    ? $docContext['text']
    : 'Nu există fragmente relevante disponibile în indexul proiect_documentatie.';

$systemPrompt = "Ești un profesor de programare C++ experimentat, răbdător și încurajator. Obiectivul tău este să ajuți elevii să învețe. Când un elev îți pune o întrebare sau îți arată un cod greșit, NU îi da soluția directă imediat. Explică-i conceptul, arată-i unde greșește și ghidează-l cu indicii pentru a găsi singur răspunsul corect. Vorbește în limba română.\n\n" .
    "Răspunde prioritar pe baza fragmentelor extrase din directorul proiect_documentatie. Dacă fragmentul nu acoperă complet întrebarea, spune pe scurt ce lipsește din documentație și completează doar cu explicații generale marcate ca atare. Când folosești o idee din context, menționează natural fișierul sursă relevant.\n\n" .
    "SURSE DISPONIBILE: {$sourceList}\n\n" .
    "CONTEXT DIN proiect_documentatie:\n{$contextText}";

$messages = [
    [
        'role' => 'system',
        'content' => $systemPrompt,
    ],
];

if (is_array($history)) {
    $history = array_slice($history, -8);
    foreach ($history as $item) {
        if (!is_array($item)) {
            continue;
        }

        $role = (string)($item['role'] ?? 'user');
        $text = trim((string)($item['text'] ?? ''));
        if ($text === '') {
            continue;
        }
        if (mb_strlen($text, 'UTF-8') > 1000) {
            $text = mb_substr($text, 0, 1000, 'UTF-8');
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
$curlErrno = curl_errno($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    // FIX [A10]: logging erori curl
    error_log("profesor_ai_chat curl error #{$curlErrno}: {$curlErr}");
    echo json_encode(['ok' => false, 'error' => 'Serviciul AI este indisponibil. Încearcă mai târziu.']);
    exit;
}
if ($httpCode !== 200) {
    error_log("profesor_ai_chat HTTP {$httpCode}: " . substr((string)$response, 0, 500));
    echo json_encode(['ok' => false, 'error' => 'AI a răspuns cu eroare (HTTP ' . $httpCode . ').']);
    exit;
}

$data = json_decode($response, true);
$reply = trim((string)($data['choices'][0]['message']['content'] ?? ''));
if ($reply === '') {
    echo json_encode(['ok' => false, 'error' => 'Răspuns invalid de la AI.']);
    exit;
}

echo json_encode(['ok' => true, 'reply' => $reply, 'model' => $model, 'sources' => $docContext['sources']], JSON_UNESCAPED_UNICODE);
