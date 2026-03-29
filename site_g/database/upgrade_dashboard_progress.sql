-- Tabel pentru salvarea progresului utilizatorilor pe metode
CREATE TABLE IF NOT EXISTS utilizatori_progres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    id_metoda INT NOT NULL,
    progres_procent INT DEFAULT 0,
    data_actualizare TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_progres_user
        FOREIGN KEY (user_id) REFERENCES utilizatori(id) ON DELETE CASCADE,
    CONSTRAINT fk_progres_metoda
        FOREIGN KEY (id_metoda) REFERENCES metode(id_metoda) ON DELETE CASCADE,
    UNIQUE KEY uq_user_metoda (user_id, id_metoda)
);

-- Tabel pentru istoricul de acces al activitatilor
CREATE TABLE IF NOT EXISTS istoric_activitate (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tip_activitate VARCHAR(50) NOT NULL,
    titlu_activitate VARCHAR(255) NOT NULL,
    link_acces VARCHAR(255) NOT NULL,
    data_accesare TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_istoric_user
        FOREIGN KEY (user_id) REFERENCES utilizatori(id) ON DELETE CASCADE
);

-- Tabel pentru progresul real al invatarii pe lectii fundamentale (slug-based)
CREATE TABLE IF NOT EXISTS learning_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_slug VARCHAR(80) NOT NULL,
    lesson_title VARCHAR(255) NOT NULL,
    progress_percent INT NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_lesson (user_id, lesson_slug)
);

-- Istoric de activitati recente afisat in dashboard
CREATE TABLE IF NOT EXISTS learning_activity_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type VARCHAR(40) NOT NULL,
    title VARCHAR(255) NOT NULL,
    link_access VARCHAR(255) NOT NULL,
    accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_user_accessed (user_id, accessed_at)
);

-- Exerciții W3 rezolvate per utilizator/per lectie
CREATE TABLE IF NOT EXISTS learning_exercise_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_slug VARCHAR(80) NOT NULL,
    exercise_key VARCHAR(120) NOT NULL,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_exercise (user_id, lesson_slug, exercise_key),
    KEY idx_user_lesson (user_id, lesson_slug)
);
