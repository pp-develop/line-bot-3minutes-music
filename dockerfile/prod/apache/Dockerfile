FROM composer AS composer

# copying the source directory and install the dependencies with composer
COPY ./ /app
COPY .env.example /app/.env

# linecorp/line-bot-sdk requires ext-sockets
RUN docker-php-ext-install sockets \
    && composer install --no-interaction --optimize-autoloader --no-dev

FROM php:8.1-apache

# Install Laravel framework system requirements (https://laravel.com/docs/9.x/deployment#server-requirements)
RUN apt-get update \
    && apt-get install -y libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath

COPY --from=composer /app /var/www/html
COPY --from=composer /app/php.ini /usr/local/etc/php/

# changing DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

RUN chown -R www-data /var/www/html/storage