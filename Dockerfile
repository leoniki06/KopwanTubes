# Base image PHP + Apache
FROM php:8.1-apache

# Install dependency sistem & ekstensi PHP yang umum dipakai Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# Set document root
WORKDIR /var/www/html

# Copy seluruh source code ke container
COPY . .

# Permission agar Apache bisa akses file
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port Apache
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
