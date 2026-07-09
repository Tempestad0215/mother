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

# 1. Instalar dependencias del sistema, herramientas de compresión y extensiones PHP
# 1. Instalar dependencias esenciales (evitando LLVM pesado)
RUN apk add --no-cache libpq-dev postgresql-client zip unzip git \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS postgresql-dev \
    && docker-php-ext-install pdo_pgsql pgsql opcache \
    && apk del .build-deps

# 2. Instalar Composer de forma oficial copiándolo desde su imagen interna
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3. Copiar archivos de dependencias de PHP primero (Optimiza la caché de Docker)
COPY composer.json composer.lock* ./

# 4. Instalar dependencias de Laravel para producción (Sin dev-dependencies, optimizado)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# 5. Copiar el resto del código fuente del proyecto
COPY . .

# 6. Copiar los assets compilados de Vue desde la Etapa 1
COPY --from=frontend-builder /app/public/build ./public/build

# 7. Generar el autoloader optimizado de Composer una vez que todo el código está copiado
RUN composer dump-autoload --optimize

# 8. Configurar los permisos correctos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm", "-F"]
