<?php
// PHP/admin_actions.php — Handler POST pentru acțiuni admin
// Toate acțiunile cer: is_admin() + verify_csrf() + POST + user_id != self pentru change_role/delete

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/conexiune.php';
require_once __DIR__ . '/helpers.php';

// FIX [A2]: Session timeout pentru AJAX/POST handlers
enforce_session_timeout_ajax();

if (!is_admin()) {
    set_flash("error", "Acces interzis.");
    header("Location: ../index.php?page=acasa");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?page=admin&tab=actiuni");
    exit;
}

if (!verify_csrf()) {
    set_flash("error", "Token CSRF invalid. Reîncarcă pagina.");
    header("Location: ../index.php?page=admin&tab=actiuni");
    exit;
}

$action  = trim((string)($_POST['action'] ?? ''));
$user_id = (int)($_POST['user_id'] ?? 0);
$self_id = (int)($_SESSION['user_id'] ?? 0);

if ($user_id <= 0) {
    set_flash("error", "ID utilizator invalid.");
    header("Location: ../index.php?page=admin&tab=actiuni");
    exit;
}

// Verifică existența user-ului
$target_user = null;
if ($stmt = $con->prepare("SELECT id, username, rol FROM utilizatori WHERE id = ?")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $rs = $stmt->get_result();
    $target_user = $rs->fetch_assoc();
    $stmt->close();
}

if (!$target_user) {
    set_flash("error", "Utilizatorul nu există.");
    header("Location: ../index.php?page=admin&tab=actiuni");
    exit;
}

// ============== CHANGE ROLE ==============
if ($action === 'change_role') {
    if ($user_id === $self_id) {
        set_flash("error", "Nu îți poți schimba propriul rol.");
        header("Location: ../index.php?page=admin&tab=actiuni");
        exit;
    }
    $new_role = trim((string)($_POST['new_role'] ?? ''));
    if (!in_array($new_role, ['user', 'admin'], true)) {
        set_flash("error", "Rol invalid.");
        header("Location: ../index.php?page=admin&tab=actiuni");
        exit;
    }
    if ($stmt = $con->prepare("UPDATE utilizatori SET rol = ? WHERE id = ?")) {
        $stmt->bind_param("si", $new_role, $user_id);
        if ($stmt->execute()) {
            log_admin_action($con, 'change_role', $user_id, $target_user['username'],
                json_encode(['from' => $target_user['rol'], 'to' => $new_role], JSON_UNESCAPED_UNICODE));
            set_flash("success", "Rolul utilizatorului „{$target_user['username']}” a fost schimbat în „{$new_role}”.");
        } else {
            error_log("admin_actions change_role failed: " . $con->error);
            set_flash("error", "Eroare la actualizarea rolului.");
        }
        $stmt->close();
    }
    header("Location: ../index.php?page=admin&tab=actiuni");
    exit;
}

// ============== RESET PROGRESS ==============
if ($action === 'reset_progress') {
    // Tranzacție: șterge progresul din toate tabelele
    $con->begin_transaction();
    try {
        $tabele_progres = [
            "DELETE FROM progres_grile WHERE id_utilizator = ?",
            "DELETE FROM learning_exercise_progress WHERE user_id = ?",
            "DELETE FROM learning_progress WHERE user_id = ?",
            "DELETE FROM learning_activity_history WHERE user_id = ?",
            "DELETE FROM utilizatori_progres WHERE user_id = ?",
            "DELETE FROM istoric_activitate WHERE user_id = ?",
            "DELETE FROM activity_day WHERE user_id = ?",
            "UPDATE user_streak SET current_streak = 0, longest_streak = 0, last_activity_date = NULL WHERE user_id = ?",
        ];
        foreach ($tabele_progres as $sql) {
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();
            }
            // Tabele opționale care pot lipsi -> ignorăm eroarea silențios
        }
        $con->commit();
        log_admin_action($con, 'reset_progress', $user_id, $target_user['username']);
        set_flash("success", "Progresul utilizatorului „{$target_user['username']}” a fost resetat.");
    } catch (Throwable $e) {
        $con->rollback();
        error_log("admin_actions reset_progress: " . $e->getMessage());
        set_flash("error", "Eroare la resetarea progresului.");
    }
    header("Location: ../index.php?page=admin&tab=actiuni");
    exit;
}

// ============== DELETE USER ==============
if ($action === 'delete_user') {
    if ($user_id === $self_id) {
        set_flash("error", "Nu îți poți șterge propriul cont.");
        header("Location: ../index.php?page=admin&tab=actiuni");
        exit;
    }
    // Foreign key-urile au ON DELETE CASCADE pentru user_streak/activity_day/utilizatori_progres,
    // dar pentru tabelele MyISAM/fără FK trebuie să curățăm manual
    $con->begin_transaction();
    try {
        $tabele_cleanup = [
            "DELETE FROM progres_grile WHERE id_utilizator = ?",
            "DELETE FROM learning_exercise_progress WHERE user_id = ?",
            "DELETE FROM learning_progress WHERE user_id = ?",
            "DELETE FROM learning_activity_history WHERE user_id = ?",
            "DELETE FROM istoric_activitate WHERE user_id = ?",
            "DELETE FROM utilizatori_progres WHERE user_id = ?",
            "DELETE FROM activity_day WHERE user_id = ?",
            "DELETE FROM user_streak WHERE user_id = ?",
            "DELETE FROM utilizatori WHERE id = ?",
        ];
        foreach ($tabele_cleanup as $sql) {
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();
            }
        }
        $con->commit();
        // Log AFTER commit dar cu username snapshot, deoarece user-ul nu mai există
        log_admin_action($con, 'delete_user', $user_id, $target_user['username'],
            json_encode(['rol_la_stergere' => $target_user['rol']], JSON_UNESCAPED_UNICODE));
        set_flash("success", "Contul „{$target_user['username']}” a fost șters definitiv.");
    } catch (Throwable $e) {
        $con->rollback();
        error_log("admin_actions delete_user: " . $e->getMessage());
        set_flash("error", "Eroare la ștergerea contului.");
    }
    header("Location: ../index.php?page=admin&tab=actiuni");
    exit;
}

set_flash("error", "Acțiune necunoscută.");
header("Location: ../index.php?page=admin&tab=actiuni");
exit;
