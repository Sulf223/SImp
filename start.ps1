# ============================================================================
# SImp Portal — Docker Startup Script (PowerShell)
# ============================================================================
# Automatically starts all Docker containers with validation & logging
# Usage: .\start.ps1 [-Rebuild] [-Down] [-Logs]
# ============================================================================

param(
    [switch]$Rebuild,
    [switch]$Down,
    [switch]$Logs
)

# Set error action preference
$ErrorActionPreference = "Stop"

# Color functions
function Write-Success {
    param([string]$Message)
    Write-Host "✓ $Message" -ForegroundColor Green
}

function Write-Error {
    param([string]$Message)
    Write-Host "✗ $Message" -ForegroundColor Red
}

function Write-Warn {
    param([string]$Message)
    Write-Host "⚠ $Message" -ForegroundColor Yellow
}

function Write-Info {
    param([string]$Message)
    Write-Host "ℹ $Message" -ForegroundColor Cyan
}

# ============================================================================
# PHASE 1: PREREQUISITES CHECK
# ============================================================================

function Test-Prerequisites {
    Write-Info "Checking prerequisites..."
    
    # Check Docker installed
    try {
        $dockerVersion = docker --version
        Write-Success "Docker installed: $dockerVersion"
    } catch {
        Write-Error "Docker not found. Please install Docker Desktop"
        exit 1
    }
    
    # Check Docker Compose
    try {
        $composeVersion = docker compose version
        Write-Success "Docker Compose installed: $($composeVersion | Select-Object -First 1)"
    } catch {
        Write-Error "Docker Compose not found"
        exit 1
    }
    
    # Check Docker daemon running
    try {
        docker ps > $null
        Write-Success "Docker daemon is running"
    } catch {
        Write-Error "Docker daemon not running. Please start Docker Desktop"
        exit 1
    }
    
    # Check required files
    $requiredFiles = @("Dockerfile", "docker-compose.yml", ".dockerignore", "site_g")
    foreach ($file in $requiredFiles) {
        if (-not (Test-Path $file)) {
            Write-Error "Required file/directory not found: $file"
            exit 1
        }
    }
    Write-Success "All required files present"
    Write-Host ""
}

# ============================================================================
# PHASE 2: CONFIGURATION VALIDATION
# ============================================================================

function Test-Configuration {
    Write-Info "Validating configuration..."
    
    # Validate docker-compose.yml syntax
    try {
        docker compose config > $null 2>&1
        Write-Success "docker-compose.yml is valid"
    } catch {
        Write-Error "docker-compose.yml syntax error"
        exit 1
    }
    
    # Check for .env file
    if (-not (Test-Path ".env")) {
        Write-Warn ".env file not found, using defaults"
        if (Test-Path ".env.example") {
            Write-Info "Creating .env from .env.example..."
            Copy-Item ".env.example" -Destination ".env" -Force
            Write-Success ".env created from template"
        }
    } else {
        Write-Success ".env file found"
    }
    
    Write-Host ""
}

# ============================================================================
# PHASE 3: PORT AVAILABILITY
# ============================================================================

function Test-Ports {
    Write-Info "Checking port availability..."
    
    $ports = @(8082, 8081, 3308)
    $inUse = 0
    
    foreach ($port in $ports) {
        try {
            $result = Get-NetTCPConnection -LocalPort $port -State Listen -ErrorAction SilentlyContinue
            if ($result) {
                Write-Warn "Port $port is already in use"
                $inUse++
            } else {
                Write-Success "Port $port is available"
            }
        } catch {
            Write-Success "Port $port is available"
        }
    }
    
    if ($inUse -gt 0) {
        Write-Warn "$inUse port(s) already in use - containers may fail to start"
        $response = Read-Host "Continue anyway? (y/n)"
        if ($response -ne "y" -and $response -ne "Y") {
            Write-Info "Startup cancelled"
            exit 0
        }
    }
    
    Write-Host ""
}

# ============================================================================
# PHASE 4: CLEANUP (if -Down flag)
# ============================================================================

function Stop-Services {
    if ($Down) {
        Write-Info "Stopping and removing containers..."
        docker compose down -v
        Write-Success "Cleanup complete"
        exit 0
    }
}

# ============================================================================
# PHASE 5: BUILD & START
# ============================================================================

function Start-Services {
    Write-Info "Starting Docker containers..."
    
    if ($Rebuild) {
        Write-Info "Rebuilding images (this may take a few minutes)..."
        docker compose up --build -d
    } else {
        docker compose up -d
    }
    
    Write-Success "Containers started"
    Write-Host ""
}

# ============================================================================
# PHASE 6: HEALTH CHECKS
# ============================================================================

function Test-HealthChecks {
    Write-Info "Waiting for services to be healthy..."
    
    $maxAttempts = 30
    $attempt = 0
    $dbReady = $false
    $webReady = $false
    
    while ($attempt -lt $maxAttempts) {
        $attempt++
        
        # Check database
        try {
            $null = docker compose exec -T db mysqladmin ping -h 127.0.0.1 -uroot -proot123 2>$null
            if (-not $dbReady) {
                Write-Success "Database is healthy"
                $dbReady = $true
            }
        } catch { }
        
        # Check web
        try {
            $response = Invoke-WebRequest -Uri "http://localhost:8082/index.php?page=bun_venit" -TimeoutSec 1 -UseBasicParsing
            if ($response.StatusCode -eq 200) {
                if (-not $webReady) {
                    Write-Success "Web application is healthy"
                    $webReady = $true
                }
            }
        } catch { }
        
        if ($dbReady -and $webReady) {
            Write-Success "All services are healthy"
            Write-Host ""
            return
        }
        
        Write-Host -NoNewline "`rWaiting for services... ($attempt/$maxAttempts)"
        Start-Sleep -Milliseconds 1000
    }
    
    Write-Host ""
    Write-Warn "Health check timeout - services may still be initializing"
    Write-Host ""
}

# ============================================================================
# PHASE 7: DISPLAY STATUS & ACCESS INFO
# ============================================================================

function Show-Info {
    Write-Info "Container status:"
    docker compose ps
    
    Write-Host ""
    Write-Success "SImp Portal is running!"
    Write-Host ""
    
    Write-Host "📌 Access URLs:"
    Write-Host "   🌐 SImp Portal:      http://localhost:8082" -ForegroundColor Yellow
    Write-Host "   📊 phpMyAdmin:       http://localhost:8081" -ForegroundColor Yellow
    Write-Host "   💾 MySQL Direct:     localhost:3308" -ForegroundColor Yellow
    Write-Host ""
    
    Write-Host "🔐 Credentials:"
    Write-Host "   MySQL User:          root"
    Write-Host "   MySQL Password:      root123"
    Write-Host "   Database:            dbsortari"
    Write-Host ""
    
    Write-Host "📖 Useful commands:"
    Write-Host "   View logs:           docker compose logs -f web" -ForegroundColor Cyan
    Write-Host "   DB logs:             docker compose logs -f db" -ForegroundColor Cyan
    Write-Host "   Stop services:       docker compose down" -ForegroundColor Cyan
    Write-Host "   Stop & clean:        docker compose down -v" -ForegroundColor Cyan
    Write-Host "   Rebuild images:      docker compose up --build -d" -ForegroundColor Cyan
    Write-Host ""
}

# ============================================================================
# MAIN EXECUTION
# ============================================================================

function Main {
    Write-Host ""
    Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Green
    Write-Host "║        🚀 SImp Portal — Docker Startup Script 2.0         ║" -ForegroundColor Green
    Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Green
    Write-Host ""
    
    # Handle logs flag
    if ($Logs) {
        docker compose logs -f web
        return
    }
    
    # Execute phases
    Test-Prerequisites
    Test-Configuration
    Test-Ports
    Stop-Services
    Start-Services
    Test-HealthChecks
    Show-Info
    
    Write-Success "Startup complete!"
    Write-Host ""
}

# Run main function
try {
    Main
} catch {
    Write-Error $_.Exception.Message
    exit 1
}
