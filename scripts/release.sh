#!/bin/bash

echo "=== Railway Deployment Debug ==="

# Copy Railway .env
cp .env.railway .env

echo "Environment Check:"
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "DATABASE_URL exists: ${DATABASE_URL:+yes}${DATABASE_URL:+, first 50 chars: ${DATABASE_URL:0:50}}${DATABASE_URL:- NO}"
echo ""

echo "Attempting database connection test..."
if [ -z "$DATABASE_URL" ]; then
    echo "ERROR: DATABASE_URL not set!"
    echo "Make sure MySQL service is linked in Railway and restart deployment"
    exit 1
fi

echo "DATABASE_URL is set, proceeding with migrations..."
php artisan config:clear
php artisan route:clear 
php artisan view:clear

echo "Installing npm dependencies..."
npm install

echo "Building Vite assets..."
npm run build

if [ ! -d "public/build" ]; then
    echo "ERROR: public/build directory not created!"
    exit 1
fi

if [ ! -f "public/build/manifest.json" ]; then
    echo "ERROR: manifest.json not found!"
    exit 1
fi

echo "Assets built successfully. Files:"
ls -la public/build/

echo "Running migrations..."
php artisan storage:link
php artisan migrate --force

echo "Caching config for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Migrations complete!"
