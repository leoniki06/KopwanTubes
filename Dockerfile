FROM php:8.2-apache

# --- System deps (umum untuk Laravel + DB + zip + gd) ---
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
 && rm -rf /var/lib/apt/lists/*

# --- PHP extensions ---
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

# --- Apache: set document root ke /public + enable rewrite ---
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
 && a2enmod rewrite

# --- Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# Cache layer: copy composer file dulu
COPY composer.json composer.lock* ./

# Install dependencies (lebih stabil di CI kalau no-scripts)
RUN composer install \
    --no-interaction \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copy source code
COPY . .

# (Opsional) entrypoint untuk artisan setup ringan
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Laravel permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
