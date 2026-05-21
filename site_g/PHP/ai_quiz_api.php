<?php
// FIX [Q0]: Explicit UTF-8 handling at file level
mb_internal_encoding('UTF-8');
if (PHP_SAPI === 'cli') {
    setlocale(LC_ALL, 'en_US.UTF-8');
}

if (session_status() === PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json; charset=UTF-8');
header('Content-Language: ro');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once 'helpers.php';
require_once 'documentation_context.php';
require_once 'conexiune.php';
require_once 'auth.php';

// FIX [A2]: Session timeout pentru AJAX
enforce_session_timeout_ajax();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metoda nepermisă.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Trebuie să fii autentificat ca să dai testul AI.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Verificăm CSRF
if (!verify_csrf_ajax()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'CSRF invalid.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$input = is_array($input) ? $input : [];
$action = $input['action'] ?? '';
$pathSlug = $input['path_slug'] ?? 'general';

$allowedActions = ['generate_quiz', 'grade_quiz'];
if (!in_array($action, $allowedActions, true)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Acțiune invalidă.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$identifier = !empty($_SESSION['user_id']) ? 'user_' . (int)$_SESSION['user_id'] : ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
if (!check_rate_limit($con, 'ai_quiz', $identifier, 25, 3600)) {
    http_response_code(429);
    echo json_encode(['ok' => false, 'error' => 'Prea multe cereri pentru quiz. Încearcă din nou mai târziu.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// FIX [L1]: Sursă unică pentru API Key (getenv). Eliminare fallback la $_ENV/$_SERVER.
$apiKey = getenv('GROQ_API_KEY') ?: '';
$apiKeyMissing = ($apiKey === '');

$model = getenv('GROQ_MODEL') ?: 'llama-3.3-70b-versatile';

function ai_quiz_has_code_context(string $question): bool {
    return (bool)preg_match('/```|#include|for\s*\(|while\s*\(|if\s*\(|int\s+\w+\s*\(|void\s+\w+\s*\(|\w+\s*=\s*\w+/iu', $question);
}

function ai_quiz_extract_code_example(string $question): array {
    $code = '';
    $cleanQuestion = preg_replace_callback('/```(?:cpp|c\+\+|c)?\s*([\s\S]*?)```/iu', static function ($matches) use (&$code) {
        if ($code === '') {
            $code = trim((string)($matches[1] ?? ''));
        }
        return ' ';
    }, $question);

    $cleanQuestion = trim((string)$cleanQuestion);
    if ($cleanQuestion === '') {
        $cleanQuestion = 'Analizează fragmentul de cod și alege răspunsul corect.';
    }

    return [$cleanQuestion, $code];
}

function ai_quiz_has_real_code_context(string $question): bool {
    [, $code] = ai_quiz_extract_code_example($question);
    if ($code === '') {
        return ai_quiz_has_code_context($question);
    }

    if (preg_match('/\.\.\./u', $code) && !preg_match('/\b(if|for|while|return)\b|strcmp|swap|=|<|>|\+\+|--/iu', $code)) {
        return false;
    }

    return (bool)preg_match('/\b(if|for|while|return)\b|strcmp|swap|=|<|>|\+\+|--/iu', $code);
}

function ai_quiz_is_memory_question(string $question): bool {
    $q = mb_strtolower($question, 'UTF-8');
    $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $q);
    if ($ascii !== false) {
        $q = $ascii;
    }
    $hasRealCode = ai_quiz_has_real_code_context($question);

    if (preg_match('/ce\s+(face|rol|scop).*functi|care\s+este\s+scopul\s+functiei|ce\s+functie\s+este\s+utilizata|ce\s+tip\s+de\s+sortare\s+este\s+utilizat/iu', $q)) {
        return true;
    }

    if (preg_match('/ce\s+(metoda|algoritm).*functia/iu', $q)) {
        return true;
    }

    if (preg_match('/ce\s+(metoda|algoritm).*aplicatia/iu', $q)) {
        return true;
    }

    if (!$hasRealCode && preg_match('/functia|aplicatia|fisierul|programul\s+(din|numit)|codul|variabila|variabilei|instructiunea|linia|`[^`]+`|ordonare[a-z0-9_]*|[a-z0-9]+_[a-z0-9_]+/iu', $q)) {
        return true;
    }

    return false;
}

function ai_quiz_prepare_db_question(array $question): array {
    [$cleanQuestion, $codeFromQuestion] = ai_quiz_extract_code_example((string)($question['question'] ?? ''));
    $codeExample = trim((string)($question['code_example'] ?? ''));
    if ($codeExample === '') {
        $codeExample = $codeFromQuestion;
    }

    return [
        'question' => $cleanQuestion,
        'code_example' => $codeExample !== '' ? $codeExample : null,
    ];
}

function ai_quiz_ensure_attempts_table(mysqli $con): void {
    $sql = "CREATE TABLE IF NOT EXISTS ai_quiz_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        path_slug VARCHAR(80) NOT NULL DEFAULT 'general',
        score INT NOT NULL,
        total INT NOT NULL,
        percent DECIMAL(5,2) NOT NULL DEFAULT 0,
        feedback_summary TEXT NULL,
        sources_json TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ai_quiz_user_time (user_id, created_at),
        INDEX idx_ai_quiz_user_path (user_id, path_slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    if (!$con->query($sql)) {
        error_log('ai_quiz_ensure_attempts_table failed: ' . $con->error);
    }
}

function ai_quiz_save_attempt(mysqli $con, int $userId, string $pathSlug, int $score, int $total, string $feedback, array $sources): array {
    if ($userId <= 0 || $total <= 0) {
        return ['saved' => false, 'id' => null, 'percent' => 0];
    }

    ai_quiz_ensure_attempts_table($con);
    $percent = round(($score / $total) * 100, 2);
    $safePath = mb_substr($pathSlug !== '' ? $pathSlug : 'general', 0, 80, 'UTF-8');
    $summary = mb_substr(trim($feedback), 0, 1200, 'UTF-8');
    $sourcesJson = json_encode(array_values($sources), JSON_UNESCAPED_UNICODE) ?: '[]';

    $stmt = $con->prepare("INSERT INTO ai_quiz_attempts (user_id, path_slug, score, total, percent, feedback_summary, sources_json) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        error_log('ai_quiz_save_attempt prepare failed: ' . $con->error);
        return ['saved' => false, 'id' => null, 'percent' => $percent];
    }

    $stmt->bind_param('isiidss', $userId, $safePath, $score, $total, $percent, $summary, $sourcesJson);
    $ok = $stmt->execute();
    $id = $ok ? $stmt->insert_id : null;
    if (!$ok) {
        error_log('ai_quiz_save_attempt execute failed: ' . $stmt->error);
    }
    $stmt->close();

    return ['saved' => (bool)$ok, 'id' => $id, 'percent' => $percent];
}

function ai_quiz_store_current_attempt(array $quiz, string $pathSlug): void {
    $questions = [];
    foreach (array_values($quiz) as $index => $question) {
        $questions[] = [
            'index' => $index,
            'question' => (string)($question['question'] ?? ''),
            'correct' => (int)($question['correct'] ?? -1),
        ];
    }

    $_SESSION['ai_quiz_current'] = [
        'path_slug' => mb_substr($pathSlug !== '' ? $pathSlug : 'general', 0, 80, 'UTF-8'),
        'created_at' => time(),
        'questions' => $questions,
    ];
}

function ai_quiz_grade_from_session(array $answers, string $pathSlug): array {
    $attempt = $_SESSION['ai_quiz_current'] ?? null;
    if (!is_array($attempt) || !isset($attempt['questions']) || !is_array($attempt['questions'])) {
        return [false, [], 'Testul a expirat. Generează un test nou înainte de evaluare.'];
    }

    if ((time() - (int)($attempt['created_at'] ?? 0)) > 7200) {
        unset($_SESSION['ai_quiz_current']);
        return [false, [], 'Testul a expirat. Generează un test nou înainte de evaluare.'];
    }

    $expectedPath = (string)($attempt['path_slug'] ?? 'general');
    if ($expectedPath !== mb_substr($pathSlug !== '' ? $pathSlug : 'general', 0, 80, 'UTF-8')) {
        return [false, [], 'Testul curent nu corespunde drumului ales. Generează un test nou.'];
    }

    $selectedByIndex = [];
    foreach ($answers as $answer) {
        if (!is_array($answer) || !isset($answer['qIndex'])) {
            continue;
        }
        $index = (int)$answer['qIndex'];
        $selectedByIndex[$index] = isset($answer['user']) ? (int)$answer['user'] : -1;
    }

    $graded = [];
    foreach (array_values($attempt['questions']) as $index => $question) {
        $correct = (int)($question['correct'] ?? -1);
        $selected = $selectedByIndex[$index] ?? -1;
        $graded[] = [
            'qIndex' => $index,
            'question' => (string)($question['question'] ?? ''),
            'user' => $selected,
            'correct' => $correct,
            'isCorrect' => $selected >= 0 && $selected === $correct,
        ];
    }

    return [true, $graded, ''];
}

function ai_quiz_fallback_questions(string $pathSlug): array {
    return [
        [
            'question' => "În fragmentul de Bubble Sort, ce condiție trebuie pusă în loc de ??? pentru sortare crescătoare?\n```cpp\nfor (int j = 0; j < n - i - 1; j++) {\n    if (???) {\n        swap(v[j], v[j + 1]);\n    }\n}\n```",
            'options' => ['v[j] > v[j + 1]', 'v[j] < v[j + 1]', 'v[j] == v[j + 1]', 'j > v[j]'],
            'correct' => 0,
            'explanation' => 'Pentru sortare crescătoare, elementul mai mare este mutat spre dreapta prin interschimbare.',
        ],
        [
            'question' => "În Selection Sort, ce instrucțiune actualizează poziția minimului?\n```cpp\nint p = i;\nfor (int j = i + 1; j < n; j++) {\n    if (v[j] < v[p]) {\n        ???\n    }\n}\n```",
            'options' => ['p = j;', 'j = p;', 'v[p] = j;', 'p++;'],
            'correct' => 0,
            'explanation' => 'Variabila p trebuie să rețină indicele celui mai mic element găsit până acum.',
        ],
        [
            'question' => "Ce condiție mută elementele mai mari la dreapta în Insertion Sort?\n```cpp\nint x = v[i], j = i - 1;\nwhile (??? ) {\n    v[j + 1] = v[j];\n    j--;\n}\nv[j + 1] = x;\n```",
            'options' => ['j >= 0 && v[j] > x', 'j < 0 && v[j] > x', 'v[j] < x', 'j >= 0 && v[j] == x'],
            'correct' => 0,
            'explanation' => 'Se mută la dreapta doar elementele din stânga care sunt mai mari decât valoarea inserată.',
        ],
        [
            'question' => "În QuickSort, ce rol are variabila `pivot` în fragmentul de mai jos?\n```cpp\nint pivot = v[(st + dr) / 2];\nwhile (v[i] < pivot) i++;\nwhile (v[j] > pivot) j--;\n```",
            'options' => ['Separă valorile mai mici și mai mari în timpul partiționării', 'Memorează mereu minimul vectorului', 'Numără interschimbările', 'Oprește recursivitatea'],
            'correct' => 0,
            'explanation' => 'Pivotul este reperul față de care elementele sunt împărțite în cele două zone.',
        ],
        [
            'question' => "Ce expresie păstrează interclasarea crescătoare în Merge Sort?\n```cpp\nwhile (i <= m && j <= r) {\n    if (???) c[k++] = v[i++];\n    else c[k++] = v[j++];\n}\n```",
            'options' => ['v[i] <= v[j]', 'v[i] >= v[j]', 'i <= j', 'k < r'],
            'correct' => 0,
            'explanation' => 'Se copiază mai întâi elementul mai mic dintre cele două secvențe deja sortate.',
        ],
        [
            'question' => "În sortarea prin numărare, ce face instrucțiunea marcată?\n```cpp\nfor (int i = 0; i < n; i++) {\n    fr[v[i]]++;\n}\n```",
            'options' => ['Crește frecvența valorii v[i]', 'Sortează direct vectorul v', 'Șterge duplicatele', 'Calculează poziția pivotului'],
            'correct' => 0,
            'explanation' => 'Vectorul de frecvență numără de câte ori apare fiecare valoare.',
        ],
        [
            'question' => "Ce schimbare transformă acest Bubble Sort din crescător în descrescător?\n```cpp\nif (v[j] > v[j + 1]) {\n    swap(v[j], v[j + 1]);\n}\n```",
            'options' => ['Înlocuiești `>` cu `<`', 'Înlocuiești `swap` cu `cout`', 'Crești n cu 1', 'Ștergi condiția if'],
            'correct' => 0,
            'explanation' => 'Pentru descrescător, elementul mai mic trebuie împins spre dreapta.',
        ],
        [
            'question' => "În căutarea binară, ce instrucțiune elimină jumătatea stângă când mijlocul este prea mic?\n```cpp\nint m = (st + dr) / 2;\nif (v[m] < x) {\n    ???\n}\n```",
            'options' => ['st = m + 1;', 'dr = m - 1;', 'x = v[m];', 'm++;'],
            'correct' => 0,
            'explanation' => 'Dacă valoarea din mijloc este mai mică decât x, căutarea continuă în dreapta.',
        ],
        [
            'question' => "De ce este importantă condiția `<=` în interclasarea de mai jos?\n```cpp\nif (stanga[i] <= dreapta[j]) {\n    rezultat[k++] = stanga[i++];\n}\n```",
            'options' => ['Ajută la păstrarea stabilității pentru elemente egale', 'Face algoritmul O(1)', 'Elimină recursivitatea', 'Transformă vectorul în heap'],
            'correct' => 0,
            'explanation' => 'La egalitate, elementul din stânga rămâne înaintea celui din dreapta, deci ordinea relativă se păstrează.',
        ],
        [
            'question' => "Ce valoare trebuie folosită pentru inițializarea lui `p` în Selection Sort?\n```cpp\nfor (int i = 0; i < n - 1; i++) {\n    int p = ???;\n    for (int j = i + 1; j < n; j++) {\n        if (v[j] < v[p]) p = j;\n    }\n    swap(v[i], v[p]);\n}\n```",
            'options' => ['i', '0', 'n - 1', 'j'],
            'correct' => 0,
            'explanation' => 'La fiecare pas, minimul se caută în zona nesortată care începe de la poziția i.',
        ],
    ];
}

function ai_quiz_local_feedback(int $score, int $total): string {
    $percent = $total > 0 ? (int)round(($score / $total) * 100) : 0;
    if ($percent >= 80) {
        $tone = 'Foarte bine. Ai prins ideile principale și poți trece spre întrebări mai aplicate.';
    } elseif ($percent >= 50) {
        $tone = 'Ești pe drumul bun, dar merită să repeți pașii algoritmilor unde ai greșit.';
    } else {
        $tone = 'Încă ai zone de consolidat. Reia explicațiile și folosește laboratorul vizual pas cu pas.';
    }

    return "Feedback local: serviciul AI extern nu este disponibil momentan, dar scorul tău a fost salvat.\n\n" .
        "Scor: {$score}/{$total} ({$percent}%). {$tone}\n\n" .
        "Recomandare: repetă Bubble/Selection/Insertion pentru mecanismele de bază, apoi Quick/Merge pentru recursivitate și partiționare/interclasare. La fiecare algoritm urmărește ideea, pseudocodul, complexitatea și un exemplu mic.";
}

if ($action === 'generate_quiz') {
    $docContext = documentation_context_for_slug((string)$pathSlug, 8500, 6);
    $sourceList = !empty($docContext['sources']) ? implode(', ', $docContext['sources']) : 'niciun fișier găsit';
    $contextText = $docContext['text'] !== ''
        ? $docContext['text']
        : 'Nu există fragmente relevante disponibile în indexul proiect_documentatie.';

    if ($apiKeyMissing) {
        $fallbackQuiz = ai_quiz_fallback_questions((string)$pathSlug);
        ai_quiz_store_current_attempt($fallbackQuiz, (string)$pathSlug);
        echo json_encode([
            'ok' => true,
            'quiz' => $fallbackQuiz,
            'sources' => $docContext['sources'],
            'fallback' => true,
            'notice' => 'Cheia AI nu este configurată. Am generat un test local de rezervă pe baza proiectului.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $prompt = "Ești expert în predarea algoritmicii. Generează un TEST DE EXAMEN de 10 întrebări grilă pe baza fragmentelor extrase din directorul proiect_documentatie.

SURSE DISPONIBILE:
$sourceList

CONTEXT DIN FIȘIERELE PROIECTULUI:
$contextText

CERINȚE STRICTE:
1. Întrebările trebuie să fie în limba ROMÂNĂ, cu caractere corecte (fără coduri eronate)
2. Întrebările trebuie să fie ancorate în contextul de mai sus; nu introduce teme care nu apar deloc în documentație
3. Fiecare întrebare trebuie să aibă EXACT 4 variante de răspuns
4. Indicele răspunsului corect: 0, 1, 2, sau 3 (distribuit aleatoriu)
5. Explicația trebuie să fie concisă (1-2 propoziții)
6. VARIAZĂ dificultatea: unele întrebări ușoare, altele mai grele
7. Evită întrebări triviale sau prea evidente
8. Include în explicații termeni și exemple compatibile cu sursele
9. NU întreba niciodată „ce metodă/algoritm este folosit în funcția X”, „ce face aplicația Y”, „din ce fișier provine X” sau alte întrebări de memorare a numelor din exemple
10. Dacă folosești o aplicație sau un exemplu de cod din documentație, pune fragmentul scurt de cod (4-10 linii) în câmpul code_example și întreabă ceva rezolvabil din acel fragment
11. Preferă întrebări de tip: completează expresia lipsă, ce variabilă trebuie înlocuită, ce condiție oprește bucla, ce efect are schimbarea unei variabile, ce complexitate rezultă din cod
12. Întrebarea trebuie să poată fi rezolvată fără ca elevul să știe pe dinafară numele funcțiilor din aplicațiile scrise
13. Dacă menționezi o variabilă concretă (`aux`, `vf`, `i`, `j`, `pivot` etc.), include obligatoriu fragmentul de cod în care apare variabila
14. Nu scrie întrebări de forma „Ce este `vf` în codul SortFrecventa?”; în loc de asta arată codul și întreabă ce valoare/condiție trebuie schimbată
15. Cel puțin jumătate dintre întrebări trebuie să conțină un fragment scurt de cod între ```cpp și ```

FORMATUL RĂSPUNSULUI - STRICT JSON (fără coduri HTML, fără escape-uri eronate):
{
  \"quiz\": [
    {
      \"question\": \"Text clar al întrebării. Poate include un fragment de cod între ```cpp și ``` dacă întrebarea se bazează pe cod.\",
      \"code_example\": \"Fragment C++ scurt sau null dacă nu este nevoie de cod.\",
      \"options\": [\"Varianta A\", \"Varianta B\", \"Varianta C\", \"Varianta D\"],
      \"correct\": 0,
      \"explanation\": \"Explicație concisă de ce e corect\"
    }
  ]
}

Răspunde DOAR cu JSON-ul valid, fără alt text, comentarii sau markdown.";

    $payload = [
        'model' => $model,
        'messages' => [['role' => 'user', 'content' => $prompt]],
        'temperature' => 0.7,
        'response_format' => ['type' => 'json_object']
    ];

    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=UTF-8', 'Authorization: Bearer ' . $apiKey],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT => 30
    ]);
    $res = curl_exec($ch);
    $curlErr = curl_error($ch);
    $curlErrno = curl_errno($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($res === false) {
        error_log("ai_quiz_api generate curl error #{$curlErrno}: {$curlErr}");
        $fallbackQuiz = ai_quiz_fallback_questions((string)$pathSlug);
        ai_quiz_store_current_attempt($fallbackQuiz, (string)$pathSlug);
        echo json_encode([
            'ok' => true,
            'quiz' => $fallbackQuiz,
            'sources' => $docContext['sources'],
            'fallback' => true,
            'notice' => 'Serviciul AI extern nu este disponibil momentan. Am generat un test local de rezervă.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if ($httpCode !== 200) {
        error_log("ai_quiz_api generate HTTP {$httpCode}: " . substr((string)$res, 0, 500));
        $fallbackQuiz = ai_quiz_fallback_questions((string)$pathSlug);
        ai_quiz_store_current_attempt($fallbackQuiz, (string)$pathSlug);
        echo json_encode([
            'ok' => true,
            'quiz' => $fallbackQuiz,
            'sources' => $docContext['sources'],
            'fallback' => true,
            'notice' => 'Serviciul AI extern nu este disponibil acum. Am generat un test local de rezervă.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // FIX [Q2]: Robust JSON parsing with UTF-8 handling
    $data = json_decode($res, true);
    if (!is_array($data)) {
        error_log("ai_quiz_api: Invalid JSON response structure");
        echo json_encode(['ok' => false, 'error' => 'Răspuns invalid de la AI.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $quizRaw = $data['choices'][0]['message']['content'] ?? '';
    if (empty($quizRaw)) {
        error_log("ai_quiz_api: Empty content from AI");
        echo json_encode(['ok' => false, 'error' => 'AI nu a generat conținut.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Try to extract JSON from potentially wrapped response
    $quizRaw = trim($quizRaw);
    if (strpos($quizRaw, '{') !== false) {
        $startPos = strpos($quizRaw, '{');
        $endPos = strrpos($quizRaw, '}');
        if ($startPos !== false && $endPos !== false) {
            $quizRaw = substr($quizRaw, $startPos, $endPos - $startPos + 1);
        }
    }
    
    $quizJson = json_decode($quizRaw, true);
    if (!is_array($quizJson) || !isset($quizJson['quiz']) || !is_array($quizJson['quiz'])) {
        error_log("ai_quiz_api: Invalid quiz structure: " . substr($quizRaw, 0, 200));
        echo json_encode(['ok' => false, 'error' => 'Structură quiz invalidă de la AI.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // FIX [Q3]: Validate and sanitize quiz data
    $sanitizedQuiz = [];
    foreach ($quizJson['quiz'] as $q) {
        if (!isset($q['question'], $q['options'], $q['correct'], $q['explanation'])) {
            continue; // Skip malformed questions
        }
        if (!is_array($q['options']) || count($q['options']) !== 4) {
            continue; // Must have exactly 4 options
        }
        if (!is_int($q['correct']) || $q['correct'] < 0 || $q['correct'] > 3) {
            continue; // Valid correct index
        }

        $questionWithOptionalCode = (string)$q['question'];
        if (!empty($q['code_example'])) {
            $questionWithOptionalCode .= "\n```cpp\n" . (string)$q['code_example'] . "\n```";
        }

        if (ai_quiz_is_memory_question($questionWithOptionalCode)) {
            error_log('ai_quiz_api: skipped memory-based question: ' . mb_substr((string)$q['question'], 0, 180, 'UTF-8'));
            continue;
        }
        
        // Ensure UTF-8 encoding for all strings
        [$cleanQuestion, $codeExample] = ai_quiz_extract_code_example((string)$q['question']);
        if (!empty($q['code_example'])) {
            $codeExample = trim((string)$q['code_example']);
        }

        $sanitizedQuestion = [
            'question' => mb_convert_encoding($cleanQuestion, 'UTF-8', 'UTF-8'),
            'code_example' => $codeExample !== '' ? mb_convert_encoding($codeExample, 'UTF-8', 'UTF-8') : null,
            'options' => array_map(function($opt) { 
                return mb_convert_encoding($opt, 'UTF-8', 'UTF-8'); 
            }, $q['options']),
            'correct' => $q['correct'],
            'explanation' => mb_convert_encoding($q['explanation'], 'UTF-8', 'UTF-8')
        ];
        $sanitizedQuiz[] = $sanitizedQuestion;
    }
    
    if (count($sanitizedQuiz) < 10) {
        // If we got fewer than 10 questions, it's still acceptable but log it
        error_log("ai_quiz_api: Generated only " . count($sanitizedQuiz) . " valid questions out of 10");
        $existingQuestions = array_map(static fn($item) => $item['question'], $sanitizedQuiz);
        foreach (ai_quiz_fallback_questions((string)$pathSlug) as $fallback) {
            if (count($sanitizedQuiz) >= 10) {
                break;
            }
            if (in_array($fallback['question'], $existingQuestions, true)) {
                continue;
            }
            $sanitizedQuiz[] = $fallback;
            $existingQuestions[] = $fallback['question'];
        }
    }
    
    if (empty($sanitizedQuiz)) {
        echo json_encode(['ok' => false, 'error' => 'Nu s-au putut valida întrebările generate.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    ai_quiz_store_current_attempt($sanitizedQuiz, (string)$pathSlug);
    
    // FIX [DB1]: Store generated questions into DB with doc link mapping
    // Helper: guess a documentation page based on question text and pathSlug
    function guess_doc_link($question, $pathSlug) {
        $q = mb_strtolower($question, 'UTF-8');
        if (strpos($q, 'merge') !== false || strpos($q, 'interclas') !== false) return 'index.php?page=sort_merge';
        if (strpos($q, 'quick') !== false || strpos($q, 'pivot') !== false) return 'index.php?page=sort_quick';
        if (strpos($q, 'bubble') !== false) return 'index.php?page=sort_bubble';
        if (strpos($q, 'selection') !== false) return 'index.php?page=sort_selection';
        if (strpos($q, 'insertion') !== false) return 'index.php?page=sort_insertion';
        if (strpos($q, 'count') !== false || strpos($q, 'counting') !== false) return 'index.php?page=sort_counting';
        if (strpos($q, 'recurs') !== false) return 'index.php?page=recursivitate';
        if (strpos($q, 'dame') !== false || strpos($q, 'regin') !== false || strpos($q, 'damelor') !== false) return 'index.php?page=backtracking';
        if (strpos($q, 'divide') !== false || strpos($q, 'divide et impera') !== false) return 'index.php?page=divide_et_impera';
        // Fallback to a generic documentation index or project PDF
        return 'proiect_documentatie/metode_de_sortare/Metode de sortare_.pdf';
    }

    // Prepare DB insertion (if $con available)
    if (isset($con) && $con instanceof mysqli) {
        $checkStmt = $con->prepare("SELECT 1 FROM grile_cpp WHERE intrebare = ? LIMIT 1");
        $insStmt = $con->prepare(
            "INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu, varianta_1, varianta_2, varianta_3, varianta_4, raspuns_corect, explicatie, doc_link)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        foreach ($sanitizedQuiz as $sq) {
            if (!$checkStmt || !$insStmt) {
                error_log('ai_quiz_api DB prepare failed: ' . $con->error);
                break;
            }
            $dbQuestion = ai_quiz_prepare_db_question($sq);
            $qtext = $dbQuestion['question'];
            $codeExample = $dbQuestion['code_example'];
            // Skip duplicate by text
            $checkStmt->bind_param('s', $qtext);
            $checkStmt->execute();
            $res = $checkStmt->get_result();
            if ($res && $res->fetch_assoc()) {
                continue; // already exists
            }

            // Guess metadata
            $docLink = guess_doc_link($qtext, $pathSlug);
            $nume_metoda = ucfirst($pathSlug);
            $dificultate = 'Mediu';
            $opt1 = $sq['options'][0] ?? null;
            $opt2 = $sq['options'][1] ?? null;
            $opt3 = $sq['options'][2] ?? null;
            $opt4 = $sq['options'][3] ?? null;
            $correct = ((int)$sq['correct']) + 1;
            $exp = $sq['explanation'];

            $insStmt->bind_param('ssssssssiss', $nume_metoda, $dificultate, $qtext, $codeExample, $opt1, $opt2, $opt3, $opt4, $correct, $exp, $docLink);
            // Note: bind_param types must match: s=string, i=integer. We'll attempt with fallback
            // Use an execution attempt; ignore failures but log them
            try {
                $insStmt->execute();
            } catch (Exception $e) {
                error_log('ai_quiz_api DB insert failed: ' . $e->getMessage());
            }
        }

        if ($checkStmt) $checkStmt->close();
        if ($insStmt) $insStmt->close();
    }

    echo json_encode(['ok' => true, 'quiz' => $sanitizedQuiz, 'sources' => $docContext['sources']], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'grade_quiz') {
    $userAnswers = $input['answers'] ?? [];
    if (!is_array($userAnswers) || empty($userAnswers)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Răspunsuri lipsă pentru evaluare.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    [$gradeOk, $gradedAnswers, $gradeError] = ai_quiz_grade_from_session($userAnswers, (string)$pathSlug);
    if (!$gradeOk) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'error' => $gradeError], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $wrongQuestions = array_filter($gradedAnswers, fn($a) => !($a['isCorrect'] ?? false));
    $score = count($gradedAnswers) - count($wrongQuestions);
    $total = count($gradedAnswers);
    unset($_SESSION['ai_quiz_current']);
    $docContext = documentation_context_for_slug((string)$pathSlug, 5000, 4);
    $sourceList = !empty($docContext['sources']) ? implode(', ', $docContext['sources']) : 'niciun fișier găsit';
    $contextText = $docContext['text'] !== ''
        ? $docContext['text']
        : 'Nu există fragmente relevante disponibile în indexul proiect_documentatie.';

    if ($apiKeyMissing) {
        $feedback = ai_quiz_local_feedback($score, $total);
        $saveResult = ['saved' => false, 'id' => null, 'percent' => $total > 0 ? round(($score / $total) * 100, 2) : 0];
        if (!empty($_SESSION['user_id'])) {
            $saveResult = ai_quiz_save_attempt($con, (int)$_SESSION['user_id'], (string)$pathSlug, $score, $total, $feedback, $docContext['sources']);
        }

        echo json_encode([
            'ok' => true,
            'feedback' => $feedback,
            'sources' => $docContext['sources'],
            'fallback' => true,
            'attempt_saved' => $saveResult['saved'],
            'attempt' => [
                'id' => $saveResult['id'],
                'score' => $score,
                'total' => $total,
                'percent' => $saveResult['percent'],
            ],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $prompt = "Ești profesor experimentat de informatică. Un elev a terminat un test de $total întrebări și a obținut scorul $score/$total.

Folosește contextul de mai jos din proiect_documentatie pentru feedback și recomandări.

SURSE DISPONIBILE:
$sourceList

CONTEXT DIN FIȘIERELE PROIECTULUI:
$contextText

ÎNTREBĂRILE LA CARE A GREȘIT (sau nu răspunde clar):
" . json_encode($wrongQuestions, JSON_UNESCAPED_UNICODE) . "

TE ROG SĂ GENEREZI UN FEEDBACK STRUCTURAT ÎN LIMBA ROMÂNĂ:

1. **Felicitare sau Încurajare** (în funcție de scor - dacă >= 70% is well, dacă < 50% needs more study)
2. **Analiza Greșelilor** - pe scurt, explicația conceptelor la care a greșit
3. **Recomandări de Aprofundare** - ce teme specifice din algoritmică trebuie repetate

STIL: Pedagogic, prietenos, motivator, concis.";

    $payload = [
        'model' => $model,
        'messages' => [['role' => 'user', 'content' => $prompt]],
        'temperature' => 0.7
    ];

    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=UTF-8', 'Authorization: Bearer ' . $apiKey],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT => 30
    ]);
    $res = curl_exec($ch);
    $curlErr = curl_error($ch);
    $curlErrno = curl_errno($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($res === false) {
        error_log("ai_quiz_api grade curl error #{$curlErrno}: {$curlErr}");
        $feedback = ai_quiz_local_feedback($score, $total);
        $saveResult = ['saved' => false, 'id' => null, 'percent' => $total > 0 ? round(($score / $total) * 100, 2) : 0];
        if (!empty($_SESSION['user_id'])) {
            $saveResult = ai_quiz_save_attempt($con, (int)$_SESSION['user_id'], (string)$pathSlug, $score, $total, $feedback, $docContext['sources']);
        }

        echo json_encode([
            'ok' => true,
            'feedback' => $feedback,
            'sources' => $docContext['sources'],
            'fallback' => true,
            'attempt_saved' => $saveResult['saved'],
            'attempt' => [
                'id' => $saveResult['id'],
                'score' => $score,
                'total' => $total,
                'percent' => $saveResult['percent'],
            ],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if ($httpCode !== 200) {
        error_log("ai_quiz_api grade HTTP {$httpCode}: " . substr((string)$res, 0, 500));
        $feedback = ai_quiz_local_feedback($score, $total);
        $saveResult = ['saved' => false, 'id' => null, 'percent' => $total > 0 ? round(($score / $total) * 100, 2) : 0];
        if (!empty($_SESSION['user_id'])) {
            $saveResult = ai_quiz_save_attempt($con, (int)$_SESSION['user_id'], (string)$pathSlug, $score, $total, $feedback, $docContext['sources']);
        }

        echo json_encode([
            'ok' => true,
            'feedback' => $feedback,
            'sources' => $docContext['sources'],
            'fallback' => true,
            'attempt_saved' => $saveResult['saved'],
            'attempt' => [
                'id' => $saveResult['id'],
                'score' => $score,
                'total' => $total,
                'percent' => $saveResult['percent'],
            ],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // FIX [Q4]: Proper UTF-8 handling for feedback response
    $data = json_decode($res, true);
    if (!is_array($data) || !isset($data['choices'][0]['message']['content'])) {
        error_log("ai_quiz_api: Invalid feedback response structure");
        echo json_encode(['ok' => false, 'error' => 'Feedback invalid de la AI.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $feedbackRaw = $data['choices'][0]['message']['content'];
    // Ensure proper UTF-8 encoding
    $feedback = mb_convert_encoding($feedbackRaw, 'UTF-8', 'UTF-8');
    $saveResult = ['saved' => false, 'id' => null, 'percent' => $total > 0 ? round(($score / $total) * 100, 2) : 0];
    if (!empty($_SESSION['user_id'])) {
        $saveResult = ai_quiz_save_attempt($con, (int)$_SESSION['user_id'], (string)$pathSlug, $score, $total, $feedback, $docContext['sources']);
    }
    
    echo json_encode([
        'ok' => true,
        'feedback' => $feedback,
        'sources' => $docContext['sources'],
        'attempt_saved' => $saveResult['saved'],
        'attempt' => [
            'id' => $saveResult['id'],
            'score' => $score,
            'total' => $total,
            'percent' => $saveResult['percent'],
        ],
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
