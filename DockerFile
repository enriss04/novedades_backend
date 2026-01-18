FROM php:8.3-apache

# 1. Configuración de sistema y TZ
ENV TZ=America/Mexico_City
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# 2. Instalar dependencias de sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql gd zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Configurar Apache y PHP
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# 4. Instalar Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Preparar el código (PASO CRUCIAL)
WORKDIR /var/www/html

# Copiamos todo el contenido de la carpeta actual del VPS al contenedor
COPY . /var/www/html

# 6. Instalar dependencias de PHP
# Ahora sí, composer.json ya existe dentro del contenedor
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 7. Permisos (Importante: hacer después de copiar el código)
# Cambiamos el dueño a www-data para que Apache pueda escribir en logs y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 80

CMD ["apache2-foreground"]