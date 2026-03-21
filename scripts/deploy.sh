#!/bin/bash
# 🚢 Mondal's E-Commerce Deployment Script 🚢
# Designed for Docker-Compose powered deployment on Ubuntu

set -e

echo "🌊 Starting Mondal's E-Commerce Deployment..."

# 1. Pull latest code from current directory (or use a Git repo)
if [ ! -f .env ]; then
  echo "⚠️ NO .env file found! Copying .env.example..."
  cp .env.example .env
  echo "⚠️ IMPORTANT: Please update your .env with production credentials before running again!"
  exit 1
fi

# 2. Stop existing containers and remove orphans
echo "🛑 Stopping existing containers..."
docker compose down --remove-orphans

# 3. Rebuild and start the containers in detached mode
echo "🏗️ Building and starting new containers..."
docker compose up -d --build

# 4. Wait for database to initialize
echo "⏳ Waiting for Database to be ready..."
sleep 20

# 5. Run essential Laravel maintenance
echo "🛠️ Finalizing App (Migrations & Cache)..."
docker exec mondals-app php artisan migrate --force
docker exec mondals-app php artisan config:cache
docker exec mondals-app php artisan route:cache
docker exec mondals-app php artisan view:cache
docker exec mondals-app php artisan storage:link

# 6. Optimized permissions fix
echo "🔐 Correcting file permissions inside container..."
docker exec mondals-app chown -R www-data:www-data storage bootstrap/cache

echo "🚀 DEPLOYMENT SUCCESSFUL!"
echo "🌐 Your app should now be live at: http://localhost:8000"
