#!/bin/bash
set -e

echo "Starting Deployment for Backend..."

# Navegar al directorio del proyecto
cd /www/wwwroot/storead.fulfillec.com

# Setup .env si no existe
if [ ! -f .env ]; then
    cp .env.production .env
    echo ".env created from .env.production"
fi

# Instalar dependencias
php -d disable_functions="" /usr/local/bin/composer install --no-dev --optimize-autoloader

# Ejecutar migraciones
php artisan migrate --force

# Storage link
if [ ! -L public/storage ]; then
    ln -sf "$(pwd)/storage/app/public" public/storage
fi

# Limpiar y reconstruir caché
php artisan config:cache
php artisan view:cache

echo "Deployment finished successfully!"
