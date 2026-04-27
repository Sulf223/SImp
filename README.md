# SImp - Portal educational pentru algoritmi de sortare

SImp este un mini-laborator web pentru invatarea algoritmilor, construit pentru prezentare academica (simpozion/proiect de curs).
Aplicatia combina explicatii teoretice, exemple C++, exercitii interactive si componente vizuale pentru invatare pas cu pas.

## De ce acest subiect

Am ales tema algoritmilor de sortare pentru ca:
- este fundamentala in programare si apare in multe discipline;
- permite comparatii clare intre complexitati diferite;
- se preteaza foarte bine la demonstratii vizuale memorabile.

## Ce face proiectul

- prezinta algoritmi de sortare (Bubble, Selection, Insertion, Quick, Merge, Counting);
- include pagini dedicate pentru algoritmi fundamentali (recursivitate, backtracking, greedy, divide et impera);
- ofera vizualizari interactive pas cu pas (Canvas/JS);
- include exercitii, grile si urmarire progres in baza de date;
- include un compilator C++ online si widget de asistenta AI pentru explicatii.

## Tehnologii folosite

- Frontend: HTML, CSS, JavaScript (Canvas)
- Backend: PHP
- Baza de date: MySQL (phpMyAdmin pentru administrare)
- Mediu local: WAMP
- Containerizare: Docker + Docker Compose
- Exemple de cod: C++

## Structura principala

- `site_g/` - aplicatia web principala
- `site_g/pagini/` - pagini tematice
- `site_g/JS/` - logica interactiva si vizualizari
- `site_g/PHP/` - backend, autentificare, API-uri
- `site_g/database/` - scripturi SQL auxiliare
- `site_g/CPP/` - implementari C++
- `DOCKER_README.md` - instructiuni de rulare in containere

## Rulare rapida (Docker)

1. Din radacina proiectului, ruleaza:
   - `docker compose up --build -d`
2. Acceseaza:
   - aplicatie: `http://localhost:8082`
   - phpMyAdmin: `http://localhost:8081`
3. Oprire:
   - `docker compose down`

Detalii complete: vezi `DOCKER_README.md`.

## Functionalitati noi adaugate pentru prezentare

- pagina de comparatii de performanta intre algoritmi (`index.php?page=comparatii_sortare`);
- benchmark pe seturi de date diferite: random, deja sortat, invers sortat;
- grafic comparativ cu timpi de executie si tabel detaliat.

## Securitate (nota pentru demo)

Credentialele Docker din documentatie sunt setate intentionat pentru mediu local de test.
Nu trebuie reutilizate in productie.

## Conventie recomandata pentru commit messages

Foloseste mesaje descriptive, de tipul:
- `feat: add sorting performance comparison page`
- `docs: clarify local-only docker credentials`
- `fix: correct quick sort partition visualization`

Evita mesaje vagi precum `update`, `fix`, `changes`.

## Licenta

Proiect educational.
