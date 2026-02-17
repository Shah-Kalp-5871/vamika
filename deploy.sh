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
# Copy contents of public/ to public_html/
cp -r public/* "$PUBLIC_HTML_DIR/"

echo "✅ Deployment Complete!"
