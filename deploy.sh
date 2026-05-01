#!/bin/bash
set -e

echo "Starting Deployment for Backend..."

cd /www/wwwroot/storead.fulfillec.com

# Sincronizar con el repo remoto
git pull origin main-fulfillec --ff-only

# Aplicar configuración de producción
cp .env.production .env

# Permisos de storage y uploads yes
chown -R www:www storage bootstrap/cache public/uploads
chmod -R 775 storage bootstrap/cache public/uploads

PHP=/www/server/php/82/bin/php
COMPOSER=/usr/local/bin/composer

# Instalar dependencias
$PHP -d disable_functions="" $COMPOSER install --no-dev --optimize-autoloader

# Ejecutar migraciones
$PHP artisan migrate --force

# Storage link
if [ ! -L public/storage ]; then
    ln -sf "$(pwd)/storage/app/public" public/storage
fi

# Limpiar cachés viejos
$PHP artisan cache:clear
$PHP artisan config:clear
$PHP artisan view:clear
$PHP artisan route:clear

# Reconstruir cachés
$PHP artisan config:cache
$PHP artisan view:cache

echo "Deployment finished successfully!"
