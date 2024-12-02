FROM php:8.2.1-fpm-alpine as build 

RUN apk update && apk add  \
    bash \
    zlib-dev \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev
 
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./ /app

RUN mkdir -m 0644 -p /etc/cron.d
RUN echo "* * * * * cd /app/api && php artisan schedule:run >> /dev/null 2>&1" >> /etc/cron.d/laravel
RUN crontab /etc/cron.d/laravel

WORKDIR /app

RUN composer install --prefer-dist -o -n
RUN rm -rf .env

ENTRYPOINT [ "bash", "/app/entrypoint.sh" ]