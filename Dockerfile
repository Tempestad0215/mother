# ==========================================
# ETAPA 1: Compilar el Frontend (Vue + TS + Vite)
# ==========================================
FROM node:22-alpine AS frontend-builder
WORKDIR /app

# Copiar archivos de dependencias de Node
COPY package.json pnpm-lock.yaml* package-lock.json* ./

# Instalar pnpm de forma global e instalar dependencias del proyecto
RUN npm install -g pnpm && pnpm install --frozen-lockfile

# Copiar el resto del código para la compilación de la interfaz
COPY . .

# Compilar los assets de producción (Vite procesa Vue y TS aquí)
RUN pnpm build


# ==========================================
# ETAPA 2: Aplicación de Producción (PHP 8.4 + Nginx)
# ==========================================
FROM php:8.4-fpm-alpine AS backend

# Instalar dependencias del sistema (Agregamos Nginx y Supervisor)
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    icu-dev

# Instalar y habilitar las extensiones nativas de PHP indispensables (Tus librerías de Postgres)
RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    zip \
    bcmath \
    intl \
    opcache

# Copiar Composer globalmente desde su imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo dentro del contenedor
WORKDIR /var/www

# Copiar los archivos de dependencias primero (Aprovechamiento de caché de capas)
COPY composer.json composer.lock ./

# Tu corrección de phpspreadsheet para ignorar ext-gd
RUN composer install --no-scripts --no-autoloader --no-dev --prefer-dist --ignore-platform-req=ext-gd

# Copiar todo el código fuente de tu aplicación Laravel al contenedor
COPY . .

# 🟢 LA MAGIA: Traer los assets compilados por Node en la ETAPA 1 a la carpeta pública de Laravel
COPY --from=frontend-builder /app/public/build ./public/build

# Generar el autoloader optimizado de Composer
RUN composer dump-autoload --optimize --classmap-authoritative

# Asegurar los permisos correctos para las carpetas de almacenamiento y caché de Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copiar configuraciones para que Nginx y Supervisor controlen los procesos
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf

# 🟢 Cambiamos el puerto expuesto al 80, que es el que Nginx usará para servir la web
EXPOSE 80

# Supervisor se encargará de encender Nginx y PHP-FPM en simultáneo
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
