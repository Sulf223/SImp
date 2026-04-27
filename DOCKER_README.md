# Rulare cu Docker (pentru incepatori)

## 1) Cerinte
- Docker Desktop instalat
- Docker pornit

## 2) Pornire aplicatie
Din folderul proiectului (`SImp`):

```bash
docker compose up --build -d
```

Optional, in fisierul `.env` din radacina poti seta:

```env
GROQ_API_KEY=cheia_ta
GROQ_MODEL=llama-3.3-70b-versatile
```

## 3) Acces aplicatie
- Site: http://localhost:8082
- phpMyAdmin: http://localhost:8081
- MySQL: `localhost:3308`
  - user: `root`
  - parola: `root123`
  - baza: `dbsortari`

> Nota securitate: credentialele de mai sus sunt strict pentru mediu local de dezvoltare/demo.
> Nu folosi aceste valori in productie si nu publica parole reale in repository.

### Login phpMyAdmin
- Server: `db`
- Username: `root`
- Password: `root123`

## 4) Oprire
```bash
docker compose down
```

## 5) Reset complet baza de date
Atentie: sterge datele din container.

```bash
docker compose down -v
docker compose up --build -d
```

## 6) Comenzi utile
Vezi logurile:

```bash
docker compose logs -f web
docker compose logs -f db
docker compose logs -f phpmyadmin
```

Rulezi comenzi PHP in container:

```bash
docker compose exec web php -v
```
