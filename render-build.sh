#!/usr/bin/env bash
set -o errexit

cd "$(dirname "${BASH_SOURCE[0]}")"

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
npm install
npm run build

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
