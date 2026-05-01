<?php
// Fișier de configurare securizat (PHP)
// Valorile sunt preluate din variabilele de mediu pentru a preveni expunerea lor în codul sursă.

// În medii locale (ex: WAMP), variabilele din .env nu sunt încărcate automat.
// Facem un loader minimal pentru .env fără dependențe externe.
// Verificăm întâi în rădăcina proiectului (recomandat), apoi în folderul curent (legacy)
$envPaths = [
    dirname(__DIR__, 2) . '/.env', 
    dirname(__DIR__) . '/.env'
];

foreach ($envPaths as $envPath) {
    if (is_readable($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (is_array($lines)) {
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                    continue;
                }

                [$key, $value] = array_map('trim', explode('=', $line, 2));
                if ($key === '') {
                    continue;
                }

                $value = trim($value, " \t\n\r\0\x0B\"'");

                if (getenv($key) === false) {
                    putenv($key . '=' . $value);
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }
        // Oprim încărcarea dacă am găsit și procesat un fișier .env valid
        break; 
    }
}

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'db'   => getenv('DB_NAME') ?: 'dbsortari'
];
