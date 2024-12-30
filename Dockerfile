FROM php:8.2-fpm

# Copiamos los archivos package.json composer.json y composer-lock.json a /var/www/
COPY composer*.json /var/www/

# Nos movemos a /var/www/
WORKDIR /var/www/

RUN apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd


# Instalamos dependendencias de composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copiamos todos los archivos de la carpeta actual de nuestra
# computadora (los archivos de laravel) a /var/www/
COPY . /var/www/

# Exponemos el puerto 9000 a la network
EXPOSE 9000

# Corremos el comando php-fpm para ejecutar PHP
CMD ["php-fpm"]
