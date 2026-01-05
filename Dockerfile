FROM php:8.2-apache

# System deps (tambahkan libzip-dev)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    zip unzip git curl \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP extensions (tambahkan zip)
RUN docker-php-ext-install \
    pdo_mysql pdo_pgsql pgsql \
    mbstring exif pcntl bcmath gd zip

# Apache doc root -> public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

RUN a2enmod rewrite

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# ✅ Copy composer files dulu biar cache aman
COPY composer.json composer.lock ./

# Install vendor dulu (lebih stabil & cepat)
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Baru copy seluruh source
COPY . .

# Permission Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
