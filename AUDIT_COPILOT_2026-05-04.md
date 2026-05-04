# Audit tehnic SImp

Data audit: 2026-05-04  
Auditor: GitHub Copilot (GPT-5.3-Codex)

## Scope si metoda
Am facut audit static/manual pe fluxurile critice din aplicatie (auth, CSRF, admin actions, reset parola, AI endpoints, progres, PWA/service worker), plus verificari automate de baza.

### Limitari de executie
- In mediul curent, comanda `php` nu este disponibila in PATH, deci nu am putut rula local lint/teste PHP.
- Verificarea a fost facuta prin inspectie de cod, corelare frontend-backend si cautare de pattern-uri riscante.

## Rezumat executiv
Am identificat **8 probleme** relevante:
- 3 critice (blocheaza functionalitati de baza sau produc comportament incorect major)
- 3 medii (inconsistenta, UX degradat, atac de resurse)
- 2 reduse (intretinere/robustete)

Top probleme:
1. Verificarea CSRF in `admin_actions.php` este folosita ca boolean, dar functia `verify_csrf()` nu returneaza boolean.
2. Inconsistenta de schema `max_streak` vs `longest_streak` in login.
3. `set_flash()` este apelata cu parametri inversati in mai multe fisiere.

---

## Findings (ordonate dupa severitate)

### CRITIC-1: Flux admin blocat de verificarea CSRF folosita gresit
- Fisiere:
  - `site_g/PHP/admin_actions.php:23`
  - `site_g/PHP/helpers.php:139`
- Observatie:
  - `admin_actions.php` foloseste `if (!verify_csrf())`.
  - `verify_csrf()` nu returneaza valoare; doar face `die(...)` la invalid.
  - La token valid, functia intoarce `null`, iar `!null === true`, deci conditia intra pe eroare.
- Impact:
  - Actiunile admin (`change_role`, `reset_progress`, `delete_user`) pot fi blocate chiar si cu CSRF valid.
- Recomandare:
  - Ori schimbi apelul la `verify_csrf();` fara `if`, ori modifici functia sa returneze strict `bool`.

### CRITIC-2: Inconsistenta schema/cod pentru streak (max_streak vs longest_streak)
- Fisiere:
  - `site_g/PHP/login_post.php:55`
  - `site_g/PHP/login_post.php:75`
  - `site_g/PHP/login_post.php:82`
  - `site_g/database/upgrade_profile_streak.sql:12`
  - `site_g/PHP/progres_learning.php:267`
- Observatie:
  - `login_post.php` foloseste coloana `max_streak`.
  - Schema si restul codului folosesc `longest_streak`.
- Impact:
  - Erori SQL pe login in baze actualizate, streak neactualizat sau comportament inconsistent.
- Recomandare:
  - Uniformizare completa pe `longest_streak` in toate query-urile.

### CRITIC-3: `set_flash()` apelat cu argumente inversate
- Fisiere:
  - `site_g/PHP/auth.php:44`
  - `site_g/PHP/grila_interactiva.php:215`
  - `site_g/PHP/metoda.php:7`
  - `site_g/PHP/metoda.php:23`
  - Definire: `site_g/PHP/helpers.php:76`
- Observatie:
  - Semnatura este `set_flash(type, message)`.
  - In aceste locuri apare `set_flash(message, "danger")`.
- Impact:
  - Mesaje neafisate corect (fallback pe `info`), feedback eronat pentru utilizator.
- Recomandare:
  - Inlocuire cu `set_flash('error', '...')` sau `set_flash('info', '...')` dupa caz.

### MEDIU-1: Link reset parola hardcodat pe `/SImp/site_g`
- Fisier:
  - `site_g/PHP/forgot_password_post.php:56`
- Observatie:
  - Link-ul de reset este construit cu path fix `/SImp/site_g/...`.
  - In README proiectul ruleaza si in Docker la root (`/`).
- Impact:
  - Linkuri de reset invalide in deployment-uri care nu folosesc exact acel subfolder.
- Recomandare:
  - Construieste URL-ul din `dirname($_SERVER['SCRIPT_NAME'])` sau variabila de configurare `APP_BASE_URL`.

### MEDIU-2: Endpoint AI quiz fara control explicit de metoda/rate-limit
- Fisier:
  - `site_g/PHP/ai_quiz_api.php:21`
- Observatie:
  - Endpointul decodeaza direct body JSON.
  - Nu exista verificare explicita `$_SERVER['REQUEST_METHOD'] === 'POST'`.
  - Nu exista rate limiting pe actiuni de generare/evaluare quiz.
- Impact:
  - Consum AI necontrolat, posibil abuz de resurse/cost.
- Recomandare:
  - Adauga check de metoda, autentificare (daca este ceruta de produs), si rate limit dedicat.

### MEDIU-3: PWA neactivata in practica (script de register nefolosit)
- Fisiere:
  - `site_g/JS/sw_register.js:1`
  - Cautare in `.php`: fara referinte la `sw_register.js`
- Observatie:
  - Exista cod de inregistrare service worker, dar nu este inclus in pagini.
- Impact:
  - Functionalitatile offline/PWA nu pornesc.
- Recomandare:
  - Include scriptul in layout principal (de preferat conditionat unde e necesar).

### REDUS-1: Service worker fara strategie de versionare/curatare cache vechi
- Fisier:
  - `site_g/sw.js:1`
  - `site_g/sw.js:13`
  - `site_g/sw.js:17`
- Observatie:
  - Exista `CACHE = 'simp-v1'`, dar fara handler `activate` pentru curatarea cache-urilor vechi.
- Impact:
  - Risc de servire asset-uri stale dupa release-uri repetate.
- Recomandare:
  - Adauga `activate` + cleanup (`caches.keys()` + delete selective) si eventual `skipWaiting()/clients.claim()` controlat.

### REDUS-2: Verificari automate neintegrate in mediul local
- Fisier:
  - `composer.json`
- Observatie:
  - Exista scripturi `test`/`stan`, dar in mediul actual nu s-au putut rula pentru ca `php` lipseste din PATH.
- Impact:
  - Defectele de regresie raman nedetectate mai mult timp.
- Recomandare:
  - Standardizeaza rularea prin Docker (task dedicat) sau documenteaza calea exacta catre binarul PHP pe Windows.

---

## Prioritate de remediere propusa
1. Fix imediat CRITIC-1, CRITIC-2, CRITIC-3.
2. Stabilizare flux reset parola (MEDIU-1).
3. Hardening AI quiz (MEDIU-2).
4. Activare corecta PWA (MEDIU-3) + igiena cache SW (REDUS-1).

## Estimare efort
- Quick fixes (1-2 ore): CRITIC-1, CRITIC-3, MEDIU-1.
- Refactor scurt (2-4 ore): CRITIC-2.
- Hardening + smoke test (2-4 ore): MEDIU-2, MEDIU-3, REDUS-1.

## Concluzie
Aplicatia are baza buna, dar contine cateva defecte de logica care afecteaza direct stabilitatea operatiilor admin si consistenta datelor de progres. Remedierea punctelor critice este directa si cu impact mare pozitiv asupra fiabilitatii.
