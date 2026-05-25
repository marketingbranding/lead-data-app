#!/bin/bash
set -e

# Manual deploy script
# Usage: bash deploy.sh

echo "==> Pulling latest code..."
git pull origin main

echo "==> Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Installing & building frontend..."
npm ci --no-optional
npm run build

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Restarting queue worker..."
sudo supervisorctl restart laravel-worker:*

echo "==> Fixing permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "==> Deploy complete!"
