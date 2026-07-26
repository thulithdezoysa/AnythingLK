FROM php:8.1-apache

# Install dependencies and PHP extensions (gd, mysqli, pdo_mysql, zip)
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
     libpng-dev \
     libjpeg62-turbo-dev \
     libwebp-dev \
     libfreetype6-dev \
     default-mysql-client \
     libzip-dev \
     zip \
  && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
  && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql zip \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Use the repository files via a bind mount from docker-compose; no COPY here so changes show up immediately.

EXPOSE 80
