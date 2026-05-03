<?php

function get_fundamental_lessons(): array {
    return [
        'sort_bubble' => ['title' => 'Bubble Sort (Metoda Bulelor)', 'link' => 'index.php?page=sort_bubble'],
        'sort_selection' => ['title' => 'Selection Sort', 'link' => 'index.php?page=sort_selection'],
        'sort_insertion' => ['title' => 'Insertion Sort', 'link' => 'index.php?page=sort_insertion'],
        'sort_quick' => ['title' => 'Quick Sort', 'link' => 'index.php?page=sort_quick'],
        'sort_merge' => ['title' => 'Merge Sort (Interclasare)', 'link' => 'index.php?page=sort_merge'],
        'sort_counting' => ['title' => 'Counting Sort', 'link' => 'index.php?page=sort_counting'],
    ];
}

function ensure_learning_tables(mysqli $con): void {
    $sql = [];

    $sql[] = "CREATE TABLE IF NOT EXISTS learning_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        lesson_slug VARCHAR(80) NOT NULL,
        lesson_title VARCHAR(255) NOT NULL,
        progress_percent INT NOT NULL DEFAULT 0,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uq_user_lesson (user_id, lesson_slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sql[] = "CREATE TABLE IF NOT EXISTS learning_activity_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        activity_type VARCHAR(40) NOT NULL,
        title VARCHAR(255) NOT NULL,
        link_access VARCHAR(255) NOT NULL,
        accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_user_accessed (user_id, accessed_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sql[] = "CREATE TABLE IF NOT EXISTS learning_exercise_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        lesson_slug VARCHAR(80) NOT NULL,
        exercise_key VARCHAR(120) NOT NULL,
        completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_user_exercise (user_id, lesson_slug, exercise_key),
        KEY idx_user_lesson (user_id, lesson_slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    foreach ($sql as $query) {
        $con->query($query);
    }
}

function track_lesson_visit(mysqli $con, int $userId, string $lessonSlug, string $lessonTitle, string $link): void {
    if ($userId <= 0 || $lessonSlug === '') {
        return;
    }

    ensure_learning_tables($con);

    $insertHistory = "INSERT INTO learning_activity_history (user_id, activity_type, title, link_access) VALUES (?, 'Lectie', ?, ?)";
    if ($stmt = $con->prepare($insertHistory)) {
        $stmt->bind_param('iss', $userId, $lessonTitle, $link);
        $stmt->execute();
        $stmt->close();
    }

    $upsert = "INSERT INTO learning_progress (user_id, lesson_slug, lesson_title, progress_percent)
               VALUES (?, ?, ?, 10)
               ON DUPLICATE KEY UPDATE
                    lesson_title = VALUES(lesson_title),
                    progress_percent = GREATEST(progress_percent, 10)";
    if ($stmt = $con->prepare($upsert)) {
        $stmt->bind_param('iss', $userId, $lessonSlug, $lessonTitle);
        $stmt->execute();
        $stmt->close();
    }

    update_streak($con, $userId);
}

function track_exercise_completion(mysqli $con, int $userId, string $lessonSlug, string $exerciseKey): int {
    if ($userId <= 0 || $lessonSlug === '' || $exerciseKey === '') {
        return 0;
    }

    ensure_learning_tables($con);

    $insert = "INSERT IGNORE INTO learning_exercise_progress (user_id, lesson_slug, exercise_key) VALUES (?, ?, ?)";
    if ($stmt = $con->prepare($insert)) {
        $stmt->bind_param('iss', $userId, $lessonSlug, $exerciseKey);
        $stmt->execute();
        $stmt->close();
    }

    $progress = recompute_progress_for_lesson($con, $userId, $lessonSlug);
    update_streak($con, $userId);

    // FEATURE [F5]: Check achievements after exercise completion
    $newly_unlocked = check_and_award_achievements($con, $userId);
    if (!empty($newly_unlocked)) {
        if (!isset($_SESSION['new_achievements'])) {
            $_SESSION['new_achievements'] = [];
        }
        $_SESSION['new_achievements'] = array_merge($_SESSION['new_achievements'], $newly_unlocked);
    }

    return $progress;
}

function recompute_progress_for_lesson(mysqli $con, int $userId, string $lessonSlug): int {
    $exerciseTotalMap = [
        'sort_bubble' => 3,
        'sort_selection' => 2,
        'sort_insertion' => 3,
        'sort_quick' => 3,
        'sort_merge' => 2,
        'sort_counting' => 2,
    ];

    $total = $exerciseTotalMap[$lessonSlug] ?? 1;
    $done = 0;

    $countSql = "SELECT COUNT(*) AS total_done FROM learning_exercise_progress WHERE user_id = ? AND lesson_slug = ?";
    if ($stmt = $con->prepare($countSql)) {
        $stmt->bind_param('is', $userId, $lessonSlug);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $done = (int)($row['total_done'] ?? 0);
        $stmt->close();
    }

    $exerciseWeight = min(100, (int)round(($done / max(1, $total)) * 90));
    $progress = max(10, min(100, $exerciseWeight));

    $lessons = get_fundamental_lessons();
    $title = $lessons[$lessonSlug]['title'] ?? $lessonSlug;

    $upsert = "INSERT INTO learning_progress (user_id, lesson_slug, lesson_title, progress_percent)
               VALUES (?, ?, ?, ?)
               ON DUPLICATE KEY UPDATE
                    lesson_title = VALUES(lesson_title),
                    progress_percent = VALUES(progress_percent)";

    if ($stmt = $con->prepare($upsert)) {
        $stmt->bind_param('issi', $userId, $lessonSlug, $title, $progress);
        $stmt->execute();
        $stmt->close();
    }

    return $progress;
}

function get_continue_learning(mysqli $con, int $userId): array {
    ensure_learning_tables($con);

    $sql = "SELECT lesson_slug, lesson_title, progress_percent, updated_at
            FROM learning_progress
            WHERE user_id = ?
            ORDER BY updated_at DESC
            LIMIT 1";

    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc() ?: [];
        $stmt->close();

        if (!empty($row)) {
            $lessons = get_fundamental_lessons();
            $slug = (string)$row['lesson_slug'];
            $row['link'] = $lessons[$slug]['link'] ?? 'index.php?page=sortare';
            return $row;
        }
    }

    return [
        'lesson_slug' => 'sort_bubble',
        'lesson_title' => 'Bubble Sort (Metoda Bulelor)',
        'progress_percent' => 0,
        'updated_at' => null,
        'link' => 'index.php?page=sort_bubble',
    ];
}

function get_recent_activity(mysqli $con, int $userId, int $limit = 3): array {
    ensure_learning_tables($con);

    $sql = "SELECT activity_type, title, link_access, accessed_at
            FROM learning_activity_history
            WHERE user_id = ?
            ORDER BY accessed_at DESC
            LIMIT ?";

    $items = [];
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('ii', $userId, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $items[] = $row;
        }
        $stmt->close();
    }

    return $items;
}

function get_exercise_stats(mysqli $con, int $userId, string $lessonSlug): array {
    ensure_learning_tables($con);

    $exerciseTotalMap = [
        'sort_bubble' => 3,
        'sort_selection' => 2,
        'sort_insertion' => 3,
        'sort_quick' => 3,
        'sort_merge' => 2,
        'sort_counting' => 2,
    ];

    $total = $exerciseTotalMap[$lessonSlug] ?? 0;
    $done = 0;

    $sql = "SELECT COUNT(*) AS total_done FROM learning_exercise_progress WHERE user_id = ? AND lesson_slug = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('is', $userId, $lessonSlug);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $done = (int)($row['total_done'] ?? 0);
        $stmt->close();
    }

    return ['done' => $done, 'total' => $total];
}

function ensure_streak_tables(mysqli $con): void {
    // Verificăm dacă tabelul principal există deja
    $check = $con->query("SHOW TABLES LIKE 'user_streak'");
    if ($check->num_rows === 0) {
        $sqlPath = __DIR__ . '/../database/upgrade_profile_streak.sql';
        if (file_exists($sqlPath)) {
            $sql = file_get_contents($sqlPath);
            if ($sql) {
                // Executăm scriptul SQL.
                if ($con->multi_query($sql)) {
                    do {
                        // Consumăm rezultatele pentru a elibera conexiunea
                        if ($result = $con->store_result()) {
                            $result->free();
                        }
                    } while ($con->more_results() && $con->next_result());
                }
            }
        }
    }
}

function update_streak(mysqli $con, int $userId): array {
    if ($userId <= 0) return ['current' => 0, 'longest' => 0];
    ensure_streak_tables($con);
    
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    
    // Citește streak existent
    $stmt = $con->prepare("SELECT current_streak, longest_streak, last_activity_date FROM user_streak WHERE user_id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc() ?: null;
    $stmt->close();
    
    $current = $row['current_streak'] ?? 0;
    $longest = $row['longest_streak'] ?? 0;
    $lastDate = $row['last_activity_date'] ?? null;
    
    if ($lastDate === $today) {
        // deja contat azi
    } elseif ($lastDate === $yesterday) {
        // continuat ieri → +1
        $current++;
    } else {
        // întrerupt → reset la 1
        $current = 1;
    }
    
    if ($current > $longest) $longest = $current;
    
    $upsert = "INSERT INTO user_streak (user_id, current_streak, longest_streak, last_activity_date) 
               VALUES (?, ?, ?, ?) 
               ON DUPLICATE KEY UPDATE current_streak = VALUES(current_streak), longest_streak = VALUES(longest_streak), last_activity_date = VALUES(last_activity_date)";
    $stmt = $con->prepare($upsert);
    $stmt->bind_param('iiis', $userId, $current, $longest, $today);
    $stmt->execute();
    $stmt->close();
    
    // Incrementează activity_day
    $activity = "INSERT INTO activity_day (user_id, activity_date, activity_count) VALUES (?, ?, 1) 
                 ON DUPLICATE KEY UPDATE activity_count = activity_count + 1";
    $stmt = $con->prepare($activity);
    $stmt->bind_param('is', $userId, $today);
    $stmt->execute();
    $stmt->close();
    
    return ['current' => $current, 'longest' => $longest, 'last_date' => $today];
}

function get_streak(mysqli $con, int $userId): array {
    ensure_streak_tables($con);
    $stmt = $con->prepare("SELECT current_streak, longest_streak FROM user_streak WHERE user_id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc() ?: ['current_streak' => 0, 'longest_streak' => 0];
    $stmt->close();
    return ['current' => (int)$row['current_streak'], 'longest' => (int)$row['longest_streak']];
}

function get_activity_heatmap(mysqli $con, int $userId, int $weeks = 26): array {
    ensure_streak_tables($con);
    $startDate = date('Y-m-d', strtotime("-{$weeks} weeks"));
    $stmt = $con->prepare("SELECT activity_date, activity_count FROM activity_day WHERE user_id = ? AND activity_date >= ?");
    $stmt->bind_param('is', $userId, $startDate);
    $stmt->execute();
    $res = $stmt->get_result();
    $map = [];
    while ($row = $res->fetch_assoc()) {
        $map[$row['activity_date']] = (int)$row['activity_count'];
    }
    $stmt->close();
    return $map; // {'2026-04-29': 5, ...}
}
