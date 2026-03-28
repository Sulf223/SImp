<?php
// ajax_progres.php - Endpoint pentru a salva progresul la grile via AJAX

// Setăm header-ul pentru a indica un răspuns JSON
header('Content-Type: application/json');

// Pornim sesiunea și includem fișierele necesare
session_start();
require_once 'conexiune.php';
require_once 'auth.php';
require_once 'helpers.php';

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

// Preluăm datele trimise (ne așteptăm la JSON)
$data = json_decode(file_get_contents('php://input'), true);
$id_grila = $data['id_grila'] ?? 0;

if ($id_grila > 0) {
    $id_utilizator = $_SESSION['user_id'];

    // Inserăm progresul în baza de date, ignorând duplicatele
    $sql = "INSERT IGNORE INTO progres_grile (id_utilizator, id_grila) VALUES (?, ?)";
    
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("ii", $id_utilizator, $id_grila);
        
        if ($stmt->execute()) {
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
