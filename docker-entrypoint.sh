#!/bin/sh
set -e

mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

php artisan storage:link || true
php artisan migrate --force

exec frankenphp run --config /etc/frankenphp/Caddyfile
