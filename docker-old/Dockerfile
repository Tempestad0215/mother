# ==========================================
# ETAPA 1: Compilar el Frontend (Vue + TS)
# ==========================================
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY ../package.json pnpm-lock.yaml* ./
RUN npm install -g pnpm && pnpm install --frozen-lockfile
COPY .. .
RUN pnpm run build

# ==========================================
# ETAPA 2: Imagen de Producción (PHP + Nginx)
# ==========================================
FROM php:8.4-fpm-alpine

# 1. Instalar dependencias del sistema incluyendo Nginx
RUN apk add --no-cache \
    nginx \
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
    supervisor

# 2. Instalar extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    opcache \
    gd \
    bcmath \
    intl \
    zip \
    exif

# 3. Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. Copiar composer.json primero (para caché)
COPY ../composer.json composer.lock* ./

# 5. Instalar dependencias de Laravel
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# 6. Copiar el resto del código
COPY .. .

# 7. Copiar assets de Vue
COPY --from=frontend-builder /app/public/build ./public/build

# 8. Configurar Git
RUN git config --global --add safe.directory /var/www/html

# 9. Generar autoloader optimizado
RUN composer dump-autoload --optimize

# 10. Configurar Nginx
COPY ../docker/default.conf /etc/nginx/http.d/default.conf

# 11. Crear directorio para logs de Nginx
RUN mkdir -p /var/log/nginx && \
    chown -R www-data:www-data /var/log/nginx

# 12. Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/public

# 13. Configurar Supervisor
COPY ../docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 14. Script de inicio
#COPY docker/entrypoint.sh /entrypoint.sh
#RUN chmod +x /entrypoint.sh

EXPOSE 80


# ENTRYPOINT ["/entrypoint.sh"]
