.PHONY: help start stop restart logs status reset build clean

help:
	@echo "🐳 SImp Portal — Docker Commands"
	@echo ""
	@echo "Usage: make [command]"
	@echo ""
	@echo "Commands:"
	@echo "  start       - Start all services (build + up)"
	@echo "  stop        - Stop all services"
	@echo "  restart     - Restart all services"
	@echo "  logs        - View live logs (web service)"
	@echo "  logs-db     - View live logs (database service)"
	@echo "  status      - Show container status"
	@echo "  build       - Build Docker image"
	@echo "  reset       - Clean reset (DELETE all data)"
	@echo "  clean       - Remove containers, images, volumes"
	@echo "  shell       - Open bash in web container"
	@echo "  db-shell    - Open MySQL shell"
	@echo ""

start:
	@echo "🚀 Starting SImp Portal..."
	@docker compose up --build -d
	@echo "⏳ Waiting for services..."
	@sleep 5
	@echo "✓ Services started!"
	@echo ""
	@echo "📍 Access Points:"
	@echo "  • SImp Portal:  http://localhost:8082"
	@echo "  • phpMyAdmin:   http://localhost:8081"
	@echo "  • MySQL:        localhost:3308"
	@echo ""

stop:
	@echo "🛑 Stopping services..."
	@docker compose down

restart:
	@echo "🔄 Restarting services..."
	@docker compose restart
	@echo "✓ Services restarted"

logs:
	@docker compose logs -f web

logs-db:
	@docker compose logs -f db

status:
	@docker compose ps

build:
	@echo "🏗️  Building Docker image..."
	@docker compose build

reset:
	@echo "⚠️  This will DELETE all database data!"
	@read -p "Continue? (yes/no): " confirm; \
	if [ "$$confirm" = "yes" ]; then \
		docker compose down -v; \
		echo "✓ Clean reset complete"; \
	else \
		echo "Cancelled"; \
	fi

clean:
	@echo "🧹 Removing all Docker objects..."
	@docker compose down -v
	@docker system prune -f
	@echo "✓ Cleanup complete"

shell:
	@docker compose exec web bash

db-shell:
	@docker compose exec db mysql -uroot -proot123 dbsortari

test-health:
	@echo "🏥 Testing service health..."
	@docker compose exec -T web curl -f http://localhost/index.php?page=bun_venit && echo "✓ Web OK" || echo "✗ Web FAILED"
	@docker compose exec -T db mysqladmin ping -h 127.0.0.1 -uroot -proot123 && echo "✓ DB OK" || echo "✗ DB FAILED"

backup:
	@echo "💾 Backing up database..."
	@docker compose exec -T db mysqldump -uroot -proot123 dbsortari > backup_dbsortari_$$(date +%Y%m%d_%H%M%S).sql
	@echo "✓ Backup created"

restore:
	@read -p "Backup file path: " backupfile; \
	docker compose exec -T db mysql -uroot -proot123 dbsortari < $$backupfile; \
	echo "✓ Database restored"
