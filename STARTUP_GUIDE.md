# 🚀 SImp Portal — Quick Start Guide

Complete guide to launching SImp Portal using automated startup scripts.

---

## 📋 Prerequisites

- ✅ Docker Desktop installed ([download](https://www.docker.com/products/docker-desktop))
- ✅ Docker & Docker Compose running
- ✅ Ports available: 8082, 8081, 3308
- ✅ Terminal/Command Prompt access

---

## 🎯 Quick Start (Choose Your OS)

### 🐧 Linux / 🍎 macOS

```bash
# Navigate to project directory
cd /path/to/SImp

# Make script executable
chmod +x start.sh

# Run startup script
./start.sh
```

**Output**:
```
✓ Docker found: Docker version 24.0.0...
✓ Docker daemon is running
✓ .env file found
✓ Services started successfully
ℹ Waiting for services to be healthy...
✓ Web service is healthy
✓ Database service is healthy
✓ phpMyAdmin is healthy

📍 Access Points:
  🌐 SImp Portal:    http://localhost:8082
  📊 phpMyAdmin:     http://localhost:8081
  🗄️  MySQL:          localhost:3308
```

### 🪟 Windows (Batch)

Simply **double-click** `start.bat` or run:

```cmd
start.bat
```

**Features**:
- ✅ Automatic prerequisite checks
- ✅ Auto-creates `.env` from `.env.example`
- ✅ Waits for services to be ready
- ✅ Displays access information
- ✅ Shows credentials

### 🪟 Windows (PowerShell)

```powershell
# Allow script execution (first time only)
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# Run script
.\start.ps1

# Or with specific command
.\start.ps1 -Command logs
```

**Commands**:
```powershell
.\start.ps1 -Command start      # Start services
.\start.ps1 -Command stop       # Stop services
.\start.ps1 -Command restart    # Restart services
.\start.ps1 -Command logs       # View logs
.\start.ps1 -Command status     # Show status
.\start.ps1 -Command reset      # Full reset
```

### 🔧 Unix/Linux with Make

```bash
# View all available commands
make help

# Start services
make start

# View logs
make logs

# Check health
make test-health

# Backup database
make backup

# Restore database
make restore FILE=backup_20260427_120000.sql
```

---

## 🌐 Access Your Services

After startup completes, open your browser:

| Service | URL | Purpose |
|---------|-----|---------|
| **SImp Portal** | http://localhost:8082 | Learning platform |
| **phpMyAdmin** | http://localhost:8081 | Database management |
| **MySQL** | localhost:3308 | Direct connection |

### Credentials

```
Username: root
Password: root123
Database: dbsortari
```

---

## ⚙️ Configuration

### Environment Variables (`.env`)

The startup script auto-creates `.env` from `.env.example` if missing.

To customize:

```env
# Optional: Add Groq AI API key
GROQ_API_KEY=gsk_your_key_here

# Optional: Set AI model
GROQ_MODEL=llama-3.3-70b-versatile

# App environment
APP_ENV=production
APP_DEBUG=false
```

**⚠️ IMPORTANT**: `.env` is in `.gitignore` — never commit actual credentials!

---

## 📊 Status & Monitoring

### Quick Status Check

```bash
# Show all containers
docker compose ps

# View live logs (web)
docker compose logs -f web

# View live logs (database)
docker compose logs -f db

# View live logs (phpMyAdmin)
docker compose logs -f phpmyadmin

# Follow all logs
docker compose logs -f
```

### Health Checks

All services have automated health checks. Check status:

```bash
docker compose ps

# Output:
# NAME           STATUS              PORTS
# simp_web       Up (healthy)        0.0.0.0:8082->80/tcp
# simp_db        Up (healthy)        0.0.0.0:3308->3306/tcp
# simp_phpmyadmin Up (healthy)        0.0.0.0:8081->80/tcp
```

---

## 🛑 Stopping & Resetting

### Stop Services (Keep Data)

```bash
# Using bash/Make
docker compose down
# or
make stop

# Using PowerShell
.\start.ps1 -Command stop

# Using batch
# (Press Ctrl+C or manually stop)
```

### Full Reset (Delete Everything)

⚠️ **WARNING**: This deletes all database data!

```bash
# Using bash/Make
docker compose down -v
# or
make reset

# Using PowerShell
.\start.ps1 -Command reset

# Using docker directly
docker compose down -v
docker compose up --build -d
```

---

## 🐛 Troubleshooting

### Services Won't Start

**Check prerequisites**:
```bash
docker --version
docker compose version
docker ps
```

**Check logs**:
```bash
docker compose logs web
docker compose logs db
```

### Port Already in Use

```bash
# Find what's using the port
lsof -i :8082  # macOS/Linux
Get-NetTCPConnection -LocalPort 8082  # Windows PowerShell

# Either:
# 1. Stop the other service
# 2. Change port in docker-compose.yml:
#    ports:
#      - "8083:80"  # Changed from 8082
```

### Cannot Connect to Database

```bash
# Verify DB is healthy
docker compose exec db mysqladmin ping -h 127.0.0.1 -uroot -proot123

# Check DB logs
docker compose logs db

# For production: change default credentials in docker-compose.yml
```

### Slow Performance

```bash
# Check resource usage
docker stats

# Increase memory limits in docker-compose.yml:
#   deploy:
#     resources:
#       limits:
#         memory: 2G
```

### "Permission Denied" on Linux

```bash
# Make scripts executable
chmod +x start.sh Makefile

# Or run with bash
bash start.sh
```

---

## 🔐 Security Notes

### ✅ Development (Local)

Current config is **OK for local development**:
```env
DB_USER=root
DB_PASS=root123
APP_DEBUG=false
```

### ⚠️ Production (Before Deploying)

**MUST DO**:
1. ✅ Change MySQL password
2. ✅ Create non-root DB user
3. ✅ Set `APP_ENV=production`
4. ✅ Set `APP_DEBUG=false`
5. ✅ Enable HTTPS (reverse proxy)
6. ✅ Use Docker Secrets for credentials
7. ✅ Regular backups
8. ✅ Security scanning

See [DOCKER_README.md](./DOCKER_README.md) for production checklist.

---

## 💾 Database Management

### Backup Database

```bash
# Quick backup
docker compose exec -T db mysqldump -uroot -proot123 dbsortari > backup.sql

# Using Make
make backup
```

### Restore Database

```bash
# Quick restore
docker compose exec -T db mysql -uroot -proot123 dbsortari < backup.sql

# Using Make
make restore
```

### Access MySQL Shell

```bash
# Direct access
docker compose exec db mysql -uroot -proot123 dbsortari

# Using Make
make db-shell
```

---

## 🧪 Testing & Verification

### Run Health Checks

```bash
# Test all services
docker compose exec -T web curl http://localhost/index.php?page=bun_venit
docker compose exec -T db mysqladmin ping -h 127.0.0.1 -uroot -proot123
docker compose exec -T phpmyadmin curl http://localhost/

# Or using Make (Unix)
make test-health
```

### View Container Stats

```bash
# Real-time resource monitoring
docker stats
```

---

## 🔄 Advanced Commands

### Rebuild Image

```bash
docker compose build --no-cache
docker compose up -d
```

### Execute Commands in Container

```bash
# PHP command
docker compose exec web php -v

# Bash shell
docker compose exec web bash

# Run specific PHP file
docker compose exec web php /var/www/html/script.php
```

### View All Logs

```bash
# Last 50 lines
docker compose logs --tail=50

# Follow all services
docker compose logs -f

# Only errors
docker compose logs | grep -i error
```

---

## 📚 More Information

- [DOCKER_README.md](./DOCKER_README.md) — Detailed Docker documentation
- [COMPLETION_SUMMARY.md](./COMPLETION_SUMMARY.md) — CSS redesign summary
- [docker-compose.yml](./docker-compose.yml) — Service configuration
- [Dockerfile](./Dockerfile) — Image build instructions
- [.env.example](./.env.example) — Environment template

---

## 🆘 Getting Help

1. **Check logs** — `docker compose logs web`
2. **Review README** — [DOCKER_README.md](./DOCKER_README.md)
3. **Verify Docker** — `docker version && docker compose version`
4. **Try reset** — `docker compose down -v && docker compose up --build -d`

---

## ✨ What's Included

- ✅ Bash startup script (Linux/macOS)
- ✅ Batch startup script (Windows)
- ✅ PowerShell startup script (Windows)
- ✅ Makefile (Unix/Make)
- ✅ Auto-created `.env` file
- ✅ Health checks on all services
- ✅ Resource limits configured
- ✅ Automatic `.env` generation
- ✅ Credential display on startup
- ✅ Comprehensive error handling

---

**Version**: 2.0  
**Last Updated**: April 27, 2026  
**Status**: ✅ Production-Ready
