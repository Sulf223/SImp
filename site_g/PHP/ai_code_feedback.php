<?php
// PHP/ai_code_feedback.php
require_once 'conexiune.php';
require_once 'helpers.php';
require_once 'documentation_context.php';
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metodă nepermisă.']);
    exit;
}

// FIX [A2]: Session timeout pentru AJAX
enforce_session_timeout_ajax();

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Trebuie să fii autentificat pentru a cere feedback.']);
    exit;
}

// FIX [A8]: Fallback pentru nginx
$token = get_csrf_token_from_request();
if (!$token || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Eroare CSRF. Reîncarcă pagina.']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

// Rate Limit: 10 per hour
if (!check_rate_limit($con, 'ai_feedback', (string)$user_id, 10, 3600)) {
    http_response_code(429);
    echo json_encode(['ok' => false, 'error' => 'Ai depășit limita de cereri. Încearcă din nou mai târziu.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Payload JSON invalid.']);
    exit;
}

$code = $data['code'] ?? '';
$context = $data['context'] ?? '';

if (empty(trim($code))) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Codul sursă este gol.']);
    exit;
}

if (mb_strlen($code) > 5000) {
    http_response_code(413);
    echo json_encode(['ok' => false, 'error' => 'Codul sursă este prea lung (max 5000 caractere).']);
    exit;
}

if (mb_strlen((string)$context, 'UTF-8') > 1000) {
    $context = mb_substr((string)$context, 0, 1000, 'UTF-8');
}

$docContext = documentation_context_for_query($code . ' ' . (string)$context, 4500, 4);
$sourceList = !empty($docContext['sources']) ? implode(', ', $docContext['sources']) : 'niciun fișier găsit';
$contextText = $docContext['text'] !== ''
    ? $docContext['text']
    : 'Nu există fragmente relevante disponibile în indexul proiect_documentatie.';

function local_code_feedback_fallback(string $code, string $contextText, array $sources): string {
    $sourceText = !empty($sources) ? implode(', ', array_slice($sources, 0, 3)) : 'proiect_documentatie';
    $tips = [];

    if (preg_match('/for\s*\(|while\s*\(/i', $code)) {
        $tips[] = 'Verifică limitele buclelor: inițializarea, condiția de oprire și incrementarea trebuie să nu sară peste elemente.';
    }
    if (preg_match('/swap|aux|temp/i', $code)) {
        $tips[] = 'La interschimbare, păstrează o valoare temporară și asigură-te că ultima atribuire pune valoarea salvată la locul corect.';
    }
    if (preg_match('/pivot|quick/i', $code)) {
        $tips[] = 'La Quick Sort, urmărește dacă pivotul separă corect elementele mai mici și mai mari.';
    }
    if (preg_match('/freq|fr\[|count/i', $code)) {
        $tips[] = 'La sortarea prin numărare, indexul din vectorul de frecvență trebuie să fie valoarea elementului, iar intervalul trebuie să fie rezonabil.';
    }

    if (empty($tips)) {
        $tips[] = 'Verifică dacă datele de intrare sunt citite corect și dacă rezultatul este afișat după prelucrare.';
        $tips[] = 'Compară codul cu pseudocodul din lecție și urmărește pașii în laboratorul vizual.';
    }

    $contextSnippet = trim(preg_replace('/\s+/', ' ', $contextText));
    if (mb_strlen($contextSnippet, 'UTF-8') > 360) {
        $contextSnippet = mb_substr($contextSnippet, 0, 360, 'UTF-8') . '...';
    }

    return "Feedback local: serviciul AI extern nu este disponibil momentan, deci îți dau o analiză de rezervă pe baza documentației proiectului.\n\n" .
        "Observații:\n- " . implode("\n- ", $tips) . "\n\n" .
        "Surse folosite: {$sourceText}.\n" .
        "Fragment relevant: {$contextSnippet}";
}

$api_key = getenv('GROQ_API_KEY');
if (!$api_key && defined('GROQ_API_KEY')) {
    $api_key = GROQ_API_KEY;
}

if (!$api_key) {
    echo json_encode([
        'ok' => true,
        'feedback' => local_code_feedback_fallback($code, $contextText, $docContext['sources']),
        'fallback' => true,
        'sources' => $docContext['sources']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$system_prompt = "Ești un mentor C++ răbdător. Analizează codul de mai jos folosind ca reper și fragmentele din proiect_documentatie. Evidențiază:\n" .
                 "1. Erori sintactice sau logice (dacă există)\n" .
                 "2. Probleme de stil (nume variabile, formatare, indentare)\n" .
                 "3. Sugestii de optimizare (complexitate, alocări inutile)\n" .
                 "4. Bune practici care lipsesc\n" .
                 "5. Legătura cu exemplele/documentația proiectului, când este relevant\n" .
                 "Nu da soluția completă — explică conceptual și ghidează studentul.\n" .
                 "Răspunde în română, max 250 cuvinte, structurat cu titluri scurte.\n\n" .
                 "Surse disponibile: $sourceList\n\n" .
                 "Context din proiect_documentatie:\n$contextText";

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
    echo json_encode([
        'ok' => true,
        'feedback' => local_code_feedback_fallback($code, $contextText, $docContext['sources']),
        'fallback' => true,
        'sources' => $docContext['sources']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
if ($http_code !== 200) {
    error_log("ai_code_feedback HTTP {$http_code}: " . substr((string)$response, 0, 500));
    if ($http_code === 429) {
        echo json_encode([
            'ok' => true,
            'feedback' => local_code_feedback_fallback($code, $contextText, $docContext['sources']),
            'fallback' => true,
            'sources' => $docContext['sources']
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => 'AI a răspuns cu eroare (HTTP ' . $http_code . ').']);
    exit;
}

$json = json_decode($response, true);
if (!isset($json['choices'][0]['message']['content'])) {
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => 'Răspuns invalid de la AI.']);
    exit;
}
echo json_encode(['ok' => true, 'feedback' => trim($json['choices'][0]['message']['content'])]);
