FROM dunglas/frankenphp:1-php8.4

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

# Debian usa apt, no apk
RUN apt-get update && apt-get install -y --no-install-recommends \
        nodejs \
        npm \
        curl \
        ca-certificates \
    && rm -rf /var/lib/apt/lists/* \
    && npm install -g pnpm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . /app

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN pnpm install --frozen-lockfile \
    && pnpm build \
    && rm -rf node_modules

RUN ln -sf /usr/local/bin/frankenphp /usr/bin/frankenphp \
    && ln -sf /usr/local/bin/frankenphp /app/frankenphp

ENV OCTANE_BINARY=/usr/local/bin/frankenphp

ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
