#!/bin/bash
set -e

# Generate .env file from environment variables
cat > .env << EOF
APP_NAME="Akademi Bimbel"
APP_ENV=${APP_ENV:-local}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-true}
APP_URL=${APP_URL:-http://localhost:5000}

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@akademibimbel.com"
MAIL_FROM_NAME="Akademi Bimbel"
EOF

echo "Generated .env file"

# Clear config cache
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Run migrations
echo "Running migrations..."
php artisan migrate --force 2>&1

# Seed database if roles don't exist yet
ROLE_COUNT=$(php artisan tinker --execute="echo \Spatie\Permission\Models\Role::count();" 2>/dev/null | tail -1 || echo "0")
if [ "$ROLE_COUNT" = "0" ]; then
    echo "Seeding database..."
    php artisan db:seed --force 2>&1
else
    echo "Database already seeded (roles: $ROLE_COUNT), skipping."
fi

# Create storage symlink
php artisan storage:link 2>/dev/null || true

# Build assets if public/build doesn't exist
if [ ! -d "public/build" ]; then
    echo "Building frontend assets..."
    npm run build 2>&1
fi

echo "Starting Laravel server on 0.0.0.0:5000..."
php artisan serve --host=0.0.0.0 --port=5000
