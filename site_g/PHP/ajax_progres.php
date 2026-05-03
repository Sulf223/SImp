<?php
// ajax_progres.php - Endpoint pentru a salva progresul la grile via AJAX

// Setăm header-ul pentru a indica un răspuns JSON
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Pornim sesiunea și includem fișierele necesare
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'conexiune.php';
require_once 'auth.php';
require_once 'helpers.php';

// FIX [A2]: Session timeout pentru AJAX
enforce_session_timeout_ajax();

// Verificăm dacă utilizatorul este logat
if (!is_logged_in()) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'error' => 'Utilizatorul nu este logat.']);
    exit;
}

// Verificăm CSRF pentru cereri AJAX
if (!verify_csrf_ajax()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Token CSRF invalid.']);
    exit;
}

// Preluăm datele trimise (JSON sau POST clasic)
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

if ($data && isset($data['id_grila'])) {
    $id_grila = (int)$data['id_grila'];
} else {
    $id_grila = (int)($_POST['id_grila'] ?? 0);
}

if ($id_grila > 0) {
    $id_utilizator = $_SESSION['user_id'];

    // FIX [M1]: Verificare existență grilă înainte de a marca progresul
    $check_sql = "SELECT 1 FROM grile_cpp WHERE id = ?";
    if ($check_stmt = $con->prepare($check_sql)) {
        $check_stmt->bind_param("i", $id_grila);
        $check_stmt->execute();
        $check_res = $check_stmt->get_result();
        if (!$check_res->fetch_assoc()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Grila nu există.']);
            $check_stmt->close();
            exit;
        }
        $check_stmt->close();
    }

    // Inserăm progresul în baza de date, ignorând duplicatele
    $sql = "INSERT IGNORE INTO progres_grile (id_utilizator, id_grila) VALUES (?, ?)";
    
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("ii", $id_utilizator, $id_grila);
        
        if ($stmt->execute()) {
            // FEATURE [F5]: Check achievements after quiz completion
            $newly_unlocked = check_and_award_achievements($con, $id_utilizator);
            if (!empty($newly_unlocked)) {
                if (!isset($_SESSION['new_achievements'])) {
                    $_SESSION['new_achievements'] = [];
                }
                $_SESSION['new_achievements'] = array_merge($_SESSION['new_achievements'], $newly_unlocked);
            }

            echo json_encode(['success' => true, 'message' => 'Progres salvat.']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'error' => 'Eroare la salvarea progresului.']);
        }
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Eroare la pregătirea interogării.']);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'error' => 'ID grilă invalid.']);
}

$con->close();
?>
