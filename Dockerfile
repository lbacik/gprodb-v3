FROM dunglas/frankenphp:latest-builder-php8

ARG REF
ENV REF=${REF}
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get -y update && apt-get install -y \
    git zip unzip librabbitmq-dev libssl-dev \
    && apt -y clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && pecl install amqp \
    && docker-php-ext-enable amqp

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /app
WORKDIR /app

RUN composer install --no-interaction --optimize-autoloader \
    && ./bin/console tailwind:build \
    && ./bin/console asset-map:compile \
    && ./bin/console assets:install \
