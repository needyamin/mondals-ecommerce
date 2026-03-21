#!/bin/bash
set -e

# Cache configuration, routes, and views if in production
if [ "$APP_ENV" == "production" ]; then
    echo "Caching Laravel configuration, routes, and views..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Run database migrations (optional: can be moved to a separate K8s Job)
if [ "$RUN_MIGRATIONS" == "true" ]; then
    echo "Running data migrations..."
    php artisan migrate --force
fi

# Start the application server
exec "$@"
