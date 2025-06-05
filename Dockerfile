# Dockerfile
FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y unzip git zip && \
    docker-php-ext-install pdo pdo_mysql

# Copy composer from the official Composer image
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Install PHP dependencies via Composer
#RUN composer install --no-interaction --prefer-dist