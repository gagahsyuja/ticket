FROM php:8.5.8-fpm-alpine3.23

WORKDIR /app

RUN apk update && apk add \
    libpng \
    libjpeg \
    libxml2-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

ENV NPM_CONFIG_CACHE=/tmp/.npm
