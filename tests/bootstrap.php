<?php
declare(strict_types=1);
// Pornim sesiunea pentru funcțiile care depind de $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
require_once __DIR__ . '/../site_g/PHP/helpers.php';
