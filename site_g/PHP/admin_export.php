<?php
// PHP/admin_export.php — Export CSV pentru utilizatori și progres
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/helpers.php';

// FIX [A2]: Session timeout pentru AJAX/Exports
enforce_session_timeout_ajax();

if (!is_admin()) {
    http_response_code(403);
    echo "Acces interzis.";
    exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'users';
$tipuri_valide = ['users', 'progress'];
if (!in_array($type, $tipuri_valide, true)) {
    http_response_code(400);
    echo "Tip export invalid.";
    exit;
}

$timestamp = date('Y-m-d_His');
$filename = "simp_export_{$type}_{$timestamp}.csv";

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-cache, no-store, must-revalidate');

$out = fopen('php://output', 'w');
// BOM UTF-8 pentru Excel
fwrite($out, "\xEF\xBB\xBF");

if ($type === 'users') {
    fputcsv($out, ['id', 'username', 'rol', 'created_at', 'grile_rezolvate', 'exercitii_completate', 'lectii_accesate', 'streak_curent', 'streak_maxim', 'ultima_activitate']);
    $sql = "SELECT u.id, u.username, u.rol, u.created_at,
                   (SELECT COUNT(*) FROM progres_grile pg WHERE pg.id_utilizator = u.id) AS grile,
                   (SELECT COUNT(*) FROM learning_exercise_progress lep WHERE lep.user_id = u.id) AS exercitii,
                   (SELECT COUNT(*) FROM learning_progress lp WHERE lp.user_id = u.id) AS lectii,
                   (SELECT current_streak FROM user_streak us WHERE us.user_id = u.id) AS streak,
                   (SELECT longest_streak FROM user_streak us WHERE us.user_id = u.id) AS streak_max,
                   (SELECT MAX(accessed_at) FROM learning_activity_history h WHERE h.user_id = u.id) AS ultima
            FROM utilizatori u
            ORDER BY u.created_at DESC";
    if ($r = $con->query($sql)) {
        while ($row = $r->fetch_assoc()) {
            fputcsv($out, [
                $row['id'],
                $row['username'],
                $row['rol'],
                $row['created_at'],
                (int)$row['grile'],
                (int)$row['exercitii'],
                (int)$row['lectii'],
                (int)($row['streak'] ?? 0),
                (int)($row['streak_max'] ?? 0),
                $row['ultima'] ?? '',
            ]);
        }
    }
} elseif ($type === 'progress') {
    fputcsv($out, ['user_id', 'username', 'tip', 'detaliu', 'meta', 'data']);

    // Grile
    $r = $con->query(
        "SELECT u.id user_id, u.username, g.nume_metoda, g.dificultate, pg.data_completare
         FROM progres_grile pg
         JOIN utilizatori u ON u.id = pg.id_utilizator
         JOIN grile_cpp g ON g.id = pg.id_grila
         ORDER BY pg.data_completare DESC"
    );
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            fputcsv($out, [$row['user_id'], $row['username'], 'grila', $row['nume_metoda'], $row['dificultate'], $row['data_completare']]);
        }
    }

    // Exerciții
    $r = $con->query(
        "SELECT u.id user_id, u.username, lep.lesson_slug, lep.exercise_key, lep.completed_at
         FROM learning_exercise_progress lep
         JOIN utilizatori u ON u.id = lep.user_id
         ORDER BY lep.completed_at DESC"
    );
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            fputcsv($out, [$row['user_id'], $row['username'], 'exercitiu', $row['lesson_slug'], $row['exercise_key'], $row['completed_at']]);
        }
    }
}

fclose($out);
exit;
