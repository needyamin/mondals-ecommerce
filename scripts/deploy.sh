#!/bin/bash
# 🚢 Mondal's E-Commerce Deployment Script 🚢
# Designed for Docker-Compose powered deployment on Ubuntu

set -euo pipefail

APP_CONTAINER="mondals-app"
DB_CONTAINER="mondals-db"

echo ""
echo "🌊 Starting Mondal's E-Commerce Deployment..."
echo ""

# ── 1. Verify Docker is running ──
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running! Start Docker first:"
    echo "   sudo systemctl start docker"
    echo "   Or run: ./scripts/setup-server.sh"
    exit 1
fi

# ── 2. Ensure .env file exists ──
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "⚠️  No .env file found. Copying from .env.example..."
        cp .env.example .env
        echo "📝 Generating application key..."
        # We'll generate the key after container starts
    else
        echo "❌ Neither .env nor .env.example found!"
        echo "   Please create a .env file with your configuration."
        exit 1
    fi
fi

# ── 3. Synchronize code from repository ──
if [ -d .git ]; then
    echo "🔄 Synchronizing code from Git..."
    # Reclaim ownership to allow git to overwrite files created by Docker www-data
    sudo chown -R "$USER":"$USER" .
    git fetch origin
    git reset --hard origin/main
fi

# ── 4. Stop existing containers ──
echo "🛑 Stopping existing containers..."
docker compose down --remove-orphans 2>/dev/null || true

# ── 4. Build and start containers ──
echo "🏗️  Building and starting containers..."
docker compose up -d --build

# ── 5. Wait for database to be ready (proper health check instead of sleep) ──
echo "⏳ Waiting for database to be ready..."
MAX_RETRIES=30
RETRY_COUNT=0
until docker exec "$DB_CONTAINER" mysqladmin ping -h "127.0.0.1" -u root -proot_password --silent 2>/dev/null; do
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

# ── 6. Laravel Setup & Permissions ──
echo "🔐 Fixing project permissions (Direct Public Storage)..."
# 1. Migrate any legacy files from storage to public if they exist
docker exec "$APP_CONTAINER" bash -c "cp -rn storage/app/public/* public/ 2>/dev/null || true"

# 2. Give ownership to www-data for core directories
docker exec "$APP_CONTAINER" chown -R www-data:www-data storage bootstrap/cache public
# 3. Set directory level permissions (775 for app/storage dirs, 755 for public traversal)
docker exec "$APP_CONTAINER" chmod -R 775 storage bootstrap/cache
docker exec "$APP_CONTAINER" chmod -R 755 public

# ── 7. Generate app key if not set ──
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    docker exec "$APP_CONTAINER" php artisan key:generate --force
fi

# ── 8. Run Laravel caching & migrations ──
echo "⚡ Refreshing application cache..."
docker exec "$APP_CONTAINER" php artisan cache:clear
docker exec "$APP_CONTAINER" php artisan config:clear
docker exec "$APP_CONTAINER" php artisan config:cache
docker exec "$APP_CONTAINER" php artisan route:cache
docker exec "$APP_CONTAINER" php artisan view:cache


echo "🛠️  Running database migrations..."
docker exec "$APP_CONTAINER" php artisan migrate --force

# ── 10. Quick health check ──
echo "🔍 Verifying application..."
sleep 5
HTTP_STATUS=$(curl -s -k -o /dev/null -w "%{http_code}" https://needyamin.site 2>/dev/null || echo "000")
if [ "$HTTP_STATUS" = "200" ] || [ "$HTTP_STATUS" = "302" ]; then
    echo "✅ Application is responding (HTTP $HTTP_STATUS)"
else
    echo "⚠️  Application returned HTTP $HTTP_STATUS (may still be starting up)"
    echo "   Check logs: docker logs mondals-caddy --tail 20"
fi

# ── 11. Cleanup old resources ──
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
echo "  Useful commands:"
echo "    docker compose logs -f        # Watch logs"
echo "    docker exec -it $APP_CONTAINER bash  # Shell into app"
echo "    docker compose down            # Stop everything"
echo "============================================"
