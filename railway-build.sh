#!/bin/bash
echo "Installing PHP dependencies..."
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader

echo "Installing Node dependencies..."
npm install

echo "Building assets..."
npm run production

echo "Generating app key..."
php artisan key:generate

echo "Build completed!"
