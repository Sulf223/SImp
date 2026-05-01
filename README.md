# SImp - ghid de rulare pentru echipa

SImp este un portal educational pentru algoritmi (sortare + algoritmi fundamentali), cu pagini teoretice, vizualizari interactive, exercitii si Profesor AI.

Acest README este gandit pentru colegii de proiect, ca sa poata porni rapid aplicatia pe orice laptop.

## 1. Ce aveti nevoie

Varianta recomandata (Docker):
1. Docker Desktop instalat
2. Docker pornit

Varianta alternativa (fara Docker):
1. WAMP/XAMPP cu PHP + MySQL
2. Import manual pentru baza de date din [site_g/dbsortari.sql](site_g/dbsortari.sql)

## 2. Clonare proiect

```bash
git clone <url-repo>
cd SImp
```

## 3. Configurare variabile de mediu

In radacina proiectului exista fisierul [.env](.env). Pentru Profesor AI (Groq), asigurati-va ca are:

```env
GROQ_API_KEY=cheia_ta_groq
GROQ_MODEL=llama-3.3-70b-versatile
```

Observatii:
1. Fisierul .env este local si nu trebuie urcat in Git.
2. Daca nu setati GROQ_API_KEY, chat-ul Profesor AI va da eroare.

## 4. Pornire rapida cu Docker (recomandat)

Din radacina proiectului:

```bash
docker compose up --build -d
```

Acces aplicatie:
1. Site: http://localhost:8082
2. phpMyAdmin: http://localhost:8081
3. MySQL host: localhost:3308

Oprire:

```bash
docker compose down
```

Reset complet DB (sterge volume):

```bash
docker compose down -v
docker compose up --build -d
```

## 5. Ce verificati dupa start (checklist de test)

1. Se deschide homepage-ul la http://localhost:8082
2. Se deschid paginile de sortare (Bubble, Selection, Insertion, Quick, Merge, Counting)
3. Functioneaza pagina Comparatii: [site_g/pagini/comparatii_sortare.php](site_g/pagini/comparatii_sortare.php)
4. Functioneaza vizualizatorul din [site_g/JS/visualizer.js](site_g/JS/visualizer.js)
5. Profesor AI raspunde fara eroare model/API
6. phpMyAdmin se deschide la http://localhost:8081

## 6. Troubleshooting rapid

### Port ocupat

Problema: docker nu porneste pentru un port deja folosit.

Porturi folosite de proiect:
1. 8082 (site)
2. 8081 (phpMyAdmin)
3. 3308 (MySQL)

Verificare in PowerShell:

```powershell
netstat -ano | findstr :8082
netstat -ano | findstr :8081
netstat -ano | findstr :3308
```

### 404 pe /SImp

In Docker, aplicatia este servita din radacina (/), nu din subfolder.

Corect: http://localhost:8082/
Gresit: http://localhost:8082/SImp

### Profesor AI nu merge

Verificati:
1. GROQ_API_KEY setat in [.env](.env)
2. Containere repornite dupa modificarea .env:

```bash
docker compose down
docker compose up -d --build
```

## 7. Securitate și Hardening

Proiectul include măsuri de securitate P0 și P1:
1. **Configurație securizată:** Fișierul `.env` este mutat în rădăcina proiectului, în afara webroot-ului, și protejat prin `.htaccess`.
2. **Protecție CSRF:** Toate endpoint-urile critice (AI Chat, Progres Learning) verifică token-ul CSRF.
3. **Rate Limiting:** Implementat în baza de date (tabela `rate_limit_attempts`) pentru a preveni brute-force-ul, chiar și la ștergerea cookie-urilor.
4. **Validare riguroasă:** Parolele la înregistrare necesită minim 8 caractere (litere + cifre).
5. **Headere de securitate:** CSP, X-Content-Type-Options, X-Frame-Options și Referrer-Policy sunt configurate în `index.php`.
6. **Sandbox Iframe:** Editorul OneCompiler rulează într-un mediu sandbox controlat.

## 8. Structura proiect (pe scurt)

1. [site_g/index.php](site_g/index.php) - intrare principala
2. [site_g/pagini](site_g/pagini) - pagini de continut
3. [site_g/JS](site_g/JS) - logica frontend si vizualizari
4. [site_g/PHP](site_g/PHP) - endpoint-uri si backend
5. [docker-compose.yml](docker-compose.yml) - orchestrare servicii
6. [DOCKER_README.md](DOCKER_README.md) - ghid Docker extins

## 8. Comenzi utile pentru debugging

```bash
docker compose ps
docker compose logs -f web
docker compose logs -f db
docker compose exec web php -v
```

## 9. Conventie commit messages

Folositi mesaje descriptive:
1. feat: add benchmark live mode
2. fix: repair AI endpoint Groq auth header
3. docs: update setup steps for teammates

Evitati mesaje vagi de tip update/fix stuff.

---

Proiect educational pentru laborator/simpozion.
