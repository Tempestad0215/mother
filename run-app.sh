#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x run-app.sh`


#permisos a la carpeta
chmod -R 775 storage
chmod -R 775 bootstrap/cache

#colocar el puerto
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

# Build assets using NPM
npm run build

# Clear cache
php artisan optimize:clear

#Crear el link
php artisan storage:link

# Cache the various components of the Laravel application
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Run any database migrations
php artisan migrate --force

