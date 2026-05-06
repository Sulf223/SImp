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
require_once 'conexiune.php';

// FIX [A2]: Session timeout pentru AJAX
enforce_session_timeout_ajax();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metoda nepermisă.'], JSON_UNESCAPED_UNICODE);
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

if ($apiKey === '') {
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => 'Serviciul AI Quiz este momentan indisponibil (API key lipsă).'], JSON_UNESCAPED_UNICODE);
    exit;
}

$model = getenv('GROQ_MODEL') ?: 'llama-3.3-70b-versatile';

// FIX [Q1]: Extract context from project documentation files
function extractDocumentationContext($pathSlug) {
    $contexts = [
        'sorting-basics' => [
            'algorithms' => ['Bubble Sort', 'Selection Sort', 'Insertion Sort', 'Quick Sort', 'Merge Sort', 'Counting Sort'],
            'key_concepts' => [
                'Complexitate O(n²) pentru algoritmi simpli vs O(n log n) pentru avansați',
                'Conceptul de stabilitate în sortare',
                'Partiționarea în Quick Sort folosind pivot',
                'Divide et Impera în Merge Sort și Quick Sort',
                'Interclasarea (merge) în Merge Sort'
            ],
            'difficulty_topics' => ['Merge Sort', 'Quick Sort', 'Stabilitate vs instabilitate']
        ],
        'recursion-pro' => [
            'algorithms' => ['Factorial', 'Fibonacci', 'Merge Sort (recursiv)', 'Quick Sort (recursiv)'],
            'key_concepts' => [
                'Caz de bază (base case) - condiția de terminare',
                'Caz recursiv - apelul funcției către ea însăși cu parametri modificați',
                'Stack overflow - depășirea limitei stivei',
                'Paradigma Divide et Impera',
                'Gestiunea memoriei în recursivitate'
            ],
            'difficulty_topics' => ['Stack overflow', 'Complexitate recursivă', 'Divide et Impera']
        ],
        'backtracking' => [
            'algorithms' => ['Problema damelor (N-Queens)', 'Permutări', 'Combinații', 'Colorarea grafului'],
            'key_concepts' => [
                'Explorarea sistematică a spațiului soluțiilor',
                'Pas înainte (forward step) - adăugarea unui element',
                'Pas înapoi (backward step / backtrack) - revenirea la starea anterioară',
                'Funcția valid() - tăierea ramurilor inutile',
                'Complexitate exponențială O(a^n)'
            ],
            'difficulty_topics' => ['Problema damelor', 'Optimizare prin prunning', 'Spațiul de stare']
        ],
        'divide-et-impera' => [
            'algorithms' => ['Merge Sort', 'Quick Sort', 'Binary Search'],
            'key_concepts' => [
                'Divide - împărțirea problemei în subprobleme mai mici',
                'Conquer - rezolvarea recursivă a subproblemelor',
                'Combine - combinarea rezultatelor subproblemelor',
                'Complexitate O(n log n) pentru majoritatea cazurilor',
                'Comparație cu alte paradigme algoritmice'
            ],
            'difficulty_topics' => ['Merge Sort recursiv', 'Quick Sort și pivotare', 'Analiza complexității']
        ]
    ];
    
    return $contexts[$pathSlug] ?? $contexts['sorting-basics'];
}

if ($action === 'generate_quiz') {
    $docContext = extractDocumentationContext($pathSlug);
    $algorithms = implode(', ', $docContext['algorithms']);
    $keyConcepts = implode('; ', $docContext['key_concepts']);
    
    $prompt = "Ești expert în predarea algoritmicii. Generează un TEST DE EXAMEN de 10 întrebări grilă, pe baza acestui conținut educativ.

DOMENIU: $algorithms

CONCEPTE CHEIE DE ACOPERIT:
$keyConcepts

CERINȚE STRICTE:
1. Întrebările trebuie să fie în limba ROMÂNĂ, cu caractere corecte (fără coduri eronate)
2. Nivel mediu spre avansat - gândite pentru elevi care au studiat acești algoritmi
3. Fiecare întrebare trebuie să aibă EXACT 4 variante de răspuns
4. Indicele răspunsului corect: 0, 1, 2, sau 3 (distribuit aleatoriu)
5. Explicația trebuie să fie concisă (1-2 propoziții)
6. VARIAZĂ dificultatea: unele întrebări ușoare, altele mai grele
7. Evită întrebări triviale sau prea evidente

FORMATUL RĂSPUNSULUI - STRICT JSON (fără coduri HTML, fără escape-uri eronate):
{
  \"quiz\": [
    {
      \"question\": \"Text clar al întrebării în limba română\",
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
        echo json_encode(['ok' => false, 'error' => 'Serviciul AI este indisponibil. Încearcă mai târziu.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if ($httpCode !== 200) {
        error_log("ai_quiz_api generate HTTP {$httpCode}: " . substr((string)$res, 0, 500));
        echo json_encode(['ok' => false, 'error' => 'AI a răspuns cu eroare (HTTP ' . $httpCode . ').'], JSON_UNESCAPED_UNICODE);
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
        
        // Ensure UTF-8 encoding for all strings
        $sanitizedQuestion = [
            'question' => mb_convert_encoding($q['question'], 'UTF-8', 'UTF-8'),
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
    }
    
    if (empty($sanitizedQuiz)) {
        echo json_encode(['ok' => false, 'error' => 'Nu s-au putut valida întrebările generate.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
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
        $hasDocLink = false;
        $colCheck = $con->query("SHOW COLUMNS FROM grile_cpp LIKE 'doc_link'");
        if ($colCheck) {
            $hasDocLink = $colCheck->num_rows > 0;
            $colCheck->free();
        }

        $checkStmt = $con->prepare("SELECT 1 FROM grile_cpp WHERE intrebare = ? LIMIT 1");
        if ($hasDocLink) {
            $insStmt = $con->prepare(
                "INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu, varianta_1, varianta_2, varianta_3, varianta_4, raspuns_corect, explicatie, doc_link)
                 VALUES (?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?)"
            );
        } else {
            $insStmt = $con->prepare(
                "INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu, varianta_1, varianta_2, varianta_3, varianta_4, raspuns_corect, explicatie)
                 VALUES (?, ?, ?, NULL, ?, ?, ?, ?, ?, ?)"
            );
        }

        foreach ($sanitizedQuiz as $sq) {
            if (!$checkStmt || !$insStmt) {
                error_log('ai_quiz_api DB prepare failed: ' . $con->error);
                break;
            }
            $qtext = $sq['question'];
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
            $correct = $sq['correct'];
            $exp = $sq['explanation'];

            if ($hasDocLink) {
                $insStmt->bind_param('sssssssiss', $nume_metoda, $dificultate, $qtext, $opt1, $opt2, $opt3, $opt4, $correct, $exp, $docLink);
            } else {
                $insStmt->bind_param('sssssssis', $nume_metoda, $dificultate, $qtext, $opt1, $opt2, $opt3, $opt4, $correct, $exp);
            }
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

    echo json_encode(['quiz' => $sanitizedQuiz], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'grade_quiz') {
    $userAnswers = $input['answers'] ?? [];
    if (!is_array($userAnswers) || empty($userAnswers)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Răspunsuri lipsă pentru evaluare.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $wrongQuestions = array_filter($userAnswers, fn($a) => !($a['isCorrect'] ?? false));
    $score = count($userAnswers) - count($wrongQuestions);
    $total = count($userAnswers);

    $prompt = "Ești profesor experimentat de informatică. Un elev a terminat un test de $total întrebări și a obținut scorul $score/$total.

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
        echo json_encode(['ok' => false, 'error' => 'Serviciul AI este indisponibil. Încearcă mai târziu.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if ($httpCode !== 200) {
        error_log("ai_quiz_api grade HTTP {$httpCode}: " . substr((string)$res, 0, 500));
        echo json_encode(['ok' => false, 'error' => 'AI a răspuns cu eroare (HTTP ' . $httpCode . ').'], JSON_UNESCAPED_UNICODE);
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
    
    echo json_encode(['ok' => true, 'feedback' => $feedback], JSON_UNESCAPED_UNICODE);
    exit;
}
