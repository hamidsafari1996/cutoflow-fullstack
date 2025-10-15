FROM php:8.2-apache

# Install system deps
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libonig-dev libzip-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache

# Enable apache rewrite
RUN a2enmod rewrite

# Set Apache DocumentRoot to Symfony's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy existing app
# (we mount volume in docker-compose, so this only helps initial build if needed)
COPY . /var/www/html

# Give permissions
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor || true

EXPOSE 80