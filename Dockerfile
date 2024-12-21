# Usa Alpine como base
FROM elrincondeisma/php-for-laravel:8.3.7


WORKDIR /app
COPY . .

RUN composer install && npm install
RUN composer require laravel/octane
COPY .env.example .env

# Crear los directorios necesarios
RUN mkdir -p /app/storage/logs && \
    chmod -R 775 /app/storage && \
    chmod -R 775 /app/bootstrap/cache

RUN php artisan octane:install --server="swoole"

CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0"]
EXPOSE 8000
