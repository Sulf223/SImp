# Audit revizie - 2026-05-21

## Ce am verificat

- Sintaxă PHP pentru toate fișierele `.php` din `site_g`.
- Sintaxă JavaScript pentru toate fișierele din `site_g/JS`.
- Smoke test în Docker pentru paginile principale: bun venit, algoritmi, drumuri de învățare, laborator vizual, comparații, sortări, Profesor AI, recursivitate, backtracking și divide et impera.
- Test în browser pentru laboratorul vizual, butonul `Ajutor` din lecția Bubble Sort și încărcarea asseturilor cu versiuni noi.
- Schema bazei de date pentru tabele legacy și texte cu encoding greșit în grile/achievements.

## Probleme reparate

- `ai_status.php` încarcă acum configurația `.env` și marchează 429 ca stare degradată, nu offline generic.
- `conexiune.php` include `config.php` relativ la propriul director, ca să nu depindă de scriptul apelant.
- Register/login au feedback mai clar și tratează erorile de `prepare()` SQL.
- AI chat, AI quiz și feedback pe cod au fallback local pe documentația proiectului când Groq e limitat, indisponibil sau cheia lipsește.
- API-ul de quiz AI întoarce consecvent `ok: true` la răspunsurile valide, iar UI-ul tratează explicit erorile.
- Manifestul pornește din ruta corectă pentru Docker (`index.php?page=acasa`) și are `scope` local.
- Asseturile JS schimbate au versiuni noi în URL, ca browserul/service worker-ul să nu mai folosească scripturi vechi din cache.
- Texte vizibile fără diacritice au fost corectate în lecții, exerciții, laborator fundamental și ecranul de bun venit.
- Tabelele legacy `exercitii` și `rezultate` au fost convertite la InnoDB; migrarea a fost adăugată și în Docker.

## Verificări trecute

- `PHP lint OK: 61 files`
- `JS syntax OK: 12 files`
- `docker compose config --quiet` fără erori
- Paginile testate prin Docker au răspuns `200`; `profil` neautentificat redirecționează corect.
- Browser: fără erori console pe paginile verificate, fără mojibake vizibil în testele făcute.
- DB: `grile_cpp` și `achievements` fără texte `Ã/Ä/È`; `exercitii` și `rezultate` sunt InnoDB.

## Observații rămase

- În `.env` există cheia Groq pentru demo. Nu este urmărită în Git, dar după prezentare ar trebui rotită.
- Credentialele Docker `root123` sunt potrivite doar local/demo, nu pentru producție.
- PHPUnit/PHPStan există în `composer.json`, dar `vendor`/Composer nu sunt instalate în mediul curent; am folosit lint, smoke tests și browser tests.
