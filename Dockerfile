FROM php:8.2-apache

# System deps
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
 && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure zip \
 && docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Apache public directory (Laravel)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
 && a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# App ada di folder moodbite/
# Copy composer files dari moodbite untuk cache layer
COPY moodbite/composer.json moodbite/composer.lock* ./

# Install dependencies (aman untuk CI)
RUN composer install \
    --no-interaction \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copy seluruh source Laravel dari moodbite ke workdir
COPY moodbite/ ./

# Permissions Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
