#!/bin/bash
set -e

echo "Starting Deployment for Backend..."

cd /www/wwwroot/storead.fulfillec.com

# Sincronizar con el repo remoto
git pull origin main-fulfillec --ff-only

# Setup .env si no existe
if [ ! -f .env ]; then
    cp .env.production .env
    echo ".env created from .env.production"
fi

# Instalar dependencias
php /usr/local/bin/composer install --no-dev --optimize-autoloader

# Ejecutar migraciones
php artisan migrate --force

# Storage link
if [ ! -L public/storage ]; then
    ln -sf "$(pwd)/storage/app/public" public/storage
fi

# Limpiar cachés viejos
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Reconstruir cachés
php artisan config:cache
php artisan view:cache

echo "Deployment finished successfully!"
