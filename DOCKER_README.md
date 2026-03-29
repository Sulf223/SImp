# Rulare cu Docker (pentru incepatori)

## 1) Cerinte
- Docker Desktop instalat
- Docker pornit

## 2) Pornire aplicatie
Din folderul proiectului (`SImp`):

```bash
docker compose up --build -d
```

## 3) Acces aplicatie
- Site: http://localhost:8080
- phpMyAdmin: http://localhost:8081
- MySQL: `localhost:3307`
  - user: `root`
  - parola: `root123`
  - baza: `dbsortari`

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
