#!/bin/bash
set -e

PHP="php -d disable_functions=''"

echo "Starting Deployment for Backend..."

cd /www/wwwroot/storead.fulfillec.com

# Setup .env si no existe
if [ ! -f .env ]; then
    cp .env.production .env
    echo ".env created from .env.production"
fi

# Instalar dependencias
$PHP /usr/local/bin/composer install --no-dev --optimize-autoloader

# Ejecutar migraciones
$PHP artisan migrate --force

# Storage link
if [ ! -L public/storage ]; then
    ln -sf "$(pwd)/storage/app/public" public/storage
fi

# Limpiar cachés viejos primero
$PHP artisan cache:clear
$PHP artisan config:clear
$PHP artisan view:clear
$PHP artisan route:clear

# Reconstruir cachés
$PHP artisan config:cache
$PHP artisan view:cache

echo "Deployment finished successfully!"
