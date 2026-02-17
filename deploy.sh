#!/bin/bash

# Configuration
# This script is designed to be run from INSIDE the /vamikanew folder
PUBLIC_HTML_DIR="/home/u362391755/domains/vardaansmartsolutions.com/public_html/vamikanew"

echo "🚀 Starting Project-Level Deployment..."

# 1. Pull Latest Code
echo "📥 Pulling latest code..."
git pull origin main

# 2. FORCE CLEAR BOOTSTRAP CACHE (Fixes the 500 'Collision' error)
echo "🧹 Cleaning bootstrap cache files..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/packages.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/services.php

# 3. Install Dependencies
echo "📦 Installing composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 4. Run Migrations
echo "🗄 Running migrations..."
php artisan migrate --force

# 5. Rebuild Caches
echo "⚡ Rebuilding Laravel caches..."
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. SYNC ASSETS TO PUBLIC_HTML
echo "🔄 Syncing assets to public_html..."
if [ -d "$PUBLIC_HTML_DIR" ]; then
    cp -r public/* "$PUBLIC_HTML_DIR/"
    
    # 7. FIX PATHS in public_html/index.php
    echo "🛠 Fixing paths in public_html/index.php..."
    sed -i "s|__DIR__.'/../vendor/autoload.php'|__DIR__.'/../../vamikanew/vendor/autoload.php'|g" "$PUBLIC_HTML_DIR/index.php"
    sed -i "s|__DIR__.'/../bootstrap/app.php'|__DIR__.'/../../vamikanew/bootstrap/app.php'|g" "$PUBLIC_HTML_DIR/index.php"
    sed -i "s|__DIR__.'/../storage/framework/maintenance.php'|__DIR__.'/../../vamikanew/storage/framework/maintenance.php'|g" "$PUBLIC_HTML_DIR/index.php"
else
    echo "⚠️ Warning: $PUBLIC_HTML_DIR not found."
fi

# 8. FIX PERMISSIONS
echo "🔑 Adjusting permissions..."
chmod -R 775 storage bootstrap/cache

echo "✅ Project Deployment Complete!"
