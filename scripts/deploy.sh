#!/bin/bash
# 🚢 Mondal's E-Commerce Deployment Script 🚢
# Designed for Docker-Compose powered deployment on Ubuntu

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
cd "$ROOT"

APP_CONTAINER="mondals-app"
DB_CONTAINER="mondals-db"

echo ""
echo "🌊 Starting Mondal's E-Commerce Deployment..."
echo "   (repo root: $ROOT)"
echo ""

# ── 1. Verify Docker is running ──
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running! Start Docker first:"
    echo "   sudo systemctl start docker"
    echo "   Or run: cd scripts && ./setup-server.sh && ./up"
    exit 1
fi

# ── 2. Pull latest code first (.env is gitignored — never on GitHub; .env.example is)
if [ -d .git ]; then
    echo "🔄 Synchronizing code from Git..."
    sudo chown -R "$USER":"$USER" .
    git fetch origin
    git reset --hard origin/main
fi

# ── 3. Create .env if missing (clone / fresh server — normal to have no .env after pull)
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "📝 No .env (expected after git clone). Copying .env.example → .env"
        cp .env.example .env
    else
        echo "❌ No .env and no .env.example — add .env.example to the repo or create .env manually."
        exit 1
    fi
fi

# ── 4. Align .env with Docker (DB host db, mysql, session/cache/queue)
echo "🔗 Syncing .env for Docker..."
if command -v python3 >/dev/null 2>&1; then
    python3 "$SCRIPT_DIR/sync-docker-env.py" "$ROOT"
else
    echo "⚠️  Install python3 for automatic .env sync; using compose defaults for DB ping."
fi

# ── 5. Stop existing containers ──
echo "🛑 Stopping existing containers..."
docker compose down --remove-orphans 2>/dev/null || true

# ── 6. Build and start containers ──
echo "🏗️  Building and starting containers..."
docker compose up -d --build

# ── 7. Wait for database to be ready (proper health check instead of sleep) ──
echo "⏳ Waiting for database to be ready..."
MYSQL_PW=$(grep -E '^DB_PASSWORD=' .env | tail -1 | cut -d= -f2- | tr -d '"' | tr -d "'")
MYSQL_PW=${MYSQL_PW:-root_password}
MAX_RETRIES=30
RETRY_COUNT=0
until docker exec "$DB_CONTAINER" mysqladmin ping -h "127.0.0.1" -u root -p"$MYSQL_PW" --silent 2>/dev/null; do
    RETRY_COUNT=$((RETRY_COUNT + 1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "❌ Database failed to start after ${MAX_RETRIES} attempts!"
        echo "🔍 Database logs:"
        docker logs "$DB_CONTAINER" --tail 30
        exit 1
    fi
    echo "   Waiting for MySQL... (attempt $RETRY_COUNT/$MAX_RETRIES)"
    sleep 2
done
echo "✅ Database is ready!"

# ── 7b. Wait until app container accepts exec (avoids "restarting" race)
echo "⏳ Waiting for app container..."
APP_RETRIES=0
until docker exec "$APP_CONTAINER" true 2>/dev/null; do
    APP_RETRIES=$((APP_RETRIES + 1))
    STATUS=$(docker inspect -f '{{.State.Status}}' "$APP_CONTAINER" 2>/dev/null || echo "unknown")
    if [ "$APP_RETRIES" -ge 60 ]; then
        echo "❌ App container not ready after 60 attempts (status: $STATUS)"
        docker logs "$APP_CONTAINER" --tail 80
        exit 1
    fi
    echo "   Waiting for app... ($APP_RETRIES/60, status: $STATUS)"
    sleep 2
done
echo "✅ App container is ready!"

# ── 8. Laravel Setup & Permissions ──
echo "🔐 Fixing project permissions (Direct Public Storage)..."
# 1. Migrate any legacy files from storage to public if they exist
docker exec "$APP_CONTAINER" bash -c "cp -rn storage/app/public/* public/ 2>/dev/null || true"

# 2. Give ownership to www-data for core directories
docker exec "$APP_CONTAINER" chown -R www-data:www-data storage bootstrap/cache public
# 3. Set directory level permissions (775 for app/storage dirs, 755 for public traversal)
docker exec "$APP_CONTAINER" chmod -R 775 storage bootstrap/cache
docker exec "$APP_CONTAINER" chmod -R 755 public

# ── 9. Generate app key if not set ──
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    docker exec "$APP_CONTAINER" php artisan key:generate --force
fi

# ── 10. Migrations first (database cache/session tables must exist before cache:clear)
echo "🛠️  Running database migrations..."
docker exec "$APP_CONTAINER" php artisan migrate --force

# ── 11. Laravel cache (after DB tables exist when CACHE_STORE=database)
echo "⚡ Refreshing application cache..."
docker exec "$APP_CONTAINER" php artisan cache:clear
docker exec "$APP_CONTAINER" php artisan config:clear
docker exec "$APP_CONTAINER" php artisan config:cache
docker exec "$APP_CONTAINER" php artisan route:cache
docker exec "$APP_CONTAINER" php artisan view:cache

# ── 12. Quick health check ──
echo "🔍 Verifying application..."
sleep 5
HTTP_STATUS=$(curl -s -k -o /dev/null -w "%{http_code}" https://needyamin.site 2>/dev/null || echo "000")
if [ "$HTTP_STATUS" = "200" ] || [ "$HTTP_STATUS" = "302" ]; then
    echo "✅ Application is responding (HTTP $HTTP_STATUS)"
else
    echo "⚠️  Application returned HTTP $HTTP_STATUS (may still be starting up)"
    echo "   Check logs: docker logs mondals-caddy --tail 20"
fi

# ── 13. Cleanup old resources ──
echo "🧹 Cleaning up old Docker layers..."
docker image prune -f

echo ""
echo "============================================"
echo "  🚀 DEPLOYMENT SUCCESSFUL!"
echo "============================================"
echo ""
echo "  🌐 App:        https://needyamin.site"
echo "  🗄️  phpMyAdmin: http://localhost:8088"
echo "  📊 DB Port:     localhost:3388"
echo ""
echo "  Useful commands (from $ROOT):"
echo "    cd \"$ROOT\" && docker compose logs -f"
echo "    docker exec -it $APP_CONTAINER bash"
echo "    cd \"$ROOT\" && docker compose down"
echo "============================================"
