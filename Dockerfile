FROM php:8.3.1-apache

ARG REF
ENV REF=${REF}

RUN apt-get -y update && apt-get install -y \
    git zip unzip \
    && a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /opt/app
WORKDIR /opt/app

RUN composer install --no-interaction --optimize-autoloader \
    && ./bin/console tailwind:build \
    && ./bin/console asset-map:compile \
    && rm -drf /var/www/html \
    && ln -s /opt/app/public /var/www/html \
    && chown -R www-data:www-data /opt/app/var
