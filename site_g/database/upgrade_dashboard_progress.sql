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
