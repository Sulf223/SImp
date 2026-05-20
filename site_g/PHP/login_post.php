<?php
// Procesare login
require_once __DIR__ . "/conexiune.php";
require_once __DIR__ . "/helpers.php"; // Includem helpers pentru set_flash și verify_csrf

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

set_exception_handler(function (Throwable $e): void {
    error_log('login_post uncaught exception: ' . $e->getMessage());
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (function_exists('set_flash')) {
        set_flash('error', 'A apărut o eroare tehnică la autentificare. Încearcă din nou.');
    }
    header('Location: ../index.php?page=login');
    exit;
});

// Verificăm CSRF
verify_csrf();

$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    set_flash('error', 'Completează utilizatorul și parola.');
    header('Location: ../index.php?page=login');
    exit;
}

// Verificăm rate limiting (P1 - Mutat în DB și bazat pe IP)
$user_ip = $_SERVER['REMOTE_ADDR'] ?: 'unknown';
if (!check_rate_limit($con, 'login', $user_ip, 5, 900)) {
    set_flash('error', 'Prea multe încercări eșuate. Te rog așteaptă 15 minute.');
    header('Location: ../index.php?page=login');
    exit;
}

// Căutăm utilizatorul în tabelul `utilizatori` folosind prepared statements
$sql = "SELECT id, username, parola_hash, rol FROM utilizatori WHERE username = ? LIMIT 1";
$stmt = $con->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res  = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    // Verificăm parola (hash)
    if ($user && password_verify($password, $user['parola_hash'])) {
        // Regenerează session ID pentru securitate
        regenerate_session();
        
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['rol'] = $user['rol'] ?? 'user';
        
        // Resetăm rate limiting la login cu succes
        reset_rate_limit($con, 'login', $user_ip);

        // FIX [BUG-500]: Streak logic — folosim user_id ca PRIMARY KEY (nu există coloană `id` în user_streak)
        // + try/catch ca nicio eroare aici să nu mai poată bloca login-ul cu HTTP 500
        $user_id = (int)$user['id'];
        $today = date('Y-m-d');
        try {
            $stmt_streak = $con->prepare("SELECT current_streak, longest_streak, last_activity_date FROM user_streak WHERE user_id = ?");
            if ($stmt_streak) {
                $stmt_streak->bind_param("i", $user_id);
                $stmt_streak->execute();
                $streak_res = $stmt_streak->get_result();
                $streak_row = $streak_res ? $streak_res->fetch_assoc() : null;
                $stmt_streak->close();

                if ($streak_row) {
                    $last_date = $streak_row['last_activity_date'];
                    $new_current = (int)$streak_row['current_streak'];
                    $new_longest = (int)$streak_row['longest_streak'];

                    if (!empty($last_date)) {
                        $diff = (strtotime($today) - strtotime($last_date)) / 86400;
                        if ($diff == 1) {
                            $new_current++;
                            if ($new_current > $new_longest) { $new_longest = $new_current; }
                        } elseif ($diff > 1) {
                            $new_current = 1;
                        }
                        // diff == 0 (același zi) → nu modificăm nimic
                    } else {
                        $new_current = 1;
                        if ($new_longest < 1) { $new_longest = 1; }
                    }

                    if ($stmt_u = $con->prepare("UPDATE user_streak SET current_streak=?, longest_streak=?, last_activity_date=? WHERE user_id=?")) {
                        $stmt_u->bind_param('iisi', $new_current, $new_longest, $today, $user_id);
                        $stmt_u->execute();
                        $stmt_u->close();
                    }
                } else {
                    if ($stmt_i = $con->prepare("INSERT INTO user_streak (user_id, current_streak, longest_streak, last_activity_date) VALUES (?, 1, 1, ?)")) {
                        $stmt_i->bind_param('is', $user_id, $today);
                        $stmt_i->execute();
                        $stmt_i->close();
                    }
                }
            }
        } catch (Throwable $e) {
            error_log('login_post streak skipped: ' . $e->getMessage());
        }

        // FEATURE [F5]: Check and award achievements on login
        try {
            $newly_unlocked = check_and_award_achievements($con, $user_id);
        } catch (Throwable $e) {
            error_log('login_post achievements skipped: ' . $e->getMessage());
            $newly_unlocked = [];
        }
        if (!empty($newly_unlocked)) {
            $_SESSION['new_achievements'] = $newly_unlocked;
        }

        set_flash('success', 'Te-ai autentificat cu succes!');
        header('Location: ../index.php?page=metode');
        exit;
    }
}

// Dacă am ajuns aici: user sau parolă greșite
set_flash('error', 'Utilizator sau parolă incorecte');
header('Location: ../index.php?page=login');
exit;
