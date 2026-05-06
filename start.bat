@echo off
REM ============================================================================
REM OffByOne Academy - Docker Startup Script (Windows)
REM ============================================================================
REM Automatically starts all Docker containers with validation & logging
REM Usage: start.bat [rebuild] [down] [logs]
REM ============================================================================

setlocal enabledelayedexpansion

REM Colors (using special characters)
set "SUCCESS=[OK]"
set "ERROR=[ERR]"
set "WARN=[WARN]"
set "INFO=[INFO]"

echo.
echo ============================================================
echo          OffByOne Academy - Docker Startup Script 2.0
echo ============================================================
echo.

REM ============================================================================
REM PHASE 1: PREREQUISITES CHECK
REM ============================================================================

echo %INFO% Checking prerequisites...

REM Check Docker installed
docker --version >nul 2>&1
if errorlevel 1 (
    echo %ERROR% Docker not found. Please install Docker Desktop
    exit /b 1
)
for /f "tokens=*" %%A in ('docker --version') do (
    echo %SUCCESS% Docker installed: %%A
)

REM Check Docker Compose
docker compose version >nul 2>&1
if errorlevel 1 (
    echo %ERROR% Docker Compose not found
    exit /b 1
)
echo %SUCCESS% Docker Compose installed

REM Check Docker daemon running
docker ps >nul 2>&1
if errorlevel 1 (
    echo %ERROR% Docker daemon not running. Please start Docker Desktop
    exit /b 1
)
echo %SUCCESS% Docker daemon is running

REM Check required files
if not exist "Dockerfile" (
    echo %ERROR% Dockerfile not found
    exit /b 1
)
if not exist "docker-compose.yml" (
    echo %ERROR% docker-compose.yml not found
    exit /b 1
)
if not exist ".dockerignore" (
    echo %ERROR% .dockerignore not found
    exit /b 1
)
if not exist "site_g" (
    echo %ERROR% site_g directory not found
    exit /b 1
)
echo %SUCCESS% All required files present

echo.

REM ============================================================================
REM PHASE 2: CONFIGURATION VALIDATION
REM ============================================================================

echo %INFO% Validating configuration...

docker compose config >nul 2>&1
if errorlevel 1 (
    echo %ERROR% docker-compose.yml syntax error
    exit /b 1
)
echo %SUCCESS% docker-compose.yml is valid

if not exist ".env" (
    echo %WARN% .env file not found, using defaults
    if exist ".env.example" (
        echo %INFO% Creating .env from .env.example...
        copy .env.example .env >nul
        echo %SUCCESS% .env created from template
    )
) else (
    echo %SUCCESS% .env file found
)

echo.

REM ============================================================================
REM PHASE 3: HANDLE FLAGS
REM ============================================================================

if "%1"=="down" (
    echo %INFO% Stopping and removing containers...
    docker compose down -v
    echo %SUCCESS% Cleanup complete
    exit /b 0
)

if "%1"=="logs" (
    docker compose logs -f web
    exit /b 0
)

REM ============================================================================
REM PHASE 4: BUILD & START
REM ============================================================================

echo %INFO% Starting Docker containers...

if "%1"=="rebuild" (
    echo %INFO% Rebuilding images (this may take a few minutes)...
    docker compose up --build -d
) else (
    docker compose up -d
)

echo %SUCCESS% Containers started

echo.

REM ============================================================================
REM PHASE 5: HEALTH CHECKS
REM ============================================================================

echo %INFO% Waiting for services to be healthy...

setlocal enabledelayedexpansion
set "max_attempts=30"
set "attempt=0"
set "db_ready=0"
set "web_ready=0"

:health_check_loop
if !attempt! geq !max_attempts! goto health_check_done

set /a attempt+=1

REM Check database
docker compose exec -T db mysqladmin ping -h 127.0.0.1 -uroot -proot123 >nul 2>&1
if errorlevel 0 (
    if "!db_ready!"=="0" (
        echo %SUCCESS% Database is healthy
        set "db_ready=1"
    )
)

REM Check web
curl -sf http://localhost:8082/index.php?page=bun_venit >nul 2>&1
if errorlevel 0 (
    if "!web_ready!"=="0" (
        echo %SUCCESS% Web application is healthy
        set "web_ready=1"
    )
)

if "!db_ready!"=="1" if "!web_ready!"=="1" (
    echo %SUCCESS% All services are healthy
    goto health_check_done
)

title OffByOne Academy Startup - Waiting for services... (!attempt!/!max_attempts!)
timeout /t 1 /nobreak >nul
goto health_check_loop

:health_check_done
title Command Prompt

echo.

REM ============================================================================
REM PHASE 6: DISPLAY STATUS & ACCESS INFO
REM ============================================================================

echo %INFO% Container status:
docker compose ps

echo.
echo %SUCCESS% OffByOne Academy is running!
echo.

echo ==== ACCESS URLS ====
echo.
echo   WEB APPLICATION:    http://localhost:8082
echo   phpMyAdmin:         http://localhost:8081
echo   MySQL Direct:       localhost:3308
echo.

echo ==== CREDENTIALS ====
echo.
echo   MySQL User:         root
echo   MySQL Password:     root123
echo   Database:           dbsortari
echo.

echo ==== USEFUL COMMANDS ====
echo.
echo   View logs:          docker compose logs -f web
echo   DB logs:            docker compose logs -f db
echo   Stop services:      docker compose down
echo   Stop and clean:     docker compose down -v
echo   Rebuild images:     docker compose up --build -d
echo.

echo %SUCCESS% Startup complete!
echo.

endlocal
