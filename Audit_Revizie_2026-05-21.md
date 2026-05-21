# Audit revizie - 2026-05-21

## Ce am verificat

- Sintaxă PHP pentru toate fișierele `.php` din `site_g`.
- Sintaxă JavaScript pentru toate fișierele din `site_g/JS`.
- Rute publice și rute cu redirect: lecții, laborator vizual, comparații, Profesor AI, login/logout, grile, profil/admin.
- Endpointuri sensibile fără autentificare: progres, grile, quiz AI, feedback AI, admin.
- Smoke test în Docker pentru paginile principale: bun venit, algoritmi, drumuri de învățare, laborator vizual, comparații, sortări, Profesor AI, recursivitate, backtracking și divide et impera.
- Test în browser pentru lecții de sortare, panoul de variabile, exerciții, laboratorul vizual și benchmark-ul de comparații.
- Schema bazei de date pentru tabele legacy și texte cu encoding greșit în grile/achievements.
- Fluxul de resetare parolă cu Mailpit.

## Probleme reparate

- `ai_status.php` încarcă acum configurația `.env` și marchează 429 ca stare degradată, nu offline generic.
- `conexiune.php` include `config.php` relativ la propriul director, ca să nu depindă de scriptul apelant.
- Register/login au feedback mai clar și tratează erorile de `prepare()` SQL.
- AI chat, AI quiz și feedback pe cod au fallback local pe documentația proiectului când Groq e limitat, indisponibil sau cheia lipsește.
- API-ul de quiz AI întoarce consecvent `ok: true` la răspunsurile valide, iar UI-ul tratează explicit erorile.
- Scorul la quiz-ul AI este calculat pe server din răspunsurile salvate în sesiune, nu din `isCorrect` trimis de browser.
- Încercările la grilele clasice sunt validate pe server din `grile_cpp.raspuns_corect`, nu din payload-ul clientului.
- `logout` este procesat înainte de layout, deci nu mai apare warning-ul de headere deja trimise.
- Formularul admin pentru metode nu mai apelează funcția inexistentă `valideazaMetoda()`.
- Handler-ele JavaScript inline (`onclick`, `onsubmit`, `onfocus`, `onmouseover` etc.) au fost eliminate; CSP blochează acum `script-src-attr` cu `none`.
- Panourile de variabile din lecțiile Selection/Insertion/Quick/Merge/Counting sunt sincronizate cu simulatorul.
- Pseudocodul Merge/Counting a fost aliniat cu liniile evidențiate în animație.
- Răspunsurile la exerciții acceptă și varianta cu `;` la final.
- Erorile afișate în feedback-ul AI pentru cod sunt escapate înainte de inserare în HTML.
- Ștergerea/resetarea unui utilizator curăță și `quiz_attempts`, `ai_quiz_attempts`, `user_achievements`, `password_reset_tokens`.
- `quiz_attempts` are acum chei străine către `utilizatori` și `grile_cpp`, cu `ON DELETE CASCADE`.
- Pagina veche `pagini/proiecte.php`, neindexată și cu referințe lipsă, a fost eliminată.
- Manifestul pornește din ruta corectă pentru Docker (`index.php?page=acasa`) și are `scope` local.
- Asseturile JS schimbate au versiuni noi în URL, ca browserul/service worker-ul să nu mai folosească scripturi vechi din cache.
- Texte vizibile fără diacritice au fost corectate în lecții, exerciții, laborator fundamental și ecranul de bun venit.
- Tabelele legacy `exercitii` și `rezultate` au fost convertite la InnoDB; migrarea a fost adăugată și în Docker.

## Verificări trecute

- `PHP lint OK: toate fișierele PHP din site_g`
- `JS syntax OK: toate fișierele din site_g/JS`
- `docker compose config --quiet` fără erori
- Paginile testate prin Docker au răspuns `200`; `logout` răspunde `302`; rutele protejate redirecționează/refuză corect.
- Browser: lecția Selection actualizează comparații/swap-uri/`minIdx`; exercițiul marchează corect răspunsul `j;`; laboratorul vizual avansează pseudocodul; comparațiile rulează benchmark și graficul este full-width.
- Mailpit primește emailul de resetare parolă pentru un cont de test.
- DB: `grile_cpp` și `achievements` fără texte `Ã/Ä/È`; `exercitii` și `rezultate` sunt InnoDB.
- Verificare CSP: nu mai există atribute HTML `on*`, `onclick`, `href="javascript:"` sau referințe `src="js/..."` cu path greșit.

## Observații rămase

- În `.env` există cheia Groq pentru demo. Nu este urmărită în Git, dar după prezentare ar trebui rotită.
- Credentialele Docker `root123` sunt potrivite doar local/demo, nu pentru producție.
- PHPUnit/PHPStan există în `composer.json`, dar `vendor`/Composer nu sunt instalate în mediul curent; am folosit lint, smoke tests și browser tests.
