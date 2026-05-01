<?php
header('Content-Type: application/json; charset=UTF-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'helpers.php';
require_once 'conexiune.php';

// Verificăm CSRF
if (!verify_csrf_ajax()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'CSRF invalid.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$pathSlug = $input['path_slug'] ?? 'general';

$apiKey = getenv('GROQ_API_KEY') ?: ($_ENV['GROQ_API_KEY'] ?? '');
if (!$apiKey) {
    echo json_encode(['ok' => false, 'error' => 'API Key missing.']);
    exit;
}

$model = getenv('GROQ_MODEL') ?: 'llama-3.3-70b-versatile';

if ($action === 'generate_quiz') {
    $topicConstraint = "algoritmi C++ (sortări, recursivitate, backtracking)";
    if ($pathSlug === 'sorting-basics') {
        $topicConstraint = "algoritmi de sortare C++ (Bubble, Selection, Insertion, Quick Sort, complexitate temporală)";
    } elseif ($pathSlug === 'recursion-pro') {
        $topicConstraint = "recursivitate în C++, paradigma Divide et Impera și Merge Sort";
    }

    $prompt = "Generează un test de EXAMEN FINAL de 10 întrebări grilă despre $topicConstraint. 
    Întrebările trebuie să fie de nivel mediu spre avansat.
    Fiecare întrebare trebuie să aibă 4 variante de răspuns și un singur răspuns corect (index 0-3).
    Formatul trebuie să fie strict JSON:
    {
      \"quiz\": [
        {
          \"question\": \"Text întrebare\",
          \"options\": [\"Var A\", \"Var B\", \"Var C\", \"Var D\"],
          \"correct\": 0,
          \"explanation\": \"De ce e corect?\"
        }
      ]
    }
    Răspunde DOAR cu JSON-ul, fără alte comentarii. Limba: Română.";

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
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);
    $res = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($res, true);
    $quizRaw = $data['choices'][0]['message']['content'] ?? '';
    echo $quizRaw;
    exit;
}

if ($action === 'grade_quiz') {
    $userAnswers = $input['answers'] ?? []; // [{question: "text", user: 0, correct: 1, isCorrect: bool}]
    
    $wrongQuestions = array_filter($userAnswers, fn($a) => !$a['isCorrect']);
    $score = count($userAnswers) - count($wrongQuestions);
    $total = count($userAnswers);

    $prompt = "Un elev a terminat un test C++ de $total întrebări și a obținut scorul $score/$total.
    Iată întrebările la care a greșit: " . json_encode($wrongQuestions, JSON_UNESCAPED_UNICODE) . ".
    
    Te rog să generezi un feedback structurat în limba română care să conțină:
    1. O scurtă felicitare sau încurajare (în funcție de scor).
    2. O secțiune 'Analiza Greșelilor' unde să explici pe scurt conceptele încurcate la întrebările greșite.
    3. O secțiune 'Recomandări de Aprofundare' unde să îi spui exact ce lecții sau teme din algoritmică trebuie să mai repete (ex: Complexitate, Stabilitatea Sortării, Gestionarea Stivei în recursivitate, etc.).
    
    Folosește un ton pedagogic, prietenos și concis.";

    $payload = [
        'model' => $model,
        'messages' => [['role' => 'user', 'content' => $prompt]],
        'temperature' => 0.7
    ];

    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE)
    ]);
    $res = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($res, true);
    echo json_encode(['ok' => true, 'feedback' => $data['choices'][0]['message']['content'] ?? 'Bravo!']);
    exit;
}
