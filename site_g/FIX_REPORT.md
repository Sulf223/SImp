# FIX REPORT - SImp Portal

Rezumatul reparării bug-urilor identificate în auditul de securitate și funcționalitate.

| ID | Fișier | Linii | Justificare |
|:---|:---|:---|:---|
| **[H1]** | `PHP/lista_metode.php` | 68-74 | Înlocuire link GET cu formular POST + CSRF pentru ștergerea metodelor. |
| **[H2]** | `PHP/grila_interactiva.php` | 393-404 | Eliminare `innerHTML` la feedback-ul AI; utilizare `textContent` și `setAttribute` pentru a preveni XSS. |
| **[M1]** | `PHP/ajax_progres.php` | 42-53 | Adăugare verificare existență `id_grila` în `grile_cpp` înainte de a marca progresul. |
| **[M2]** | `index.php`, `PHP/*.php`, `pagini/*.php` | Multiplu | Implementare sistem `nonce` pentru CSP; eliminare `unsafe-inline` din script-src. |
| **[M3]** | `PHP/lista_metode.php`, `PHP/lista_exercitii.php` | 51-53, 40-42 | Înlocuire `mysqli_error()` afișat utilizatorului cu `error_log()` și mesaj generic. |
| **[M4]** | `PHP/auth.php` | 6-16 | Implementare timeout sesiune la 30 minute de inactivitate. |
| **[M5]** | `PHP/compilator_online.php` | 16-22 | Securizare cale fișier C++ folosind `realpath()` și verificare director (Path Traversal). |
| **[M6]** | `database/upgrade_unique_progress.sql` | 1-10 | Creare migrație pentru adăugare `UNIQUE KEY` pe `progres_grile(id_utilizator, id_grila)`. |
| **[L1]** | `PHP/profesor_ai_chat.php`, `PHP/ai_quiz_api.php` | 54-62, 22-28 | Sursă unică pentru `GROQ_API_KEY` via `getenv()`; returnare HTTP 503 dacă lipsește. |
| **[L2]** | `PHP/metoda_salveaza.php` | 33-40 | Adăugare validare `is_file()` și `filesize()` (<1MB) pentru fișierele C++ asociate. |
| **[L3]** | `PHP/grila_interactiva.php` | 208-212 | Redirecționare cu mesaj de eroare dacă `id_grila` solicitat nu există în baza de date. |
| **[L4]** | `PHP/helpers.php` | 107, 160 | Înlocuire `md5()` cu `hash('sha256')` pentru hashing-ul IP-ului în rate-limiting. |

## Notă SQL
A fost creat fișierul `site_g/database/upgrade_unique_progress.sql`. Acesta trebuie rulat manual în baza de date pentru a finaliza fix-ul **[M6]**.

## Validare
Toate fișierele au fost editate chirurgical pentru a menține logica de business intactă. S-au folosit prepared statements și helper-ele de securitate existente (`csrf_field`, `verify_csrf`, `set_flash`).
