FROM php:8.3.1-apache

ARG REF
ENV REF=${REF}
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get -y update && apt-get install -y \
    git zip unzip librabbitmq-dev libssl-dev \
    && apt -y clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /opt/app
WORKDIR /opt/app

RUN composer install --no-interaction --optimize-autoloader \
    && ./bin/console tailwind:build \
    && ./bin/console asset-map:compile \
    && ./bin/console assets:install \
    && rm -drf /var/www/html \
    && ln -s /opt/app/public /var/www/html \
    && chown -R www-data:www-data /opt/app/var
