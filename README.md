# OffByOne Academy

OffByOne Academy este o platformă educațională pentru învățarea algoritmilor C++: lecții, grile, laborator vizual, comparații de performanță, profil cu progres și Profesor AI.

Ghidul acesta explică pornirea proiectului, configurarea fișierului `.env` și setarea cheii Groq pentru partea de AI.

## Cerințe

Varianta recomandată:
1. Docker Desktop instalat
2. Docker Desktop pornit înainte de rulare

Variantă alternativă:
1. WAMP/XAMPP cu PHP și MySQL
2. Import manual pentru [site_g/dbsortari.sql](site_g/dbsortari.sql)
3. Rularea migrărilor din [site_g/database](site_g/database) și [migrations](migrations), în ordine

## Configurare `.env`

Fișierul `.env` trebuie să fie în rădăcina proiectului, lângă [docker-compose.yml](docker-compose.yml), nu în `site_g/`.

Exemplu minim:

```env
GROQ_API_KEY=cheia_ta_groq_aici
GROQ_MODEL=llama-3.3-70b-versatile
SITE_URL=http://localhost:8082
```

Pe Windows îl poți crea rapid așa:

```powershell
notepad .env
```

Apoi lipești valorile de mai sus și înlocuiești `cheia_ta_groq_aici` cu cheia ta reală.

Important:
1. Nu pune ghilimele în jurul cheii.
2. Nu urca `.env` în Git.
3. Dacă schimbi `.env`, repornește containerele.

## Cheie API Groq

Profesorul AI folosește Groq. Cheia se ia de aici:

1. Intră pe [groq.com](https://groq.com/) sau direct în [GroqCloud Console](https://console.groq.com/).
2. Creează cont sau autentifică-te.
3. Mergi la [API Keys](https://console.groq.com/keys).
4. Apasă `Create API Key`.
5. Copiază cheia și pune-o în `.env`:

```env
GROQ_API_KEY=gsk_...
```

Dacă cheia lipsește, este greșită sau Groq răspunde cu `429`, aplicația nu ar trebui să crape: Profesor AI intră în fallback local pe baza documentației din `proiect_documentatie`.

## Pornire cu Docker

Din rădăcina proiectului:

```bash
docker compose up --build -d
```

Adrese utile:
1. Site: [http://localhost:8082](http://localhost:8082)
2. phpMyAdmin: [http://localhost:8081](http://localhost:8081)
3. Mailpit: [http://localhost:8025](http://localhost:8025)
4. MySQL local: `localhost:3308`

Oprire:

```bash
docker compose down
```

Restart după modificarea `.env`:

```bash
docker compose down
docker compose up -d --build
```

Reset complet bază de date:

```bash
docker compose down -v
docker compose up --build -d
```

## Verificare rapidă

După pornire, verifică:

1. Se deschide [http://localhost:8082](http://localhost:8082).
2. Paginile Bubble, Selection, Insertion, Quick, Merge și Counting se încarcă.
3. `Laborator Vizual` pornește și butonul `Pas următor` avansează.
4. `Comparații` rulează benchmark-ul și afișează graficul.
5. `Profesor AI` răspunde sau afișează fallback local.
6. Resetarea parolei trimite email în Mailpit la [http://localhost:8025](http://localhost:8025).

## Troubleshooting

### Port ocupat

Porturi folosite:
1. `8082` pentru site
2. `8081` pentru phpMyAdmin
3. `8025` pentru Mailpit
4. `3308` pentru MySQL

Verificare în PowerShell:

```powershell
netstat -ano | findstr :8082
netstat -ano | findstr :8081
netstat -ano | findstr :8025
netstat -ano | findstr :3308
```

### AI nu răspunde

Verifică:
1. `.env` există în rădăcina proiectului.
2. `GROQ_API_KEY` este setat corect.
3. Docker a fost repornit după modificarea `.env`.
4. Nu ai depășit rate limit-ul Groq.

Comandă utilă:

```bash
docker compose logs -f web
```

### 404 pe `/OffByOneAcademy`

În Docker, aplicația este servită din rădăcină.

Corect:

```text
http://localhost:8082/
```

Greșit:

```text
http://localhost:8082/OffByOneAcademy
```

## Structură proiect

1. [site_g/index.php](site_g/index.php) - router și layout principal
2. [site_g/pagini](site_g/pagini) - pagini vizibile în aplicație
3. [site_g/PHP](site_g/PHP) - backend, endpoint-uri și helper-e
4. [site_g/JS](site_g/JS) - interacțiuni frontend și vizualizatoare
5. [site_g/CSS](site_g/CSS) - stiluri și temă dark/light
6. [site_g/dbsortari.sql](site_g/dbsortari.sql) - schema inițială
7. [migrations](migrations) - update-uri pentru baza de date
8. [docker-compose.yml](docker-compose.yml) - servicii Docker

## Comenzi utile

```bash
docker compose ps
docker compose logs -f web
docker compose logs -f db
docker compose exec web php -v
```

## Securitate

Proiectul include:
1. parole salvate cu `password_hash`
2. CSRF tokens pe formulare și endpoint-uri sensibile
3. rate limiting pentru login, resetare parolă și endpoint-uri AI
4. prepared statements pentru query-urile cu input
5. CSP și headere de securitate în [site_g/index.php](site_g/index.php)
6. `.env` ignorat de Git

## Commit messages

Folosește mesaje clare:

```text
feat: add visual lab controls
fix: validate quiz attempt server-side
docs: update Groq env setup
```

Evită mesaje de tip `update`, `fix stuff`, `final final`.

---

Proiect educațional pentru laborator/simpozion: OffByOne Academy.
