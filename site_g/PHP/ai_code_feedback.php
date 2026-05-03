<?php
// PHP/ai_code_feedback.php
require_once 'conexiune.php';
require_once 'helpers.php';
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Metodă nepermisă.']);
    exit;
}

// FIX [A2]: Session timeout pentru AJAX
enforce_session_timeout_ajax();

if (empty($_SESSION['user_id'])) {
    echo json_encode(['ok' => false, 'error' => 'Trebuie să fii autentificat pentru a cere feedback.']);
    exit;
}

// FIX [A8]: Fallback pentru nginx
$token = get_csrf_token_from_request();
if (!$token || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    echo json_encode(['ok' => false, 'error' => 'Eroare CSRF. Reîncarcă pagina.']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

// Rate Limit: 10 per hour
if (!check_rate_limit($con, 'ai_feedback', (string)$user_id, 10, 3600)) {
    echo json_encode(['ok' => false, 'error' => 'Ai depășit limita de cereri. Încearcă din nou mai târziu.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$code = $data['code'] ?? '';
$context = $data['context'] ?? '';

if (empty(trim($code))) {
    echo json_encode(['ok' => false, 'error' => 'Codul sursă este gol.']);
    exit;
}

if (mb_strlen($code) > 5000) {
    echo json_encode(['ok' => false, 'error' => 'Codul sursă este prea lung (max 5000 caractere).']);
    exit;
}

$api_key = getenv('GROQ_API_KEY');
if (!$api_key && defined('GROQ_API_KEY')) {
    $api_key = GROQ_API_KEY;
}

if (!$api_key) {
    echo json_encode(['ok' => false, 'error' => 'Cheia API Groq nu este configurată pe server.']);
    exit;
}

$system_prompt = "Ești un mentor C++ răbdător. Analizează codul de mai jos. Evidențiază:\n" .
                 "1. Erori sintactice sau logice (dacă există)\n" .
                 "2. Probleme de stil (nume variabile, formatare, indentare)\n" .
                 "3. Sugestii de optimizare (complexitate, alocări inutile)\n" .
                 "4. Bune practici care lipsesc\n" .
                 "Nu da soluția completă — explică conceptual și ghidează studentul.\n" .
                 "Răspunde în română, max 250 cuvinte, structurat cu titluri scurte.";

$messages = [
    ['role' => 'system', 'content' => $system_prompt],
    ['role' => 'user', 'content' => "Cod C++:\n```cpp\n$code\n```\nContext adițional: $context"]
];

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $api_key,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'llama-3.3-70b-versatile',
    'messages' => $messages,
    'max_tokens' => 800,
    'temperature' => 0.4
]));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err = curl_error($ch);
$curl_errno = curl_errno($ch);
curl_close($ch);

if ($response === false) {
    // FIX [A10]: logging erori curl
    error_log("ai_code_feedback curl error #{$curl_errno}: {$curl_err}");
    echo json_encode(['ok' => false, 'error' => 'Serviciul AI este indisponibil. Încearcă mai târziu.']);
    exit;
}
if ($http_code !== 200) {
    error_log("ai_code_feedback HTTP {$http_code}: " . substr((string)$response, 0, 500));
    echo json_encode(['ok' => false, 'error' => 'AI a răspuns cu eroare (HTTP ' . $http_code . ').']);
    exit;
}

$json = json_decode($response, true);
if (!isset($json['choices'][0]['message']['content'])) {
    echo json_encode(['ok' => false, 'error' => 'Răspuns invalid de la AI.']);
    exit;
}
echo json_encode(['ok' => true, 'feedback' => trim($json['choices'][0]['message']['content'])]);
