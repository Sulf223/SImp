@echo off
REM Simple Docker Start - With FULL OUTPUT
REM Pornire simplă - CU OUTPUT COMPLET

echo.
echo ========================================
echo   SIMP PORTAL - DOCKER STARTUP
echo ========================================
echo.

cd /d c:\wamp64\www\SImp
if errorlevel 1 (
    echo ERROR: Folderul nu gasit!
    pause
    exit /b 1
)

echo [1] Checking Docker...
docker ps >nul 2>&1
if errorlevel 1 (
    echo ERROR: Docker not running! Deschide Docker Desktop!
    pause
    exit /b 1
)
echo OK - Docker is running
echo.

echo [2] Creating .env file...
if not exist .env (
    copy .env.example .env >nul
    echo OK - .env created
) else (
    echo OK - .env exists
)
echo.

echo [3] Validating docker-compose.yml...
docker compose config >nul 2>&1
if errorlevel 1 (
    echo ERROR: Invalid docker-compose.yml!
    docker compose config
    pause
    exit /b 1
)
echo OK - Config is valid
echo.

echo [4] STARTING CONTAINERS (watch output below)...
echo.
echo ========================================
echo   LIVE DOCKER OUTPUT
echo ========================================
echo.

docker compose up

echo.
echo ========================================
echo   CONTAINERS STOPPED
echo ========================================
echo.
echo To restart: docker compose up
echo To stop: docker compose down
echo.
pause
