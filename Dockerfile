# Usamos PHP 8.4 con Apache para Laravel (alineado con tu entorno local)
FROM php:8.4-apache

# 1. Instalar dependencias del sistema operativo
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Instalar extensiones de PHP requeridas por Laravel y bases de datos (MySQL y PostgreSQL)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# 3. Habilitar mod_rewrite de Apache para que las rutas de Laravel funcionen bien
RUN a2enmod rewrite

# 4. Apuntar la raíz web de Apache a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# 5. Configurar Apache para escuchar en el puerto dinámico de Render ($PORT o 80 por defecto)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 6. Instalar Composer globalmente desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Copiar los archivos del proyecto al contenedor
WORKDIR /var/www/html
COPY . /var/www/html

# 8. Instalar dependencias de PHP para producción
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. Dar permisos de escritura a las carpetas requeridas por Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Puerto por defecto expuesto
EXPOSE 80

# 11. Limpieza de cachés, ejecución segura de migraciones/seeders y arranque de Apache
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate --force && php artisan db:seed --force && php artisan config:cache && php artisan route:cache && apache2-foreground"]
