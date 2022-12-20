FROM php:8.1-fpm
RUN apt-get update \
    && apt-get install -y mariadb-client libzip-dev libicu-dev unzip  wget git unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip pdo_mysql sockets opcache intl

# GD用
RUN apt-get update \
  && apt-get install -y libpq-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && apt-get clean
 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


