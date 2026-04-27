# 🚀 Quick Start Guide — SImp Portal

Choose your platform below to get started in seconds:

---

## 🪟 **Windows Users**

### Option 1: PowerShell (Recommended)
```powershell
# Open PowerShell in the project folder, then run:
.\start.ps1

# Or with rebuild:
.\start.ps1 -Rebuild

# Or to stop services:
.\start.ps1 -Down

# Or to view logs:
.\start.ps1 -Logs
```

### Option 2: Command Prompt (CMD)
```batch
REM Open CMD in the project folder, then run:
start.bat

REM Or with rebuild:
start.bat rebuild

REM Or to stop services:
start.bat down

REM Or to view logs:
start.bat logs
```

---

## 🐧 **Linux & macOS Users**

### Bash
```bash
# Open terminal in the project folder, then run:
bash start.sh

# Or with rebuild:
bash start.sh --rebuild

# Or to stop services:
bash start.sh --down

# Or to view logs:
bash start.sh --logs
```

---

## 🎯 What the Script Does

The startup script automatically:

1. ✅ **Checks Prerequisites**
   - Verifies Docker & Docker Compose installed
   - Confirms Docker daemon is running
   - Validates all required files present

2. ✅ **Validates Configuration**
   - Checks docker-compose.yml syntax
   - Creates .env from template if missing

3. ✅ **Port Availability**
   - Tests ports 8082, 8081, 3308
   - Warns if ports are in use

4. ✅ **Starts Containers**
   - Pulls/builds images
   - Starts web, db, phpmyadmin services
   - Applies resource limits

5. ✅ **Health Checks**
   - Waits for database to be ready
   - Waits for web app to respond
   - Reports all services healthy

6. ✅ **Shows Access Info**
   - Displays container status
   - Lists access URLs
   - Shows credentials
   - Provides useful commands

---

## 📊 Access URLs (After Startup)

| Service | URL |
|---------|-----|
| **SImp Portal** | http://localhost:8082 |
| **phpMyAdmin** | http://localhost:8081 |
| **MySQL** | localhost:3308 |

**Credentials**:
- User: `root`
- Password: `root123`
- Database: `dbsortari`

---

## ⚡ Quick Commands

```bash
# View logs (all services)
docker compose logs -f

# View specific service logs
docker compose logs -f web    # Web app
docker compose logs -f db     # Database

# Stop services (keep data)
docker compose down

# Stop & delete everything
docker compose down -v

# Rebuild images
docker compose up --build -d

# Check container status
docker compose ps

# Execute command in container
docker compose exec web bash
docker compose exec db mysql -uroot -proot123
```

---

## 🆘 Troubleshooting

### "Port already in use"
```bash
# Find what's using the port
lsof -i :8082

# Or change port in docker-compose.yml:
ports:
  - "8083:80"  # Change 8082 to 8083
```

### "Docker daemon not running"
- Start Docker Desktop
- Wait 10-20 seconds for it to fully start
- Run startup script again

### "Permission denied" (Linux/Mac)
```bash
# Make script executable
chmod +x start.sh

# Then run
bash start.sh
```

### "Cannot connect to database"
```bash
# Wait a bit longer and retry
docker compose logs db

# Or reset database
docker compose down -v
docker compose up -d
```

---

## 📖 Full Documentation

For complete documentation, see:
- **DOCKER_README.md** — Full Docker guide
- **DOCKER_MODERNIZATION.md** — What's new in v2.0

---

## 🎉 You're All Set!

Your SImp Portal is now running. Start by:

1. Opening http://localhost:8082
2. Creating a new account on the register page
3. Logging in
4. Exploring the sorting algorithms!

**Questions?** Check DOCKER_README.md for detailed troubleshooting.

---

**Last Updated**: April 27, 2026  
**Version**: 2.0
