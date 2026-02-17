#!/bin/bash

# Configuration
PROJECT_DIR="/home/u362391755/domains/vardaansmartsolutions.com/vamikanew"
PUBLIC_HTML_DIR="/home/u362391755/domains/vardaansmartsolutions.com/public_html/vamikanew"

echo "🚀 Starting Deployment..."

# 1. Pull Latest Code
echo "📥 Pulling latest code..."
git pull origin main

# 2. Install Dependencies (Optional, good practice)
echo "📦 Installing composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Run Migrations (Optional)
echo "🗄 Running migrations..."
php artisan migrate --force

# 4. Clear Caches
echo "🧹 Clearing caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. SYNC ASSETS (Crucial Step!)
echo "🔄 Syncing assets to public_html..."
cp -r public/* "$PUBLIC_HTML_DIR/"

# 6. FIX PATHS in public_html/index.php (Server specific)
echo "🛠 Fixing paths in index.php for server environment..."
sed -i "s|__DIR__.'/../vendor/autoload.php'|__DIR__.'/../../vamikanew/vendor/autoload.php'|g" "$PUBLIC_HTML_DIR/index.php"
sed -i "s|__DIR__.'/../bootstrap/app.php'|__DIR__.'/../../vamikanew/bootstrap/app.php'|g" "$PUBLIC_HTML_DIR/index.php"
sed -i "s|__DIR__.'/../storage/framework/maintenance.php'|__DIR__.'/../../vamikanew/storage/framework/maintenance.php'|g" "$PUBLIC_HTML_DIR/index.php"

# 7. FIX PERMISSIONS (Common 500 error fix)
echo "🔑 Adjusting permissions for storage and cache..."
chmod -R 775 storage bootstrap/cache

echo "✅ Deployment Complete!"
