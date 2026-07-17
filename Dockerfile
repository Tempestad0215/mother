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

# 2. Instalar Node.js, npm y pnpm para compilar los assets de Vite
# Usamos el gestor de paquetes de Alpine (apk)
RUN apk add --no-cache nodejs npm \
    && npm install -g pnpm

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app


# 3. Instalar las dependencias de Composer para producción
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 4. Instalar dependencias de Node y compilar los recursos de Frontend con Vite
RUN pnpm install --frozen-lockfile \
    && pnpm build \
    && rm -rf node_modules # Limpiamos node_modules para mantener la imagen ligera

RUN ln -sf /usr/local/bin/frankenphp /usr/bin/frankenphp \
    && ln -sf /usr/local/bin/frankenphp /app/frankenphp

ENV OCTANE_BINARY=/usr/local/bin/frankenphp

ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
