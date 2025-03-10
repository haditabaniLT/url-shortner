FROM php:8.1-apache

# Install MySQLi and enable it
RUN docker-php-ext-install mysqli pdo_mysql

RUN a2enmod rewrite

# Copy your files if you’re not using volumes
COPY ./app/ /var/www/html/

# Fix ownership & permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80
