# 1) Gunakan Image PHP 8.2 dengan Apache
FROM php:8.2-apache

# 2) Install library system (termasuk libpq-dev untuk Postgres)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# 3) Install ekstensi PHP (MySQL + Postgres + kebutuhan Laravel)
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# 4) Konfigurasi Apache Document Root ke folder "public"
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5) Aktifkan mod rewrite (Laravel routing)
RUN a2enmod rewrite

# 6) Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7) Set folder kerja
WORKDIR /var/www/html

# 8) Copy source code project
COPY . .

# 9) Install vendor (production)
RUN composer install --no-interaction --no-dev --optimize-autoloader

# 10) Permission storage & cache (Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 11) Expose port 80
EXPOSE 80

# 12) Jalankan Apache
CMD ["apache2-foreground"]
