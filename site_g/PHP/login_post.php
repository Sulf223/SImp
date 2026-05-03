<?php
// Procesare login
require_once __DIR__ . "/conexiune.php";
require_once __DIR__ . "/helpers.php"; // Includem helpers pentru set_flash și verify_csrf

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificăm CSRF
verify_csrf();

$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    set_flash('error', 'Completă utilizator și parolă');
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

        // Streaks logic
        $user_id = (int)$user['id'];
        $stmt_streak = $con->prepare("SELECT id, current_streak, max_streak, last_activity_date FROM user_streak WHERE user_id = ?");
        $stmt_streak->bind_param("i", $user_id);
        $stmt_streak->execute();
        $streak_res = $stmt_streak->get_result();
        $today = date('Y-m-d');
        if ($streak_res && $streak_row = $streak_res->fetch_assoc()) {
            $last_date = $streak_row['last_activity_date'];
            $diff = (strtotime($today) - strtotime($last_date)) / (60 * 60 * 24);
            $new_current = (int)$streak_row['current_streak'];
            $new_max = (int)$streak_row['max_streak'];
            
            if ($diff == 1) {
                // Consecutive day
                $new_current++;
                if ($new_current > $new_max) $new_max = $new_current;
            } elseif ($diff > 1) {
                // Streak broken
                $new_current = 1;
            } // If diff == 0, same day, do nothing to counts
            
            $stmt_streak = $con->prepare("UPDATE user_streak SET current_streak=?, max_streak=?, last_activity_date=? WHERE id=?");
            if ($stmt_streak) {
                $stmt_streak->bind_param('iisi', $new_current, $new_max, $today, $streak_row['id']);
                $stmt_streak->execute();
            }
        } else {
            // First time tracking
            $stmt_streak = $con->prepare("INSERT INTO user_streak (user_id, current_streak, max_streak, last_activity_date) VALUES (?, 1, 1, ?)");
            if ($stmt_streak) {
                $stmt_streak->bind_param('is', $user_id, $today);
                $stmt_streak->execute();
            }
        }

        // FEATURE [F5]: Check and award achievements on login
        $newly_unlocked = check_and_award_achievements($con, $user_id);
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
