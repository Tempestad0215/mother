FROM dunglas/frankenphp:1.4-builder-php8.4.5-alpine

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

RUN ln -sf /usr/local/bin/frankenphp /usr/bin/frankenphp \
    && ln -sf /usr/local/bin/frankenphp /app/frankenphp

ENV OCTANE_BINARY=/usr/local/bin/frankenphp

ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
