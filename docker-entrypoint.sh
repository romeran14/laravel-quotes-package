#!/bin/bash
set -e

# 1. If artisan file doesn't exist, we install Laravel
if [ ! -f "artisan" ]; then
    echo "Creating new Laravel application..."
    
    # Create a temporary project to avoid "folder not empty" error
    composer create-project laravel/laravel tmp_laravel --prefer-dist
    
    # Move files to the root (/var/www)
    cp -rn tmp_laravel/. .
    rm -rf tmp_laravel

    echo "Configuring local package repository..."
    # Set the path relative to where Docker mounts your code
    composer config repositories.local-package path /var/www/package
    
    echo "Installing the package..."
    # Use --no-interaction to skip confirmations
    composer require "laravel-package-maker/quotes-package:@dev" --no-interaction

    echo "Configuring environment..."
    if [ ! -f ".env" ]; then
        cp .env.example .env
        php artisan key:generate
    fi

    echo "Publishing package configuration..."
    php artisan vendor:publish --tag=quotes-config --force
fi


echo "Preparing database..."
# Ensure the database directory exists and create the sqlite file
mkdir -p database
touch database/database.sqlite

chmod -R 777 database

echo "Running migrations..."
php artisan migrate --force

# Seed initial quotes
echo "Seeding initial quotes..."
php artisan quotes:batch-import 10

# UI assetes publishing
echo "Publishing UI assets..."
php artisan vendor:publish --tag=quotes-assets --force

# Execute the main command (php-fpm or php artisan serve)
echo "🎬 Starting application..."
exec "$@"