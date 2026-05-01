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
        mysqli_query($con, $query);
    }
}

function track_lesson_visit(mysqli $con, int $userId, string $lessonSlug, string $lessonTitle, string $link): void {
    if ($userId <= 0 || $lessonSlug === '') {
        return;
    }

    ensure_learning_tables($con);

    $insertHistory = "INSERT INTO learning_activity_history (user_id, activity_type, title, link_access) VALUES (?, 'Lectie', ?, ?)";
    if ($stmt = mysqli_prepare($con, $insertHistory)) {
        mysqli_stmt_bind_param($stmt, 'iss', $userId, $lessonTitle, $link);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $upsert = "INSERT INTO learning_progress (user_id, lesson_slug, lesson_title, progress_percent)
               VALUES (?, ?, ?, 10)
               ON DUPLICATE KEY UPDATE
                    lesson_title = VALUES(lesson_title),
                    progress_percent = GREATEST(progress_percent, 10)";
    if ($stmt = mysqli_prepare($con, $upsert)) {
        mysqli_stmt_bind_param($stmt, 'iss', $userId, $lessonSlug, $lessonTitle);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    update_streak($con, $userId);
}

function track_exercise_completion(mysqli $con, int $userId, string $lessonSlug, string $exerciseKey): int {
    if ($userId <= 0 || $lessonSlug === '' || $exerciseKey === '') {
        return 0;
    }

    ensure_learning_tables($con);

    $insert = "INSERT IGNORE INTO learning_exercise_progress (user_id, lesson_slug, exercise_key) VALUES (?, ?, ?)";
    if ($stmt = mysqli_prepare($con, $insert)) {
        mysqli_stmt_bind_param($stmt, 'iss', $userId, $lessonSlug, $exerciseKey);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $progress = recompute_progress_for_lesson($con, $userId, $lessonSlug);
    update_streak($con, $userId);
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
    if ($stmt = mysqli_prepare($con, $countSql)) {
        mysqli_stmt_bind_param($stmt, 'is', $userId, $lessonSlug);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        $done = (int)($row['total_done'] ?? 0);
        mysqli_stmt_close($stmt);
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

    if ($stmt = mysqli_prepare($con, $upsert)) {
        mysqli_stmt_bind_param($stmt, 'issi', $userId, $lessonSlug, $title, $progress);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
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

    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res) ?: [];
        mysqli_stmt_close($stmt);

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
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $limit);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($res)) {
            $items[] = $row;
        }
        mysqli_stmt_close($stmt);
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
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, 'is', $userId, $lessonSlug);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        $done = (int)($row['total_done'] ?? 0);
        mysqli_stmt_close($stmt);
    }

    return ['done' => $done, 'total' => $total];
}

function ensure_streak_tables(mysqli $con): void {
    // Verificăm dacă tabelul principal există deja
    $check = mysqli_query($con, "SHOW TABLES LIKE 'user_streak'");
    if (mysqli_num_rows($check) === 0) {
        $sqlPath = __DIR__ . '/../database/upgrade_profile_streak.sql';
        if (file_exists($sqlPath)) {
            $sql = file_get_contents($sqlPath);
            if ($sql) {
                // Executăm scriptul SQL. Notă: mysqli_multi_query poate fi periculos 
                // dacă scriptul are erori (ex: coloană existentă).
                // Folosim un bloc de ignorare a erorilor pentru ALTER TABLE dacă e nevoie.
                if (mysqli_multi_query($con, $sql)) {
                    do {
                        // Consumăm rezultatele pentru a elibera conexiunea
                        if ($result = mysqli_store_result($con)) {
                            mysqli_free_result($result);
                        }
                    } while (mysqli_more_results($con) && mysqli_next_result($con));
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
    $stmt = mysqli_prepare($con, "SELECT current_streak, longest_streak, last_activity_date FROM user_streak WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res) ?: null;
    mysqli_stmt_close($stmt);
    
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
    $stmt = mysqli_prepare($con, $upsert);
    mysqli_stmt_bind_param($stmt, 'iiis', $userId, $current, $longest, $today);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Incrementează activity_day
    $activity = "INSERT INTO activity_day (user_id, activity_date, activity_count) VALUES (?, ?, 1) 
                 ON DUPLICATE KEY UPDATE activity_count = activity_count + 1";
    $stmt = mysqli_prepare($con, $activity);
    mysqli_stmt_bind_param($stmt, 'is', $userId, $today);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return ['current' => $current, 'longest' => $longest, 'last_date' => $today];
}

function get_streak(mysqli $con, int $userId): array {
    ensure_streak_tables($con);
    $stmt = mysqli_prepare($con, "SELECT current_streak, longest_streak FROM user_streak WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res) ?: ['current_streak' => 0, 'longest_streak' => 0];
    mysqli_stmt_close($stmt);
    return ['current' => (int)$row['current_streak'], 'longest' => (int)$row['longest_streak']];
}

function get_activity_heatmap(mysqli $con, int $userId, int $weeks = 26): array {
    ensure_streak_tables($con);
    $startDate = date('Y-m-d', strtotime("-{$weeks} weeks"));
    $stmt = mysqli_prepare($con, "SELECT activity_date, activity_count FROM activity_day WHERE user_id = ? AND activity_date >= ?");
    mysqli_stmt_bind_param($stmt, 'is', $userId, $startDate);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $map = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $map[$row['activity_date']] = (int)$row['activity_count'];
    }
    mysqli_stmt_close($stmt);
    return $map; // {'2026-04-29': 5, ...}
}
