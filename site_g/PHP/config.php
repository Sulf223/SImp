<?php
// Fișier de configurare securizat (PHP)
// Valorile sunt preluate din variabilele de mediu pentru a preveni expunerea lor în codul sursă.

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'db'   => getenv('DB_NAME') ?: 'dbsortari'
];
