#!/bin/bash
# ============================================================================
# OffByOne Academy — Docker Startup Script
# ============================================================================
# Automatically starts all Docker containers with validation & logging
# Usage: bash start.sh [--rebuild] [--down] [--logs]
# ============================================================================

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging functions
log() {
    echo -e "${BLUE}ℹ ${NC}$1"
}

success() {
    echo -e "${GREEN}✓ ${NC}$1"
}

error() {
    echo -e "${RED}✗ ${NC}$1"
}

warn() {
    echo -e "${YELLOW}⚠ ${NC}$1"
}

# ============================================================================
# PHASE 1: PREREQUISITES CHECK
# ============================================================================

phase_prerequisites() {
    log "Checking prerequisites..."
    
    # Check Docker installed
    if ! command -v docker &> /dev/null; then
        error "Docker not found. Please install Docker Desktop"
        exit 1
    fi
    success "Docker installed: $(docker --version)"
    
    # Check Docker Compose
    if ! command -v docker compose &> /dev/null; then
        error "Docker Compose not found"
        exit 1
    fi
    success "Docker Compose installed: $(docker compose version | head -1)"
    
    # Check Docker daemon running
    if ! docker ps &> /dev/null; then
        error "Docker daemon not running. Please start Docker Desktop"
        exit 1
    fi
    success "Docker daemon is running"
    
    # Check required files
    local files=("Dockerfile" "docker-compose.yml" ".dockerignore" "DOCKER_README.md" "site_g")
    for file in "${files[@]}"; do
        if [ ! -e "$file" ]; then
            error "Required file/directory not found: $file"
            exit 1
        fi
    done
    success "All required files present"
    
    echo ""
}

# ============================================================================
# PHASE 2: CONFIGURATION VALIDATION
# ============================================================================

phase_config() {
    log "Validating configuration..."
    
    # Validate docker-compose.yml syntax
    if ! docker compose config > /dev/null 2>&1; then
        error "docker-compose.yml syntax error"
        exit 1
    fi
    success "docker-compose.yml is valid"
    
    # Check for .env file
    if [ ! -f ".env" ]; then
        warn ".env file not found, using defaults"
        if [ -f ".env.example" ]; then
            log "Creating .env from .env.example..."
            cp .env.example .env
            success ".env created from template"
        fi
    else
        success ".env file found"
    fi
    
    echo ""
}

# ============================================================================
# PHASE 3: PORT AVAILABILITY
# ============================================================================

phase_ports() {
    log "Checking port availability..."
    
    local ports=(8082 8081 3308)
    local in_use=0
    
    for port in "${ports[@]}"; do
        if lsof -Pi :$port -sTCP:LISTEN -t >/dev/null 2>&1; then
            warn "Port $port is already in use"
            in_use=$((in_use + 1))
        else
            success "Port $port is available"
        fi
    done
    
    if [ $in_use -gt 0 ]; then
        warn "$in_use port(s) already in use - containers may fail to start"
        echo ""
        read -p "Continue anyway? (y/n) " -n 1 -r
        echo ""
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            log "Startup cancelled"
            exit 1
        fi
    fi
    
    echo ""
}

# ============================================================================
# PHASE 4: CLEANUP (if --down flag)
# ============================================================================

phase_cleanup() {
    if [[ "$1" == "--down" ]]; then
        log "Stopping and removing containers..."
        docker compose down -v
        success "Cleanup complete"
        exit 0
    fi
}

# ============================================================================
# PHASE 5: BUILD & START
# ============================================================================

phase_start() {
    log "Starting Docker containers..."
    
    if [[ "$1" == "--rebuild" ]]; then
        log "Rebuilding images (this may take a few minutes)..."
        docker compose up --build -d
    else
        log "Starting containers..."
        docker compose up -d
    fi
    
    success "Containers started"
    echo ""
}

# ============================================================================
# PHASE 6: HEALTH CHECKS
# ============================================================================

phase_health() {
    log "Waiting for services to be healthy..."
    
    local max_attempts=30
    local attempt=0
    local db_ready=0
    local web_ready=0
    
    while [ $attempt -lt $max_attempts ]; do
        attempt=$((attempt + 1))
        
        # Check database
        if docker compose exec -T db mysqladmin ping -h 127.0.0.1 -uroot -proot123 &>/dev/null; then
            if [ $db_ready -eq 0 ]; then
                success "Database is healthy"
                db_ready=1
            fi
        fi
        
        # Check web
        if curl -sf http://localhost:8082/index.php?page=bun_venit &>/dev/null; then
            if [ $web_ready -eq 0 ]; then
                success "Web application is healthy"
                web_ready=1
            fi
        fi
        
        if [ $db_ready -eq 1 ] && [ $web_ready -eq 1 ]; then
            success "All services are healthy"
            echo ""
            return 0
        fi
        
        echo -ne "\rWaiting for services... ($attempt/$max_attempts)"
        sleep 1
    done
    
    echo ""
    warn "Health check timeout - services may still be initializing"
    echo ""
}

# ============================================================================
# PHASE 7: DISPLAY STATUS & ACCESS INFO
# ============================================================================

phase_info() {
    log "Container status:"
    docker compose ps
    
    echo ""
    success "OffByOne Academy is running!"
    echo ""
    
    echo "📌 Access URLs:"
    echo "   🌐 OffByOne Academy:      ${BLUE}http://localhost:8082${NC}"
    echo "   📊 phpMyAdmin:       ${BLUE}http://localhost:8081${NC}"
    echo "   💾 MySQL Direct:     ${BLUE}localhost:3308${NC}"
    echo ""
    
    echo "🔐 Credentials:"
    echo "   MySQL User:          root"
    echo "   MySQL Password:      root123"
    echo "   Database:            dbsortari"
    echo ""
    
    echo "📖 Useful commands:"
    echo "   View logs:           ${YELLOW}docker compose logs -f web${NC}"
    echo "   DB logs:             ${YELLOW}docker compose logs -f db${NC}"
    echo "   Stop services:       ${YELLOW}docker compose down${NC}"
    echo "   Stop & clean:        ${YELLOW}docker compose down -v${NC}"
    echo "   Rebuild images:      ${YELLOW}docker compose up --build -d${NC}"
    echo ""
}

# ============================================================================
# MAIN EXECUTION
# ============================================================================

main() {
    echo ""
    echo "╔════════════════════════════════════════════════════════════╗"
    echo "║        🚀 OffByOne Academy — Docker Startup Script 2.0         ║"
    echo "╚════════════════════════════════════════════════════════════╝"
    echo ""
    
    # Parse arguments
    while [[ $# -gt 0 ]]; do
        case $1 in
            --rebuild)
                REBUILD=true
                shift
                ;;
            --down)
                phase_cleanup "$1"
                shift
                ;;
            --logs)
                docker compose logs -f web
                exit 0
                ;;
            *)
                echo "Unknown option: $1"
                echo "Usage: bash start.sh [--rebuild] [--down] [--logs]"
                exit 1
                ;;
        esac
    done
    
    # Execute phases
    phase_prerequisites
    phase_config
    phase_ports
    phase_start ${REBUILD:+--rebuild}
    phase_health
    phase_info
    
    echo "${GREEN}✓ Startup complete!${NC}"
    echo ""
}

# Run main function
main "$@"
