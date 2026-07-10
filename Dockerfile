# ==========================================
# ETAPA 1: Compilar el Frontend (Vue + TS)
# ==========================================
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package.json pnpm-lock.yaml* ./
RUN npm install -g pnpm && pnpm install --frozen-lockfile
COPY . .
RUN pnpm run build

# ==========================================
# ETAPA 2: Imagen de Producción (PHP + Composer)
# ==========================================
FROM php:8.4-fpm-alpine

# 1. Instalar dependencias del sistema + todas las librerías necesarias
RUN apk add --no-cache \
    postgresql-client \
    libpq-dev \
    zip \
    unzip \
    git \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    icu-dev \
    libxml2-dev

# 2. Instalar TODAS las extensiones PHP necesarias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        opcache \
        gd \
        bcmath \
        intl \
        zip \
        exif \
        soap \
        sockets \
        pcntl

# 3. Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. Copiar composer.json primero (para caché)
COPY composer.json composer.lock* ./

# 5. Instalar dependencias de Laravel
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# 6. Copiar el resto del código
COPY . .

# 7. Copiar assets de Vue
COPY --from=frontend-builder /app/public/build ./public/build

# 8. Generar autoloader optimizado
RUN composer dump-autoload --optimize

# 9. Configurar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm", "-F"]
