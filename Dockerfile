FROM elrincondeisma/php-for-laravel:8.3.7
LABEL authors="Marionil Guzman"


WORKDIR /app
COPY . .


RUN composer install && npm install
RUN composer require laravel/octane
COPY .env.example .env

RUN mkdir -p /app/storage/logs

RUN php artisan octane:install --server="swoole"


CMD ["sh", "-c", "php artisan octane:start --server=swoole --host=0.0.0.0 & npm run dev"]
EXPOSE 8000
