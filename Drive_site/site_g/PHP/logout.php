<?php
require_once 'helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificăm CSRF pentru logout (trebuie să fie POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    
    $_SESSION = [];
    session_destroy();
    
    // Pornim o sesiune nouă doar pentru mesajul flash
    session_start();
    set_flash('success', 'Ai fost delogat cu succes!');
    
    header('Location: ../index.php?page=acasa');
    exit;
} else {
    // Dacă nu e POST, afișăm un formular cu buton de logout
    die('Te rog folosește butonul de logout.');
}
