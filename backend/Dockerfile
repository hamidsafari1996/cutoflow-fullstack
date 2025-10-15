FROM php:8.2-apache

# Install system deps
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libonig-dev libzip-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache

# Enable apache rewrite
RUN a2enmod rewrite

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy existing app
# (we mount volume in docker-compose, so this only helps initial build if needed)
COPY . /var/www/html

# Give permissions
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor || true

EXPOSE 80