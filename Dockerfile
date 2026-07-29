# Usamos PHP 8.4 con Apache para Laravel
FROM php:8.4-apache

# 1. Instalar dependencias del sistema operativo y librerías de desarrollo
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libssl-dev \
    pkg-config \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Instalar la extensión de MongoDB mediante PECL y habilitarla
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# 3. Instalar extensiones nativas de PHP requeridas por Laravel y DomPDF
RUN docker-php-ext-install mbstring exif pcntl bcmath gd zip

# 4. Habilitar mod_rewrite de Apache para las rutas de Laravel
RUN a2enmod rewrite

# 5. Apuntar la raíz web de Apache a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# 6. Configurar Apache para escuchar en el puerto dinámico de Render ($PORT)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 7. Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8. Copiar los archivos del proyecto al contenedor
WORKDIR /var/www/html
COPY . /var/www/html

# 9. Instalar dependencias de PHP para producción
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 10. Dar permisos de escritura a las carpetas requeridas por Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Puerto por defecto expuesto
EXPOSE 80

# 12. Comando de arranque: limpia caché, ejecuta migraciones + seeders y levanta Apache
CMD ["sh", "-c", "php artisan config:clear && php artisan cache:clear && php artisan migrate:fresh --seed --force && apache2-foreground"]