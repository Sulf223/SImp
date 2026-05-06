# 🐳 Docker Setup — OffByOne Academy v2.0

Documentație completă pentru rularea **OffByOne Academy** în Docker containers cu PHP 8.2, MySQL 8.0, și phpMyAdmin.

---

## 📋 Prerequisite

- ✅ **Docker Desktop** instalat ([download](https://www.docker.com/products/docker-desktop))
- ✅ **Docker și Docker Compose** active
- ✅ **Ports disponibili**: 8082 (web), 8081 (phpMyAdmin), 3308 (MySQL)

**Verificare**:
```bash
docker --version
docker compose version
```

---

## 🚀 Quick Start (3 comenzi)

```bash
# 1. Mergi în folderul proiectului
cd /path/to/OffByOneAcademy

# 2. Pornire containers
docker compose up --build -d

# 3. Urmărire logs (optional)
docker compose logs -f web
```

✅ **OffByOne Academy este live la**: http://localhost:8082

---

## 🌐 Access Points

| Service | URL | Credentials |
|---------|-----|-------------|
| **OffByOne Academy** | http://localhost:8082 | Crează cont pe pagina de register |
| **phpMyAdmin** | http://localhost:8081 | `root` / `root123` |
| **MySQL Direct** | `localhost:3308` | `root` / `root123` |

---

## 🔧 Configuration

### Variabile de Mediu (`.env`)

Crează fișier `.env` în root pentru a override valorile:

```env
# Profesor AI (optional)
GROQ_API_KEY=gsk_xxxxxxxxxxxxxxxxxxxx
GROQ_MODEL=llama-3.3-70b-versatile

# App
APP_ENV=production
APP_DEBUG=false
```

**Fără `.env`** — folderul va folosi defaults din docker-compose.yml.

### Modifica Credentialele MySQL

Editeaza `docker-compose.yml` și schimbă:
```yaml
environment:
  MYSQL_ROOT_PASSWORD: your_secure_password
```

⚠️ **IMPORTANT**: Pentru producție, folosește credențiale SIGURE și **nu** le comita în git!

### Aplicarea migrațiilor noi

Compose aplică automat scripturile din `docker-entrypoint-initdb.d` la prima inițializare a volumului MySQL. Pe lângă schema de bază, sunt aplicate migrațiile pentru progres, recursivitate/backtracking, profil/streak, rate limiting, drumuri de învățare, resetare parolă, audit log, achievements și `doc_link` pentru grile. Dacă ai deja `db_data` creat, aceste schimbări nu apar până când:

```bash
docker compose down -v
docker compose up --build -d
```

Sau rulezi manual migrarea necesară în containerul DB.

---

## 📊 Architecture

```
┌─────────────────────────────────────────────┐
│         Docker Compose Network              │
│       (offbyone_academy_network, bridge)               │
├─────────────────────────────────────────────┤
│                                             │
│  ┌──────────────┐  ┌──────────────┐        │
│  │   WEB        │  │   DB         │        │
│  │   (PHP 8.2)  │  │  (MySQL 8.0) │        │
│  │  Port 8082   │  │  Port 3308   │        │
│  └──────────────┘  └──────────────┘        │
│       │                    │                │
│  ┌────────────────────────────────┐         │
│  │      phpMyAdmin (5-apache)     │         │
│  │          Port 8081             │         │
│  └────────────────────────────────┘         │
│                                             │
└─────────────────────────────────────────────┘
```

---

## 📁 Volume Mapping

```
Host                          Container
═════════════════════════════════════════════
./site_g/                  → /var/www/html (ro)
./site_g/uploads/          → /var/www/html/uploads (rw)
./site_g/logs/             → /var/www/html/logs (rw)
./site_g/dbsortari.sql     → init script (MySQL)
./site_g/database/*.sql    → migrații init DB
./migrations/*.sql         → migrații proiect
db_data/ (Docker volume)    → /var/lib/mysql (persistent)
```

---

## ⚙️ Comenzi Utile

### Pornire / Oprire

```bash
# Pornire în background
docker compose up -d

# Pornire cu rebuild imagini
docker compose up --build -d

# Oprire containers (păstrează data)
docker compose down

# Oprire și ștergere date (clean reset)
docker compose down -v
```

### Monitoring

```bash
# Vezi status containers
docker compose ps

# Logs în real-time (web)
docker compose logs -f web

# Logs pentru DB
docker compose logs -f db

# Ultimi 50 linii (phpMyAdmin)
docker compose logs --tail=50 phpmyadmin

# Logs filtrate pentru erori
docker compose logs web 2>&1 | grep -i error
```

### Execuție Comenzi în Container

```bash
# Rulează PHP command
docker compose exec web php -v

# Merge în bash shell
docker compose exec web bash

# Rulează MySQL query
docker compose exec db mysql -uroot -proot123 dbsortari -e "SELECT VERSION();"

# Verific Apache modules
docker compose exec web apache2ctl -M
```

### Database Management

```bash
# Backup complet MySQL
docker compose exec db mysqldump -uroot -proot123 dbsortari > backup_dbsortari.sql

# Restore din backup
docker compose exec -T db mysql -uroot -proot123 dbsortari < backup_dbsortari.sql

# Acces MySQL shell direct
docker compose exec db mysql -uroot -proot123 dbsortari

# Resetează baza de date (ATENȚIE: șterge tot!)
docker compose down -v
docker compose up -d
```

### Debugging

```bash
# Health check status
docker compose exec web curl http://localhost/index.php?page=bun_venit

# Verific network
docker compose exec web ping db

# Verific database connection
docker compose exec web php -r "
  \$conn = new mysqli('db', 'root', 'root123', 'dbsortari');
  echo \$conn->connect_error ?: 'Connected!';
"

# View container resource usage
docker stats offbyone_academy_web offbyone_academy_db
```

---

## 🔐 Security Notes

### Development (Local)
Configurația curentă e OK pentru **local development** pe mașina ta.

### Production
⚠️ **NUNICIODATĂ** folosi:
- ❌ `root` ca user MySQL
- ❌ Parolele `root123` în fișiere tracked de git
- ❌ `DB_USER: root` în environment

**Production checklist**:
- ✅ Creează MySQL user specific cu permisiuni limitate
- ✅ Folosește secrets management (Docker Secrets, HashiCorp Vault)
- ✅ Enable HTTPS (reverse proxy cu Let's Encrypt)
- ✅ Set resource limits (see docker-compose.yml: `deploy.resources`)
- ✅ Use `.env.example` template, `.env` în `.gitignore`
- ✅ Regular database backups
- ✅ Monitor container health (see healthchecks)

---

## 🐛 Troubleshooting

### "Port 8082 already in use"
```bash
# Găsește ce proces folosește portul
lsof -i :8082

# Schimbă port în docker-compose.yml
ports:
  - "8083:80"  # Schimbare din 8082 → 8083
```

### "Connection refused" la MySQL
```bash
# Verifică dacă DB container e healthy
docker compose ps

# Vezi logs MySQL
docker compose logs db

# Rebuild DB
docker compose down -v
docker compose up -d db
docker compose up -d
```

### "File permissions denied" pe logs/uploads
```bash
# Fix permissions din container
docker compose exec web chown -R www-data:www-data /var/www/html/uploads
docker compose exec web chown -R www-data:www-data /var/www/html/logs
```

### "Database initialization fails"
```bash
# Verific SQL files
ls -la ./site_g/dbsortari.sql
ls -la ./site_g/database/upgrade_dashboard_progress.sql

# Rebuild fără cache
docker compose down -v
docker compose up --build -d
```

### Application slow / crashing
```bash
# Check resource limits
docker stats offbyone_academy_web offbyone_academy_db

# Increase memory în docker-compose.yml
deploy:
  resources:
    limits:
      memory: 2G
    reservations:
      memory: 1G

# Rebuild
docker compose down
docker compose up -d
```

---

## 📈 Performance Optimization

### Enabled (by default)
- ✅ Apache `mod_rewrite` — URL routing
- ✅ Apache `mod_headers` — CORS headers
- ✅ Apache `mod_cors` — CORS support
- ✅ PHP GD extension — image handling
- ✅ MySQL persistence — `db_data` volume

### Optional Enhancements
```yaml
# Add Redis cache (docker-compose.yml)
cache:
  image: redis:7-alpine
  ports:
    - "6379:6379"
  networks:
    - offbyone_academy_network
```

---

## 🔄 CI/CD Integration

### GitHub Actions Example
```yaml
name: Docker Build & Push

on: [push]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - uses: docker/setup-buildx-action@v2
      
      - uses: docker/build-push-action@v4
        with:
          context: .
          tags: offbyone-academy:latest
          push: true
```

---

## 📚 Resurse Externe

- [Docker Docs](https://docs.docker.com/)
- [Docker Compose Reference](https://docs.docker.com/compose/compose-file/)
- [MySQL Docker Image](https://hub.docker.com/_/mysql)
- [PHP Docker Image](https://hub.docker.com/_/php)
- [phpMyAdmin Docker](https://hub.docker.com/_/phpmyadmin)

---

## 📝 Version History

| Version | Date | Changes |
|---------|------|---------|
| 2.0 | Apr 27, 2026 | Modernized with health checks, resource limits, labels |
| 1.0 | Earlier | Initial Docker setup |

---

## 🆘 Getting Help

1. Check this README first
2. Review `docker compose logs` output
3. Verify firewall/antivirus allows ports 8082, 8081, 3308
4. Ensure `.env` file is properly configured (if needed)
5. Try `docker compose down -v && docker compose up --build -d`

---

**Last Updated**: May 6, 2026
**Maintained By**: OffByOne Academy Team
**License**: Same as main project

