FROM dunglas/frankenphp

RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    pgsql \
    redis \
    intl \
    zip \
    opcache \
    bcmath \
    gd
    # Add other PHP extensions here...

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

# 3. Instalar las dependencias de Composer para producción
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 4. Asegurar permisos correctos para que Laravel pueda escribir logs y caché
RUN chown -R write-user:write-user /app/storage /app/bootstrap/cache

ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
