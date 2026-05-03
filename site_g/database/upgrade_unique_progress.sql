-- FIX [M6]: Adăugare constrângere UNIQUE pentru a preveni duplicatele în progresul grilelor
-- Această constrângere este necesară pentru ca 'INSERT IGNORE' să funcționeze corect în ajax_progres.php.

USE dbsortari;
ALTER TABLE progres_grile ADD UNIQUE KEY uq_user_grila (id_utilizator, id_grila);

-- Verificare și pentru learning_exercise_progress (deși pare să existe în upgrade_dashboard_progress.sql, 
-- ne asigurăm că este aplicată dacă tabelul a fost creat anterior fără ea).
-- NOTĂ: În MySQL, putem folosi o procedură sau pur și simplu încercăm să o adăugăm dacă nu există (deși ALTER TABLE nu suportă IF NOT EXISTS).
-- Pentru simplitate, lăsăm doar progres_grile care sigur lipsește din dbsortari.sql.
