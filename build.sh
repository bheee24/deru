#!/bin/bash
set -e

echo "Downloading Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

echo "Installing PHP dependencies..."
composer install --no-interaction --no-dev --optimize-autoloader

echo "Installing Node dependencies..."
npm install

echo "Building assets..."
npm run production

echo "Generating app key..."
php artisan key:generate

echo "Build completed successfully!"
