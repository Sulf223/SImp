# Ghid de funcționare - OffByOne Academy

Actualizat: 20 mai 2026

Acest ghid explică pe scurt cum funcționează proiectul, ce rol au folderele și ce face fiecare fișier important. Este gândit pentru prezentare, întrebări tehnice și înțelegerea rapidă a codului.

## 1. Ideea proiectului

OffByOne Academy este o platformă web educațională pentru învățarea algoritmilor, în special metode de sortare în C++. Aplicația combină:

- lecții teoretice despre algoritmi;
- laborator vizual pas cu pas;
- exerciții de completare cod;
- grile interactive;
- comparații între algoritmi;
- profil cu progres, streak și realizări;
- Profesor AI care răspunde pe baza fișierelor din `proiect_documentatie`;
- resetare parolă cu email local prin Mailpit;
- administrare și audit pentru partea de management.

Pe scurt, proiectul nu este doar un site static. Este o aplicație PHP cu MySQL, rulată în Docker, cu interfață modernă în HTML/CSS/JavaScript.

## 2. Cum rulează proiectul

Proiectul rulează prin Docker Compose.

Serviciile principale sunt:

| Serviciu | Rol | URL local |
|---|---|---|
| `web` | aplicația PHP/Apache | `http://localhost:8082` |
| `db` | baza de date MySQL | port intern `3306`, extern `3308` |
| `phpmyadmin` | administrare vizuală DB | `http://localhost:8081` |
| `mailpit` | inbox local pentru emailuri de test | `http://localhost:8025` |

Comandă uzuală:

```powershell
docker compose up -d
```

Ce poți spune la prezentare:

> Aplicația rulează containerizat cu Docker. Containerul `web` servește codul PHP, `db` ține baza de date MySQL, `phpMyAdmin` este folosit pentru administrare, iar `Mailpit` captează emailurile de resetare parolă în mediu local.

## 3. Fluxul general al aplicației

1. Utilizatorul intră pe `index.php`.
2. `index.php` citește parametrul `page` din URL.
3. Dacă pagina există în lista `$pagini_permise`, include fișierul potrivit.
4. Dacă pagina cere autentificare, utilizatorul este redirecționat spre login.
5. Paginile afișează HTML, iar JavaScript-ul adaugă interactivitate.
6. Pentru date persistente, PHP comunică cu MySQL prin `PHP/conexiune.php`.
7. Pentru AI, frontend-ul trimite cereri către endpoint-uri PHP care comunică cu Groq sau folosesc fallback local.

Exemplu de rută:

```text
http://localhost:8082/index.php?page=sort_bubble
```

Aceasta încarcă:

```text
site_g/pagini/sort_bubble.php
```

## 4. Foldere principale

| Folder | Rol |
|---|---|
| `site_g/` | aplicația web propriu-zisă |
| `site_g/PHP/` | endpoint-uri, autentificare, DB, API-uri |
| `site_g/pagini/` | paginile afișate în interfață |
| `site_g/JS/` | logica frontend: AI widget, vizualizator, grile, exerciții |
| `site_g/CSS/` | stiluri moderne, teme, layout |
| `site_g/CPP/` | exemple C++ folosite ca referință educațională |
| `site_g/database/` | upgrade-uri SQL pentru tabele noi |
| `site_g/storage/` | indexul documentației pentru AI |
| `migrations/` | migrări SQL suplimentare |
| `proiect_documentatie/` | sursa de documentație pe baza căreia răspunde AI-ul |
| `tests/` | teste automate |
| `scripts/` | scripturi utilitare |

## 5. Fișiere din rădăcina proiectului

| Fișier | Ce face |
|---|---|
| `docker-compose.yml` | definește containerele `web`, `db`, `phpmyadmin`, `mailpit` |
| `Dockerfile` | construiește imaginea PHP/Apache pentru aplicație |
| `.env` | variabile locale reale: API key, configurări SMTP/DB |
| `.env.example` | exemplu de configurare fără secrete |
| `.dockerignore` | exclude fișiere inutile din build-ul Docker |
| `.gitignore` | exclude fișiere generate sau locale din Git |
| `.gitattributes` | reguli de normalizare pentru Git |
| `composer.json` | dependențe PHP și autoload, dacă se instalează prin Composer |
| `phpunit.xml` | configurare PHPUnit pentru teste |
| `Makefile` | comenzi scurte pentru rulare/testare |
| `run.bat`, `start.bat`, `start.ps1`, `start.sh` | scripturi de pornire pe Windows/Linux |
| `README.md` | prezentare generală proiect |
| `QUICKSTART.md` | pași rapizi de pornire |
| `STARTUP_GUIDE.md` | ghid de pornire mai detaliat |
| `DOCKER_README.md` | explicații dedicate pentru Docker |
| `INDEX.md` | index de documentație al proiectului |
| `COMENZI_CMD.txt` | comenzi utile folosite în dezvoltare |
| `AUDIT_FULL.md`, `AUDIT_COPILOT_2026-05-04.md`, `AUDIT_R7_REPORT.md` | rapoarte de audit/verificare |
| `PROMPT_GEMINI_AUDIT_R7.md`, `PROMPT_GEMINI_LOGIC_R8.md` | prompturi folosite pentru audit extern |
| `GEMINI.md` | notițe legate de verificări cu Gemini |
| `WORKSPACE_DUMP.md` | dump mare al workspace-ului, util pentru analiză, nu pentru rulare |
| `audit.php` | fișier auxiliar de audit PHP |
| `Prezentare_*.pptx/pdf` | prezentările proiectului |
| `Lucrare_*.docx/pdf` | lucrarea scrisă |
| `Intrebari_Simpozion_OffByOne.*` | set de întrebări pentru pregătirea prezentării |
| `Ghid_*.docx/pdf/txt` | ghiduri generate pentru pregătire |

Fișierele `~$...` sunt fișiere temporare create de Microsoft Office și nu fac parte din aplicație.

## 6. Punctul central: `site_g/index.php`

`site_g/index.php` este layout-ul principal al aplicației.

Roluri:

- pornește sesiunea;
- setează cookie-uri securizate;
- configurează CSP și headere de securitate;
- include `PHP/helpers.php` și `PHP/auth.php`;
- definește lista de pagini permise în `$pagini_permise`;
- decide ce pagină se încarcă în funcție de `?page=...`;
- blochează accesul la pagini care cer login sau admin;
- generează titlul paginii;
- include CSS-urile globale;
- afișează navbar-ul;
- include pagina cerută;
- afișează widget-ul flotant Profesor AI;
- include scripturile globale.

Întrebare posibilă:

> Cum previi includerea arbitrară de fișiere prin URL?

Răspuns:

> `index.php` nu include direct ce vine din `$_GET['page']`. Verifică valoarea într-un whitelist numit `$pagini_permise`. Dacă pagina nu există acolo, se afișează 404.

## 7. Paginile din `site_g/pagini/`

Aceste fișiere sunt pagini vizibile în aplicație. Ele sunt incluse de `index.php`.

| Fișier | Rol |
|---|---|
| `bun_venit.php` | pagina inițială/hero interactiv înainte de dashboard |
| `acasa.php` | dashboard-ul utilizatorului: progres, continuă învățarea, activitate |
| `algoritmi.php` | indexul de teorie, carduri către algoritmi fundamentali și tehnici |
| `sortare.php` | pagina cu metodele de sortare disponibile |
| `sort_bubble.php` | conținutul lecției Bubble Sort |
| `sort_selection.php` | conținutul lecției Selection Sort |
| `sort_insertion.php` | conținutul lecției Insertion Sort |
| `sort_quick.php` | conținutul lecției Quick Sort |
| `sort_merge.php` | conținutul lecției Merge Sort |
| `sort_counting.php` | conținutul lecției Counting Sort |
| `partials/sort_lesson_template.php` | template comun pentru paginile de sortare |
| `laborator_vizual.php` | laborator vizual unificat pentru sortări, recursivitate și backtracking |
| `comparatii_sortare.php` | benchmark și comparații între algoritmi |
| `algoritmi_fundamentali.php` | noțiuni de bază: cifre, divizori, CMMDC, prime, frecvențe |
| `algoritmi_avansati.php` | hub pentru recursivitate, backtracking, greedy, divide et impera |
| `recursivitate.php` | lecție despre recursivitate |
| `backtracking.php` | lecție despre backtracking |
| `greedy.php` | lecție despre greedy |
| `divide_et_impera.php` | lecție despre divide et impera |
| `invatare.php` | drumuri de învățare recomandate |
| `profesor_ai.php` | pagina AI & Quiz: test AI, istoric scoruri, acces chat |
| `profil.php` | profil utilizator, progres, realizări, streak |
| `admin.php` | panou de administrare pentru admin |
| `changelog.php` | istoric modificări/versiuni |
| `forgot_password.php` | formular pentru recuperarea parolei |
| `reset_password.php` | formular pentru setarea unei parole noi |
| `proiecte.php` | pagină auxiliară pentru proiecte |
| `404.php` | pagină pentru rute inexistente |

### Cum funcționează lecțiile de sortare

Fișierele `sort_bubble.php`, `sort_selection.php`, `sort_insertion.php`, `sort_quick.php`, `sort_merge.php`, `sort_counting.php` definesc câte un array `$lesson`. În acel array sunt puse:

- titlul;
- descrierea;
- ideea de bază;
- complexități;
- pași;
- greșeli frecvente;
- variabile urmărite;
- cod C++;
- pseudocod sincronizat;
- algoritmul folosit de canvas.

Apoi fiecare fișier apelează:

```php
render_sort_lesson($lesson, $nonce);
```

Template-ul comun `partials/sort_lesson_template.php` transformă acele date în interfață.

Avantaj:

> Toate lecțiile au aceeași structură vizuală și aceeași logică, iar conținutul diferă doar prin array-ul lecției.

## 8. Fișiere PHP din `site_g/PHP/`

Aceste fișiere sunt endpoint-uri, helper-e sau pagini vechi încă folosite.

| Fișier | Rol |
|---|---|
| `conexiune.php` | conectare la MySQL folosind variabile de mediu |
| `config.php` | configurări generale și compatibilitate |
| `helpers.php` | CSRF, flash messages, rate limit, email, URL-uri, utilitare |
| `auth.php` | verifică login, rol admin, timeout sesiune |
| `login.php` | formular login |
| `login_post.php` | procesează login, verifică parola cu `password_verify` |
| `register.php` | formular înregistrare |
| `register_post.php` | creează utilizator nou, parole hash-uite cu bcrypt |
| `logout.php` | distruge sesiunea |
| `forgot_password_post.php` | generează token resetare parolă și trimite email |
| `reset_password_post.php` | verifică token și setează parolă nouă |
| `profesor_ai_chat.php` | endpoint pentru chat AI pe baza documentației |
| `ai_quiz_api.php` | generează și evaluează teste AI |
| `ai_code_feedback.php` | oferă feedback AI pentru cod C++ |
| `ai_status.php` | verifică starea serviciului AI pentru indicatorul din UI |
| `documentation_context.php` | caută fragmente relevante în `storage/documentation_index.json` |
| `progres_learning.php` | tabele și funcții pentru progres, streak, realizări |
| `progres_api.php` | endpoint AJAX pentru progres lecții/exerciții |
| `ajax_progres.php` | endpoint vechi pentru progres |
| `quiz_attempt.php` | salvează încercările la grile |
| `grile.php` | pagina grilelor clasice |
| `grila_interactiva.php` | grilă interactivă cu moduri și salvare scor |
| `lista_metode.php` | listare metode din DB |
| `lista_exercitii.php` | listare exerciții vechi, redirecționată în mare spre laborator |
| `metoda.php` | detalii despre o metodă din DB |
| `metoda_form.php` | formular admin pentru metodă |
| `metoda_salveaza.php` | salvează o metodă |
| `metoda_sterge.php` | șterge o metodă |
| `compilator_online.php` | integrează compilatorul online |
| `admin_actions.php` | acțiuni admin: utilizatori, resetări, ștergeri |
| `admin_export.php` | export date admin în CSV |
| `bun_venit.php` | fișier PHP vechi/auxiliar pentru bun venit |

### Securitate în PHP

Elemente importante:

- parolele sunt hash-uite, nu salvate în clar;
- formularele folosesc CSRF token;
- endpoint-urile AJAX verifică `X-CSRF-Token`;
- sesiunile expiră după inactivitate;
- paginile admin cer rol admin;
- rate limit pentru login, resetare parolă, AI, quiz;
- CSP în `index.php`;
- whitelist pentru pagini.

## 9. JavaScript din `site_g/JS/`

| Fișier | Rol |
|---|---|
| `visualizer.js` | cel mai important JS: canvas sortări, laborator unificat, pași, pseudocod, statistici, buton Explică-mi |
| `performance_compare.js` | benchmark pentru pagina de comparații: rulează algoritmi, generează grafic/tabel/concluzie |
| `exercitii.js` | exerciții fill-in-the-blank pentru lecțiile de sortare |
| `exercitii_avansate.js` | exerciții pentru recursivitate/backtracking |
| `fundamental_visualizer.js` | vizualizări pentru algoritmi fundamentali |
| `ai_widget.js` | widget-ul flotant Profesor AI, istoric local, submit către `profesor_ai_chat.php` |
| `ai_code_feedback.js` | trimite cod spre feedback AI |
| `lesson_tracker.js` | trimite progres de lecție către API |
| `toast.js` | toast-uri/notificări în UI |
| `validare.js` | validări frontend pentru formulare |
| `utf8_normalize.js` | corectează texte cu probleme de encoding/mojibake |
| `sw_register.js` | înregistrare service worker |

### `visualizer.js`

Conține două clase mari:

1. `SortingVisualizer`
   - folosit în paginile individuale de sortare;
   - desenează vectorul pe canvas;
   - rulează Bubble, Selection, Insertion, Quick, Merge, Counting;
   - actualizează comparații, swap-uri, timp, stare;
   - sincronizează pseudocodul cu pașii;
   - trimite cereri la AI pentru explicații.

2. `AlgorithmLab`
   - folosit în `laborator_vizual.php`;
   - permite alegerea algoritmului;
   - are pași înainte/înapoi, rulare, reset;
   - afișează explicații și pseudocod pentru laborator.

### `performance_compare.js`

Conține:

- definiții pentru algoritmi;
- scenarii de comparație;
- funcții pentru generarea dataset-urilor;
- benchmark în browser;
- desenarea graficului;
- tabel comparativ;
- concluzie automată;
- resetare date la schimbarea scenariului.

## 10. CSS din `site_g/CSS/`

| Fișier | Rol |
|---|---|
| `modern_vars.css` | variabile CSS globale: culori, spațieri, fonturi, radius |
| `dashboard_modern.css` | stilul principal al aplicației: carduri, navbar, bento layout, widget AI, profil, laborator |
| `sortare.css` | stiluri dedicate paginii cu metode de sortare |
| `bun_venit.css` | stiluri pentru pagina inițială/solar hero |
| `admin.css` | stiluri pentru panoul de administrare |
| `stil.css` | stiluri legacy/globale mai vechi |

CSS-ul este împărțit între variabile (`modern_vars.css`), componenta principală (`dashboard_modern.css`) și câteva fișiere specifice.

## 11. Baza de date

Schema inițială este în:

```text
site_g/dbsortari.sql
```

Fișierul pentru import manual în phpMyAdmin:

```text
site_g/dbsortari_for_phpmyadmin.sql
```

Tabele importante:

| Tabel | Rol |
|---|---|
| `utilizatori` | conturi, roluri, date profil |
| `metode` | metode/algoritmi din aplicația inițială |
| `grile_cpp` | întrebări grilă |
| `progres_grile` | progres pe grile |
| `rezultate` | rezultate vechi/istoric |
| `exercitii` | exerciții vechi din DB |
| `learning_progress` | progres pe lecții |
| `learning_activity_history` | istoric accesări lecții |
| `learning_exercise_progress` | exerciții completate pe lecții |
| `rate_limit_attempts` | limite cereri login/AI/resetare |
| `password_resets` | tokenuri de resetare parolă |
| `admin_audit_log` | jurnal acțiuni admin |
| `achievements`, `user_achievements` | realizări și deblocări |
| `quiz_attempts` | încercări grile clasice |
| `ai_quiz_attempts` | scoruri la testele AI |

## 12. Migrații SQL

### `site_g/database/`

| Fișier | Rol |
|---|---|
| `upgrade_dashboard_progress.sql` | adaugă tabele/progres dashboard |
| `upgrade_recursivitate_backtracking.sql` | suport DB pentru module avansate |
| `upgrade_profile_streak.sql` | profil, streak, câmpuri utilizator |
| `upgrade_rate_limit.sql` | tabel pentru limitarea cererilor |
| `upgrade_learning_paths.sql` | drumuri de învățare |
| `upgrade_password_reset.sql` | resetare parolă |
| `upgrade_admin_audit_log.sql` | audit admin |
| `upgrade_achievements.sql` | realizări/achievements |
| `upgrade_unique_progress.sql` | constrângeri pentru progres unic |

### `migrations/`

| Fișier | Rol |
|---|---|
| `20260505_add_doc_link_to_grile_cpp.sql` | adaugă legături spre documentație la grile |
| `20260512_audit_grile_questions.sql` | corectează/auditează întrebări grilă |
| `20260512_fix_achievement_encoding.sql` | repară encoding pentru achievements |
| `20260512_quiz_attempts.sql` | tabel pentru încercări quiz |
| `20260512_ai_quiz_attempts.sql` | tabel pentru testele AI |
| `20260513_update_learning_paths.sql` | actualizează drumurile de învățare |

## 13. AI și documentație

AI-ul nu răspunde doar generic. El folosește indexul:

```text
site_g/storage/documentation_index.json
```

Acest index este construit din fișierele din:

```text
proiect_documentatie/
```

Fișierul-cheie este:

```text
site_g/PHP/documentation_context.php
```

Ce face:

- normalizează întrebarea;
- caută termeni relevanți;
- folosește sinonime;
- selectează fragmente relevante din documentație;
- returnează text + surse către endpoint-ul AI.

Endpoint-uri AI:

| Endpoint | Ce face |
|---|---|
| `PHP/profesor_ai_chat.php` | chat Profesor AI |
| `PHP/ai_quiz_api.php` | generează/evaluează teste AI |
| `PHP/ai_code_feedback.php` | feedback pe cod C++ |
| `PHP/ai_status.php` | status pentru indicatorul AI |

Fallback AI:

- dacă Groq dă `HTTP 429`, chat-ul răspunde local din documentație;
- testul AI poate genera întrebări locale de rezervă;
- feedback-ul pe cod dă observații locale.

Ce poți spune:

> Profesorul AI folosește documentația proiectului ca sursă principală. În backend, întrebarea este mapată la fragmente relevante din `documentation_index.json`, apoi acele fragmente sunt trimise către model. Dacă API-ul extern are limită temporară, aplicația are fallback local ca să nu se blocheze.

## 14. Resetare parolă și Mailpit

Flux:

1. Utilizatorul intră pe `forgot_password.php`.
2. Formularul trimite către `forgot_password_post.php`.
3. Backend-ul generează un token.
4. Tokenul este salvat în DB.
5. Se trimite email prin SMTP.
6. În Docker, emailul ajunge în Mailpit.
7. Linkul duce la `reset_password.php?token=...`.
8. `reset_password_post.php` setează parola nouă.

Mailpit este un inbox local de test. Nu trimite email pe internet, ci îl capturează în browser la:

```text
http://localhost:8025
```

## 15. Grile și teste

Sunt două zone:

1. Grile clasice:
   - `PHP/grile.php`
   - `PHP/grila_interactiva.php`
   - `PHP/quiz_attempt.php`
   - tabel DB: `grile_cpp`, `quiz_attempts`

2. Test AI:
   - `pagini/profesor_ai.php`
   - `PHP/ai_quiz_api.php`
   - tabel DB: `ai_quiz_attempts`

Testul AI cere login pentru a salva evoluția scorurilor.

## 16. Profil, progres și realizări

Fișiere relevante:

- `pagini/profil.php`
- `PHP/progres_learning.php`
- `PHP/progres_api.php`
- `JS/lesson_tracker.js`
- `JS/exercitii.js`

Flux:

1. Utilizatorul intră într-o lecție.
2. `lesson_tracker.js` poate marca accesarea.
3. Când rezolvă exerciții, `exercitii.js` trimite progres.
4. `progres_api.php` primește cererea.
5. `progres_learning.php` actualizează tabelele.
6. Profilul citește progresul, streak-ul și realizările.

## 17. Admin

Fișiere:

- `pagini/admin.php`
- `PHP/admin_actions.php`
- `PHP/admin_export.php`

Roluri:

- doar utilizatorii cu `rol = admin` pot intra;
- adminul poate vedea statistici, utilizatori, activitate;
- acțiunile importante se pot jurnaliza în `admin_audit_log`;
- exportul se face prin CSV.

## 18. C++ din `site_g/CPP/`

Aceste fișiere sunt exemple educaționale. Ele nu rulează direct în aplicația PHP, dar sunt folosite ca material de învățare, referință pentru grile, documentație și AI.

| Fișier | Conținut |
|---|---|
| `BubbleSort.cpp` | implementare Bubble Sort |
| `Selectie.cpp`, `ord3_selectie.cpp` | Selection Sort |
| `InsertDirect.cpp`, `ord5-insD.cpp` | Insertion Sort direct |
| `InsertieBinara.cpp`, `InsertieBinara_distincte.cpp` | inserție binară |
| `quick1.cpp`, `quicks.cpp` | Quick Sort |
| `Interclasare.cpp`, `Interclasareegale.cpp`, `Sortare_Interclasare.cpp` | interclasare/Merge Sort |
| `SortNumarare.cpp`, `SortFrecventa.cpp`, `Ord4-numarare.cpp` | Counting/Frequency Sort |
| `InterschimbareS.cpp` | sortare prin interschimbare |
| `Vector_STL.cpp` | exemplu vector STL |
| `Candidati.cpp` | exemplu candidați/tehnică algoritmică |
| `Aplicatia1_ordonare.cpp`, `Aplicatia1_ordonare_produse.cpp` | aplicații de ordonare |
| `Laborator2_ordonare_rezolvare.cpp` | rezolvare laborator ordonare |
| `Tema_ordonare_rez.cpp` | temă rezolvată |
| `main.cpp` | exemplu principal C++ |

## 19. Service worker și PWA

Fișiere:

- `site_g/manifest.json`
- `site_g/sw.js`
- `site_g/JS/sw_register.js`

Rol:

- manifest pentru aplicație;
- service worker pentru cache;
- înregistrare service worker în browser.

Dacă nu vrei să menționezi PWA la prezentare, poți spune simplu:

> Există și fișiere pentru cache în browser, dar partea principală a proiectului este aplicația educațională PHP/MySQL.

## 20. Fluxuri importante explicate simplu

### Login

1. `login.php` afișează formularul.
2. `login_post.php` verifică CSRF.
3. Caută utilizatorul în DB.
4. Verifică parola cu `password_verify`.
5. Salvează în sesiune `user_id`, `username`, `rol`.
6. Redirecționează spre dashboard.

### Înregistrare

1. `register.php` afișează formularul.
2. `register_post.php` validează datele.
3. Parola este hash-uită.
4. Utilizatorul este inserat în `utilizatori`.

### Lecție de sortare

1. URL: `index.php?page=sort_bubble`.
2. `index.php` include `pagini/sort_bubble.php`.
3. Lecția definește array-ul `$lesson`.
4. Template-ul comun afișează teoria, codul, canvasul și exercițiile.
5. `visualizer.js` controlează animația.
6. `exercitii.js` controlează exercițiul de completare.
7. `progres_api.php` salvează progresul.

### Laborator vizual

1. URL: `index.php?page=laborator_vizual`.
2. Pagina creează containerul `#algorithms-lab`.
3. `visualizer.js` pornește `AlgorithmLab`.
4. Utilizatorul alege algoritmul, dimensiunea, presetul și viteza.
5. JS generează pașii și îi afișează sincronizat.

### Comparații

1. URL: `index.php?page=comparatii_sortare`.
2. `performance_compare.js` are algoritmii implementați în JS.
3. Utilizatorul alege scenariul și parametrii.
4. Benchmark-ul rulează în browser.
5. Se actualizează graficul, tabelul și concluzia.

### AI

1. Frontend-ul trimite textul către `profesor_ai_chat.php`.
2. Backend-ul caută context în documentație.
3. Contextul și întrebarea sunt trimise la Groq.
4. Răspunsul se afișează în widget.
5. Dacă API-ul extern dă 429, se folosește fallback local.

## 21. Ce fișiere aș menționa dacă mă întreabă profesorul

Pentru arhitectură:

- `site_g/index.php`
- `docker-compose.yml`
- `site_g/PHP/conexiune.php`
- `site_g/PHP/helpers.php`
- `site_g/PHP/auth.php`

Pentru algoritmi și vizualizare:

- `site_g/pagini/partials/sort_lesson_template.php`
- `site_g/pagini/sort_bubble.php` etc.
- `site_g/JS/visualizer.js`
- `site_g/JS/performance_compare.js`

Pentru AI:

- `site_g/PHP/profesor_ai_chat.php`
- `site_g/PHP/documentation_context.php`
- `site_g/storage/documentation_index.json`
- `site_g/PHP/ai_quiz_api.php`

Pentru securitate:

- `site_g/index.php` pentru CSP și whitelist pagini;
- `site_g/PHP/helpers.php` pentru CSRF/rate limit;
- `site_g/PHP/login_post.php` și `register_post.php` pentru parole;
- `site_g/PHP/auth.php` pentru roluri și sesiune.

Pentru bază de date:

- `site_g/dbsortari.sql`
- `site_g/database/*.sql`
- `migrations/*.sql`

## 22. Răspunsuri scurte pentru întrebări probabile

### Cu ce tehnologii este făcut?

PHP 8.2, MySQL 8, HTML/CSS/JavaScript, Docker, phpMyAdmin, Mailpit și Groq API pentru AI.

### De ce ai folosit Docker?

Pentru ca aplicația, baza de date, phpMyAdmin și Mailpit să pornească izolat și reproductibil, fără configurare manuală în WAMP.

### Unde este routerul?

În `site_g/index.php`, prin array-ul `$pagini_permise`.

### Unde este conexiunea la baza de date?

În `site_g/PHP/conexiune.php`.

### Cum se face protecția CSRF?

Tokenul este generat în `helpers.php`, pus în meta/formulare și verificat în endpoint-uri prin POST sau header `X-CSRF-Token`.

### Cum funcționează AI-ul?

Întrebarea este trimisă la un endpoint PHP. Endpoint-ul caută fragmente relevante în `documentation_index.json`, construiește promptul și îl trimite la Groq. Dacă Groq nu răspunde sau limitează cererile, există fallback local.

### Unde sunt algoritmii vizualizați?

În `site_g/JS/visualizer.js`. Acolo sunt implementările pentru sortări și logica de animație pe canvas.

### Unde sunt întrebările grilă?

În baza de date, în tabela `grile_cpp`, inițializată din `site_g/dbsortari.sql` și ajustată prin migrări.

### Unde se salvează progresul?

În tabelele `learning_progress`, `learning_activity_history`, `learning_exercise_progress`, plus tabelele de quiz și achievements.

### Ce este Mailpit?

Mailpit este inbox local pentru emailuri de test. Resetarea parolei trimite emailul acolo, nu pe internet.

## 23. Rezumat foarte scurt pentru prezentare

> OffByOne Academy este o platformă educațională PHP/MySQL pentru învățarea algoritmilor C++. Are lecții structurate, vizualizări interactive pe canvas, exerciții de completare, grile, comparații de performanță și un Profesor AI conectat la documentația proiectului. Aplicația rulează în Docker cu servicii separate pentru web, MySQL, phpMyAdmin și Mailpit. Securitatea include CSRF, sesiuni, roluri, rate limit și parole hash-uite.

