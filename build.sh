#!/bin/bash
set -e

echo "Installing PHP dependencies..."
composer install --no-interaction --no-dev --optimize-autoloader

echo "Installing Node dependencies..."
npm install

echo "Building assets..."
npm run production

echo "Generating app key..."
php artisan key:generate

echo "Build completed successfully!"
