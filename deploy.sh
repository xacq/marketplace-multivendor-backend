#!/bin/bash
set -e

echo "Starting Deployment for Backend..."

cd /www/wwwroot/storead.fulfillec.com

# Sincronizar con el repo remoto (siempre usa el estado de GitHub, descarta commits locales)
git fetch origin main-fulfillec
git reset --hard origin/main-fulfillec

# Setup .env si no existe
if [ ! -f .env ]; then
    cp .env.production .env
    echo ".env created from .env.production"
fi

# Instalar dependencias
php -d disable_functions="" /usr/local/bin/composer install --no-dev --optimize-autoloader

# Ejecutar migraciones
php -d disable_functions="" artisan migrate --force

# Storage link
if [ ! -L public/storage ]; then
    ln -sf "$(pwd)/storage/app/public" public/storage
fi

# Limpiar cachés viejos
php -d disable_functions="" artisan cache:clear
php -d disable_functions="" artisan config:clear
php -d disable_functions="" artisan view:clear
php -d disable_functions="" artisan route:clear

# Reconstruir cachés
php -d disable_functions="" artisan config:cache
php -d disable_functions="" artisan view:cache

echo "Deployment finished successfully!"
